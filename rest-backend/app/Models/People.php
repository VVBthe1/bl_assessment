<?php

namespace App\Models;

use App\Http\Requests\UpdatePeople;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class People extends Model
{
    use HasFactory, SoftDeletes;

    /* @var string $table */
    protected $table = 'bl_people';

    /* @var array $editable */
    protected $editable = ['sort_order'];

    /**
     * Method to update the record while adjusting sort order
     *
     * @param int $requestSortOrder
     * @return void
     */
    public function updateRecord(UpdatePeople $request)
    {
        $requestSortOrder = $request->get('sort_order');
        if ($requestSortOrder !== $this->sort_order) {
            $maxSortOrder = People::max('sort_order');
            $requestSortOrder = ($requestSortOrder > $maxSortOrder) ? $maxSortOrder : $requestSortOrder;
            $this->adjustSortOrder($requestSortOrder);
            $this->sort_order = $requestSortOrder;
            $this->save();
        }
    }

    /**
     * Deletes with sorting adjustment
     *
     * @return void
     */
    public function deleteRecord()
    {
        $requestSortOrder = People::max('sort_order');
        if ($requestSortOrder !== $this->sort_order) {
            $this->adjustSortOrder($requestSortOrder);
            $this->sort_order = $requestSortOrder;
            $this->save();
            $this->delete();
        }
    }

    /**
     * Method to adjust sort order based on provided input
     *
     * @param int $requestSortOrder
     * @return boolean
     */
    public function adjustSortOrder(int $requestSortOrder)
    {
        $existingSortOrder = $this->sort_order;
        $operand = ($existingSortOrder < $requestSortOrder) ? -1 : 1; // deciding on whther to increment value or decrease
        if ($existingSortOrder > $requestSortOrder) { // if existing sort order is greater than requested sort order
            $resource = People::where('sort_order', '>=', $requestSortOrder)->where('sort_order', '<', $existingSortOrder);
        } else if ($existingSortOrder < $requestSortOrder) { // if existing sort order is greater than requested sort order
            $resource = People::where('sort_order', '<=', $requestSortOrder)->where('sort_order', '>', $existingSortOrder);
        } else {
            return true;
        }
        $resource->update(['sort_order' => DB::raw("sort_order + ($operand)")]);
        return true;
    }

}
