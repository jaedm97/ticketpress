/**
 * Front Script
 */

(function ($, window, document) {
    "use strict";

    let checkedSeats = [], vehicleId, elVehicleInfo, elVehicleSeats, elVehicleSummary, elPassengersInfo, elVehicleNotice,
        elDatePicker, elRoutePicker;


    $(document).on('ready', function () {

        vehicleId = $('input[name="vehicle_id"]').val();
        elVehicleNotice = $('.vehicle-notice');
        elVehicleInfo = $('.vehicle-info');
        elVehicleSeats = $('.vehicle-seats');
        elVehicleSummary = $('.vehicle-summary');
        elPassengersInfo = $('.passengers-info');
        elDatePicker = $('.ticketpress-date-selection input[name=selected_date]');
        elRoutePicker = $('.ticketpress-routes-selection input[name=route]');

        elDatePicker.datepicker({dateFormat: 'yy-mm-dd'});
    });

    $(document).on('click', '.btn-booking', function () {

        let bookingButton = $(this),
            bookingButtonAction = bookingButton.data('action'),
            htmlPassengersInfo = '',
            selectedRoute = $('.ticketpress-routes-selection input[name=route]').val();

        if (checkedSeats.length < 1) {
            return;
        }

        if (typeof bookingButtonAction !== 'undefined' && bookingButtonAction === 'continue') {

            checkedSeats.forEach(function (seatNum) {
                htmlPassengersInfo += '<div class="single-passenger">\n' +
                    '<label><input type="text" name="p_info[' + seatNum + '][name]" placeholder="Full Name"></label>\n' +
                    '<label><input type="text" name="p_info[' + seatNum + '][phone]" placeholder="Phone Number"></label>\n' +
                    '</div>';
            });

            bookingButton.data('action', 'confirm').html(ticketPress.confirmBookingText);
            elVehicleSeats.fadeOut(100);
            elPassengersInfo.find('.passengers').html($(htmlPassengersInfo));
            elPassengersInfo.fadeIn(100);

            elRoutePicker.parent().addClass('disabled');
            return;
        }

        if (typeof bookingButtonAction !== 'undefined' && bookingButtonAction === 'confirm') {

            $.ajax({
                type: 'POST',
                context: this,
                url: ticketPress.ajaxURL,
                data: {
                    'action': 'ticketpress_confirm_booking',
                    'vehicle_id': vehicleId,
                    'selected_route': selectedRoute,
                    'selected_seats': checkedSeats,
                    'selected_date': elDatePicker.val(),
                    'passengers': elPassengersInfo.serialize()
                },
                success: function (response) {
                    if (response.success) {
                        elVehicleInfo.fadeOut(100);
                        elVehicleSeats.fadeOut(100);
                        elVehicleSummary.fadeOut(100);
                        elPassengersInfo.fadeOut(100);
                        elVehicleNotice.html(response.data).fadeIn(100);
                    }
                }
            });
        }
    });


    $(document).on('ticketpress_seats_allocation_may_change', function () {

        let elSeatsSelected = $('.ticketpress-seats-selected'),
            elTicketPrice = $('.ticketpress-ticket-price'),
            elTotalPrice = $('.ticketpress-total-price'),
            ticketPrice = elTicketPrice.html(),
            seatsSelectedCount = checkedSeats.length,
            totalPrice = 0;

        if (typeof elTicketPrice !== 'undefined' && typeof ticketPrice !== 'undefined') {
            totalPrice = seatsSelectedCount * ticketPrice;
        }

        if (checkedSeats.length > 0) {
            elVehicleSummary.fadeIn(100);
        } else {
            elVehicleSummary.fadeOut(100);
        }

        elSeatsSelected.html(seatsSelectedCount);
        elTotalPrice.html(totalPrice);
    });


    $(document).on('change', '.ticketpress-routes-selection input[name=route]', function () {

        let thisRoute = $(this),
            thisRouteParent = thisRoute.parent();

        if (elDatePicker.val().length === 0) {
            elDatePicker.focus();
            thisRoute.prop('checked', false);
            return;
        }

        thisRouteParent.parent().find('label').removeClass('selected');
        thisRouteParent.parent().find('input:checked').parent().toggleClass('selected');

        if (thisRouteParent.parent().find('input[type=radio]:checked').val().length !== 0) {

            $.ajax({
                type: 'POST',
                context: this,
                url: ticketPress.ajaxURL,
                data: {
                    'action': 'ticketpress_load_seats',
                    'vehicle_id': vehicleId,
                    'selected_route': thisRoute.val(),
                    'selected_date': elDatePicker.val(),
                },
                success: function (response) {
                    if (response.success) {
                        elVehicleSeats.find('.seat-row-wrap').html(response.data);
                        elVehicleSeats.fadeIn();
                    }
                }
            });
        }

        elDatePicker.addClass('disabled');
    });


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

        $(document.body).trigger('ticketpress_seats_allocation_may_change');
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


