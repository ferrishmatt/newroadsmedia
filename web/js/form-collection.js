$(function() {
    var $x = $('<a href="#" class="form-delete">x</a>');

    $(document).on('click', '.form-add', function() {
        var $collection = $(this).siblings('.form-collection');
        var index = $collection.data('index') || 0;
        index++;
        var prototype = $collection.data('prototype');
        var prototypeName = $collection.data('prototype-name');
        var regExp = new RegExp(prototypeName, 'g');
        var $newForm = $(prototype.replace(regExp, index));

        $newForm.prepend($x.clone());
        $collection.append($newForm).data('index', index);

        return false;
    });

    $(document).on('click', '.form-delete', function() {
        var index = $('.form-delete').index($(this));
        var $collection = $(this).closest('.form-collection');
        var $child = $collection.find('> div').eq(index);
        $child.remove();

        return false;
    });

    $('.form-collection').each(function() {
        var $existing = $(this).find('> div');
        $existing.each(function() {
            $(this).prepend($x.clone());
        });
        var $formAdd = $(this).siblings('.form-add');
        for (var i = $existing.length; i < 1; i++) {
            $formAdd.click();
        }
    });
});