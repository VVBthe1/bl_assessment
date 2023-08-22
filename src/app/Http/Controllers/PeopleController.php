<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeople;
use App\Http\Requests\UpdatePeople;
use App\Models\People;
use Illuminate\Support\Facades\DB;

class PeopleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(People::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Not used
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePeople $request)
    {
        $people = new People();
        $people->name = $request->get('name');
        $people->sort_order = (int) People::max('sort_order') + 1;
        $people->save();
        return response()->json("Saved successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\People  $people
     * @return \Illuminate\Http\Response
     */
    public function show(People $people)
    {
        return response()->json($people);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\People  $people
     * @return \Illuminate\Http\Response
     */
    public function edit(People $people)
    {
        return response()->json($people);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\People  $people
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePeople $request, People $people)
    {
        DB::transaction(function () use ($people, $request) {
            $newSortOrder = $request->get('sort_order');
            $people->updateDetails($request);
        });
        return response()->json("Saved successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\People  $people
     * @return \Illuminate\Http\Response
     */
    public function destroy(People $people)
    {
        $people->deleteRecord();
        return response()->json("Deleted successfully");
    }
}
