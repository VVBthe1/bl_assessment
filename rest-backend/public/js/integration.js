$(function () {
    $("#sortable").sortable();
    $("#error-msg").hide();
    $("#success-msg").hide();
    $.ajaxSetup({
        error: function(jqXHR, exception) {
            console.log(jqXHR);
            message = 'Unable to peocess the request';
            if (jqXHR.status === 0) {
                message = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                message = 'Requested page not found. [404]';
            } else if (jqXHR.status == 500) {
                message = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                message = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                message = 'Time out error.';
            } else if (exception === 'abort') {
                message = 'Ajax request aborted.';
            } else {
                var err = JSON.parse(jqXHR.responseText);
                message = err.message;
            }
            $("#success-msg").hide();
            $("#error-msg").show();
            $("#error-msg").children('p').children('span').html(message)
        }
    });
});

var loggedIn = false;
var token = null;
var api_url = 'http://localhost:8000/api';

$.ajax({
    type: "POST",
    url: api_url + "/login",
    async: false,
    data: {
        "email": "assessment@gmail.com"
    },
    dataType: 'json',
    success: function (data) {
        loggedIn = true;
        token = data.response.token;
        console.log(token);
    },

});

function getList() {
    $.ajax({
        type: "GET",
        url: api_url + "/peoples",
        async: false,
        headers: {
            'Authorization': "Bearer " + token,
        },
        dataType: 'json',
        success: function (data) {
            data.response.forEach(element => {
                // console.log(element, element.name)
                $('#sortable').append('<li class="ui-state-default" data-id="' + element.id + '"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span> Hello  <input type="text" class="item-input" value="' + element.name + '" /><button class="save">Save</button><button class="delete">X</button></li>');
            });
        },

    });
}

getList() // calling item list function

$("#sortable").on( "sortupdate", function( event, ui ) {
    $.ajax({
        type: "PUT",
        url: api_url + "/peoples/" + ui.item.attr('data-id'),
        async: false,
        data: {
            sort_order: ui.item.index() + 1
        },
        headers: {
            'Authorization': "Bearer " + token,
        },
        dataType: 'json',
        success: function (data) {
            $("#error-msg").hide();
            $("#success-msg").show();
            $("#success-msg").children('p').children('span').html('Saved successfully')
        },

    });
} );

$("#sortable .delete").click(function() {

    $.ajax({
        type: "DELETE",
        url: api_url + "/peoples/" + $(this).parent().attr('data-id'),
        async: false,
        data: {
            sort_order: $(this).parent().index() + 1
        },
        headers: {
            'Authorization': "Bearer " + token,
        },
        dataType: 'json',
        success: function (data) {
            console.log(data.response);
            $("#error-msg").hide();
            $("#success-msg").show();
            $("#success-msg").children('p').children('span').html('Saved successfully')
        }

    });
    $(this).parent().remove();
});

$("#sortable .save").click(function() {
    $.ajax({
        type: "PUT",
        url: api_url + "/peoples/" + $(this).parent().attr('data-id'),
        async: false,
        data: {
            name: $(this).parent().children('.item-input').val(),
            sort_order: $(this).parent().index() + 1
        },
        headers: {
            'Authorization': "Bearer " + token,
        },
        dataType: 'json',
        success: function (data) {
            $("#error-msg").hide();
            $("#success-msg").show();
            $("#success-msg").children('p').children('span').html('Saved successfully');
        },

    });
});

$(".add").click(function() {
    $.ajax({
        type: "POST",
        url: api_url + "/peoples",
        async: false,
        data: {
            name: $('.add-item-input').val(),
        },
        headers: {
            'Authorization': "Bearer " + token,
        },
        dataType: 'json',
        success: function (data) {
            $('#sortable').append('<li class="ui-state-default" data-id="' + data.response.id + '"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span> Hello  <input type="text" class="item-input" value="' + data.response.name + '" /><button class="save">Save</button><button class="delete">X</button></li>');

            $("#error-msg").hide();
            $("#success-msg").show();
            $("#success-msg").children('p').children('span').html('Saved successfully');
            $('.add-item-input').val('');
        },

    });
});

