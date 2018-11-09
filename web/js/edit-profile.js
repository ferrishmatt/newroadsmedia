$(function() {
    // date pickers
    $('.jquery-date').datepicker({});

    // date or active toggles
    $(':input.date-toggle-checkbox')
        .each(function() {
            var $dateExpiration = $(this).siblings(':input.date-toggle');
            if ($(this).is(':checked')) {
                $dateExpiration.attr('disabled', 'disabled');
            } else {
                $dateExpiration.removeAttr('disabled');
            }
        })
        .change(function() {
            var $dateExpiration = $(this).siblings(':input.date-toggle');
            if ($(this).is(':checked')) {
                $dateExpiration.attr('disabled', 'disabled');
            } else {
                $dateExpiration.removeAttr('disabled');
            }
        })
    ;

    // current job status
    var $currentJobStatusChoice = $('#profile_candidate_snapshot_currentJobStatusChoice');
    var $currentJobStatus = $('#profile_candidate_snapshot_currentJobStatus');
    $currentJobStatusChoice
        .change(function() {
            if ($(this).val() == 'Other') {
                $currentJobStatus.removeAttr('disabled').show().focus();
            } else {
                $currentJobStatus.hide().attr('disabled', 'disabled');
            }
        })
        .val($currentJobStatus.val())
    ;
    if (!$currentJobStatusChoice.val() && $currentJobStatus.val() != '') {
        $currentJobStatusChoice.val('Other');
    } else {
        $currentJobStatus.val('');
    }
    $currentJobStatusChoice.trigger('change');

    // Word Count
    $('#profile_candidate_snapshot_resumeJobObjective').wordCount(function(wordCount) {
        $('#profile_candidate_snapshot_resumeJobObjective_word_count').text('You entered ' + wordCount + ' word' + (wordCount == 1 ? '' : 's')  + '.');
    });

    // prototypes
    $(document).on('click', '.form-add', function(e) {
        e.preventDefault();
        var $collection = $(this).siblings('.form-collection');
        var index = $collection.data('index') || 0;
        index++;
        var prototype = $collection.data('prototype');
        var prototypeName = $collection.data('prototype-name');
        var regExp = new RegExp(prototypeName, 'g');
        var $newForm = $(prototype.replace(regExp, index));
        $collection.append($newForm).data('index', index);

        var $addForms = $newForm.find('.form-add');
        if ($addForms.length > 0) {
            $addForms.each(function() {
                $(this).click();
                var $newCollection = $(this).siblings('.form-collection');
                $newCollection.find('.form-delete').remove();
            });
        }
    });
    $(document).on('click', '.form-delete', function(e) {
        e.preventDefault();
        var $collection = $(this).closest('.form-collection');
        var childName = $collection.data('child-name');
        var $child = $(this).closest('.' + childName);
        $child.remove();
    });
});