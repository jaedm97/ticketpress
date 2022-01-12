/**
 * Front Script
 */

(function ($, window, document, pluginObject) {
    "use strict";

    // $(document).on('click', '.cstools-get-poll-results', function () {
    //
    //     let pollID = $(this).data('poll-id');
    //
    //     if (typeof pollID === 'undefined') {
    //         return;
    //     }
    //
    //     let singlePoll = $('#poll-' + pollID);
    //
    //     singlePoll.find('.cstools-responses').slideUp();
    //
    //     $.ajax({
    //         type: 'POST',
    //         context: this,
    //         url: pluginObject.ajaxurl,
    //         data: {
    //             'action': 'cstools_get_poll_results',
    //             'poll_id': pollID,
    //         },
    //         success: function (response) {
    //
    //             if (!response.success) {
    //                 singlePoll.find('.cstools-responses').addClass('cstools-error').html(response.data).slideDown();
    //                 return;
    //             }
    //
    //             singlePoll.find('.cstools-options .cstools-option-single').each(function () {
    //
    //                 let optionID = $(this).data('option-id'),
    //                     percentageValue = response.data.percentages[optionID],
    //                     singleVoteCount = response.data.singles[optionID],
    //                     classTobeAdded = '';
    //
    //                 if (typeof percentageValue === 'undefined') {
    //                     percentageValue = 0;
    //                 }
    //
    //                 if (typeof singleVoteCount === 'undefined' || singleVoteCount.length === 0) {
    //                     singleVoteCount = 0;
    //                 }
    //
    //                 if (percentageValue <= 25) {
    //                     classTobeAdded = 'results-danger';
    //                 } else if (percentageValue > 25 && percentageValue <= 50) {
    //                     classTobeAdded = 'results-warning';
    //                 } else if (percentageValue > 50 && percentageValue <= 75) {
    //                     classTobeAdded = 'results-info';
    //                 } else {
    //                     classTobeAdded = 'results-success';
    //                 }
    //
    //                 if ($.inArray(optionID, response.data.percentages)) {
    //                     $(this).addClass('has-result').find('.cstools-option-result-bar').addClass(classTobeAdded).css('width', percentageValue + '%');
    //                     $(this).find('.cstools-option-result').html(singleVoteCount + ' ' +  pluginObject.voteText);
    //                 }
    //             });
    //         }
    //     });
    // });

})(jQuery, window, document, cstools_object);







