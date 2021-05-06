jQuery(document).ready(function ($) {
    "use strict";

    function changeHeaderEl() {
        var curVal = $('#review_archive option:selected').val();

        console.log(curVal);

        if(curVal == 'block') {
            $('#customize-control-review_archive_sidebar_position').hide();
            $('#customize-control-review_block_title_bg').show();
            $('#customize-control-review_subtitle').show();
        } else {
            $('#customize-control-review_archive_sidebar_position').show();
            $('#customize-control-review_block_title_bg').hide();
            $('#customize-control-review_subtitle').hide();
        }
    }

    if($('#review_archive').length) {
        changeHeaderEl();
        console.log(1);
        $('#review_archive').on('change', function(){
            console.log(2);
            changeHeaderEl();
        });
    }
});