$(function() {
    var $sections = $('.sections .section');
    var $section = $(window.location.hash);
    if ($section.length == 0) {
        var $errorSections = $('.sections .section.has-errors');
        if ($errorSections.length == 0) {
            $section = $sections.first();
        } else {
            $section = $errorSections.first();
        }
    }

    $sections.not($section).removeClass('active').find('.section-content').hide();
    $section.addClass('active');
    var index = $section.index();
    $('#content').find('.links ul li').removeClass('active').eq(index).addClass('active');

    // links
    $('a[href*=#]').click(function(e) {
        e.preventDefault();
        var hash = $(this).attr('href');
        var $section = $(hash);
        if ($section.length == 0) {
            return;
        }
        if ($section.hasClass('active')) {
            $section.find('.section-content').slideToggle();
        } else {
            $sections.not($section).removeClass('active').find('.section-content').slideUp();
            $section.addClass('active').find('.section-content').slideDown();
            var index = $section.index();
            $('#content').find('.links ul li').removeClass('active').eq(index).addClass('active');
        }
    });

    // form submit
    $('form').submit(function() {
        var isValid = true;
        $(':input[required="required"]').removeClass('has-error').each(function() {
            if (isValid && $(this).val().length == 0) {
                var $section = $(this).parents('.section');
                $sections.not($section).find('.section-content').slideUp();
                var $input = $(this);
                $section.find('.section-content').slideDown(function() {
                    $input.focus().addClass('has-error');
                });
                isValid = false;
            }
        });

        return isValid;
    });

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
    var $currentJobStatusChoice = $('#profile_edit_resume_currentJobStatusChoice');
    var $currentJobStatus = $('#profile_edit_resume_currentJobStatus');
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

        // apply ckeditor
        var $ckeditor = $newForm.find('.ckeditor');
        if ($ckeditor.length > 0) {
            $ckeditor.each(function() {
                CKEDITOR.replace($(this)[0]);
            });
        }

        // apply date pickers
        $('.jquery-date').datepicker({});
    });
    $(document).on('click', '.form-delete', function(e) {
        e.preventDefault();
        var $collection = $(this).closest('.form-collection');
        var childName = $collection.data('child-name');
        var $child = $(this).closest('.' + childName);
        $child.remove();
    });
});