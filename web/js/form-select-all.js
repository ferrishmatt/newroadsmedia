$(function() {
    $(document).on('change', '.select-all', function() {
        var $checkboxes = $(this).closest('table').find('tbody input[type="checkbox"]');
        $checkboxes.prop('checked', $(this).is(':checked'));
    });
});