$(function() {
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
        var childSelector = $collection.data('child-selector');
        var $child = $(this).closest(childSelector);
        $child.remove();
    });

    $(document).on('submit', 'form', function() {
        var valid = true;
        $(this).find(':input[required="required"]').each(function() {
            $(this).removeClass('has-error');
            if ($(this).val() == '') {
                $(this).addClass('has-error');
                if (valid) {
                    showTab('#' + $(this).closest('.content').attr('id'));
                    $(this).focus();
                }
                valid = false;
            }
        });
        return valid;
    });

    $(document).on('click', '#profile-menu ul li a', function() {
        showTab($(this).attr('href'));
        return false;
    });

    function showTab(id) {
        $('#profile-menu ul li').removeClass('active');
        $('#profile-menu ul li a[href="' + id + '"]').closest('li').addClass('active');
        $('#profile-content .content').hide();
        $(id).show();
    }

    var $errors = $('.form-errors:first');
    if ($errors.length > 0) {
        showTab('#' + $errors.first().closest('.content').attr('id'));
    }

    // TJ
    // date pickers
    var $jQueryDates = $('.jquery-date');
    if ($jQueryDates.length > 0) {
        $('.jquery-date').datepicker({});
    }

    // date or active toggles
    var dateToggle = function() {
        var $dateExpiration = $($(this).data('date-toggle'));
        if ($(this).is(':checked')) {
            $dateExpiration.attr('disabled', 'disabled');
        } else {
            $dateExpiration.removeAttr('disabled');
        }
    }
    $(':input[data-date-toggle]')
        .each(dateToggle)
        .change(dateToggle)
    ;

    // current job status
    var $currentJobStatusChoice = $('#resume_currentJobStatusChoice');
    var $currentJobStatus = $('#resume_currentJobStatus');
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
});