/**
 * Front Script
 */

(function ($, window, document) {
    "use strict";

    let checkedSeats = [];

    $(document).on('change', '.single-vehicle .vehicle-seats .seat-row .seat input[type=checkbox]', function () {

        let thisSeat = $(this),
            thisSeatParent = thisSeat.parent(),
            seatNumber = thisSeat.val();

        if (checkedSeats.length >= 4) {

            if (thisSeatParent.hasClass('booked')) {
                thisSeatParent.removeClass('booked');
            }

            checkedSeats.remove(seatNumber);
            this.checked = false;
            return;
        }

        if (thisSeat.is(':checked') && $.inArray(seatNumber, checkedSeats) !== 0) {
            checkedSeats.push(seatNumber);
        }

        if (!thisSeat.is(':checked')) {
            checkedSeats.remove(seatNumber);
        }

        thisSeatParent.toggleClass('booked');
    });


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


    $(document).on('click', '.action.action-fav', function () {

        let favButton = $(this),
            vehicleID = favButton.data('id'),
            vehicleAction = favButton.hasClass('fav') ? 'unfav' : 'fav';

        if (vehicleID.length <= 0 || typeof vehicleID === 'undefined') {
            return;
        }

        $.ajax({
            type: 'POST',
            context: this,
            url: ticketPress.ajaxURL,
            data: {
                'action': 'ticketpress_vehicle_fav',
                'vehicle_id': vehicleID,
                'vehicle_action': vehicleAction,
            },
            success: function (response) {
                if (response.success) {

                    if (vehicleAction === 'fav') {
                        favButton.addClass('fav').html(ticketPress.unFavText)
                    }

                    if (vehicleAction === 'unfav') {
                        favButton.removeClass('fav').html(ticketPress.favText)
                    }
                }
            }
        });

    });


})(jQuery, window, document);

Array.prototype.remove = function (x) {
    var i;
    for (i in this) {
        if (this[i].toString() === x.toString()) {
            this.splice(i, 1)
        }
    }
}


