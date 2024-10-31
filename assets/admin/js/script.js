jQuery(function ($) {

    if ($().select2) {
        $('.attributes-settings-select').select2({
            'allowClear': false,
            minimumResultsForSearch: 100,
            // templateSelection: function (data) {
            //     var $text = $('<span class="' + data.id + '">' + data.text + '</span>');
            //     console.log($text);
            //     return $text;
            // },
            templateResult: function (data, container) {
                if (data.element) {
                    $(container).addClass($(data.element).attr("class"));
                }
                return data.text;
            },

        });
    }

    $('.premmerce-attr-settings-color-picker').wpColorPicker();
});
