$(function() {
    var $ul = $('.privacy-settings ul');
    var $li = $ul.find('li');
    var $isPublic = $('#upload_resume_isPublic');
    $li.click(function() {
        $li.removeClass('active');
        $(this).addClass('active');
        $isPublic.val($(this).index() == 0 ? 1 : 0);
    });
});