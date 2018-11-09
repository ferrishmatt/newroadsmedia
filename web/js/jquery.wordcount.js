(function ($) {
    $.fn.wordCount = function(updateFunction) {
        var calculateWordCount = function() {
            var value = this.val();
            var wordCount = 0;
            if (value.length > 0) {
                wordCount = value.trim().replace(/\s+/gi, ' ').split(' ').length;
            }
            updateFunction(wordCount);
        }.bind(this);
        this.change(calculateWordCount).keyup(calculateWordCount);

        return this;
    };
}(jQuery));