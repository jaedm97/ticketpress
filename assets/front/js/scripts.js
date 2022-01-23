/**
 * Front Script
 */

(function ($, window, document) {
    "use strict";


    $(document).on('change', '#route', function () {
        $('#VehicleFilterForm').submit();
    });

    $(document).on('click', '#formReset', function () {

        let url = window.location.href.split('?');

        window.location.href = url[0];
    });

    $(document).on('submit', '#VehicleFilterFormAjax', function (e) {

        e.preventDefault();

        let searchForm = $(this),
            allVehicles = $('.all-vehicles');

        $.ajax({
            type: 'POST',
            context: this,
            url: ticketPress.ajaxURL,
            data: {
                'action': 'ticketpress_search_vehicle',
                'form_data': searchForm.serialize(),
            },
            success: function (response) {

                if (response.success) {
                    allVehicles.html(response.data);
                }
            }
        });


        return false;
    });

})(jQuery, window, document);


