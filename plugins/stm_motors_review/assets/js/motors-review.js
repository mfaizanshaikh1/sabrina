(function ($) {
    'use strict'

    $(document).ready(function($) {
        $('#review-play-btn').on('click', function(ev) {
            $('.stm-review-video').addClass('video-played');
            $(".iframeWrap").attr('style', 'display: block;');
            $(".iframeWrap iframe")[0].src += "?autoplay=1";
            ev.preventDefault();

        });

        $('body').on('click', '.stm_load_review', function (e) {
            e.preventDefault();

            $.ajax({
                url: stm_ajaxurl,
                dataType: 'json',
                context: this,
                data: {
                    'page': $(this).attr('data-page'),
                    'per_page': $(this).attr('data-per_page'),
                    'post_type': $(this).attr('data-post_type'),
                    'action': 'review_load_more_posts'
                },
                beforeSend: function beforeSend() {
                    $(this).addClass('loading');
                },
                complete: function complete(data) {
                    $(this).removeClass('loading');
                    var dt = data.responseJSON;
                    var $items = $(dt.content);

                    var contentWrapper = $('.stm-review-load-block');
                    contentWrapper.append($items);

                    if (dt.page) {
                        $(this).attr('data-page', dt.page);
                    } else {
                        $(this).remove();
                    }
                }
            });
        });
    })
}(jQuery));