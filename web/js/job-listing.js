$(function() {
    var $tabs = $('.resume-tabs .tab');
    var $tabLinks = $('.resume-tabs ul li');
    $tabLinks.find('a').click(function(e) {
        e.preventDefault();
        var id = $(this).attr('href');
        $tabs.removeClass('active').find(':input').attr('disabled', 'disabled');
        $(id).addClass('active').find(':input').removeAttr('disabled');

        $tabLinks.removeClass('active');
        $(this).parents('li').addClass('active');
    });
    $tabs.each(function() {
        if (!$(this).hasClass('active')) {
            $(this).find(':input').attr('disabled', 'disabled');
        }
    });

    $('#non_registered_application_coverLetter').wordCount(function(wordCount) {
        $('.non-registered-cover-letter-word-count').text('You entered ' + wordCount + ' word' + (wordCount == 1 ? '' : 's')  + '.');
    });
    $('#registered_application_coverLetter').wordCount(function(wordCount) {
        $('.registered-cover-letter-word-count').text('You entered ' + wordCount + ' word' + (wordCount == 1 ? '' : 's')  + '.');
    });

    var interval = 100;
    var show = false;
    var hide = function() {
        if (!show) {
            $('.job-listing-share').hide();
        }
    };
    $('.share-job-link')
        .click(function() {
            return false;
        })
        .mouseover(function() {
            var top = $(this).position().top + $(this).outerHeight();
            var left = $(this).position().left;
            $('.job-listing-share').css({top: top, left: left}).show();
            show = true;
        })
        .mouseout(function() {
            show = false;
            setTimeout(hide, interval);
        })
    ;
    $('.job-listing-share')
        .mouseover(function() {
            show = true;
        })
        .mouseout(function() {
            show = false;
            setTimeout(hide, interval);
        })
    ;

    var $dialogEmail = $('#dialog-email');
    var dialogEmailContent = $dialogEmail.html();
    var dialogEmailButtons = [
        {
            'text': 'Send Email',
            'click': function() {
                var $form = $(this).find('form:first');
                var url = $form.attr('action');
                var request = $form.serialize();
                $.ajax({
                    url: url,
                    data: request,
                    method: 'POST',
                    error: function(jqXHR, textStatus, errorThrown) {
                        var errors = JSON.parse(jqXHR.responseText);
                        $dialogEmail.find('.form-errors').remove();
                        var $ul = $('<ul class="form-errors"></ul>');
                        $.each(errors, function(index, error) {
                            $ul.append('<li>' + error + '</li>');
                        });
                        $dialogEmail.prepend($ul);
                        $('#dialog-email img')[0].src = '/gcb/generate-captcha/gcb_captcha?n=' + (new Date()).getTime();
                    },
                    success: function (response) {
                        var message = response['message'];
                        $dialogEmail.html('<p class="success">' + message + '</p>');
                        $dialogEmail.dialog('option', 'buttons', [
                            {
                                'text': 'Ok',
                                'click': function() {
                                    $(this).dialog('close');
                                }
                            }
                        ]);
                    }
                });
            }
        }
    ];
    $dialogEmail.dialog({
        autoOpen: false,
        buttons: dialogEmailButtons,
        close: function() {
            $dialogEmail.html(dialogEmailContent).dialog('option', 'buttons', dialogEmailButtons);
        },
        draggable: false,
        hide: 'fade',
        modal: true,
        resizable: false,
        show: 'fade',
        title: 'Email',
        width:'640px'
    });
    $('.email').click(function() {
        $dialogEmail.dialog('open');

        return false;
    });
});