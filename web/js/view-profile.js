$(function() {
    var $tabs = $('#view-profile-tabs .tab');
    var $links = $('#view-profile-snapshot .tabs li');
    var $tab = $(window.location.hash);
    if ($tab.length == 0) {
        $tab = $tabs.first();
    }
    $tabs.not($tab).hide();
    $tab.show();
    $links.removeClass('active').eq($tab.index()).addClass('active');

    // links
    $('a[href*=#]').click(function(e) {
        var hash = $(this).attr('href');
        var $tab = $(hash);
        $tabs.not($tab).hide();
        $tab.show();
        $links.removeClass('active').eq($tab.index()).addClass('active');
    });
});