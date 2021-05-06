<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

global $wp_embed;
$embed = '';
$video_w = 500;
$video_h = $video_w / 1.61;
if ( is_object( $wp_embed ) ) {
    $embed = $wp_embed->run_shortcode( '[embed width="' . $video_w . '"' . $video_h . ']' . $video_url . '[/embed]' );
}

$uniqId = rand(1000, 1000000);
?>
<a href="#" id="youtube-play-video-wrap" class="youtube-play-video-wrap-<?php echo esc_attr($uniqId);?>">
    <div class="youtube-play-circle" style="background: <?php echo stm_do_lmth($color); ?>">
        <i class="fa fa-play"></i>
    </div>
</a>
<div id="video-popup-wrap" class="video-popup-wrap video-popup-wrap-<?php echo esc_attr($uniqId);?>" style="display: none;">
    <div class="video-popup">
        <div class="wpb_video_wrapper"><?php echo stm_do_lmth($embed); ?></div>
    </div>
</div>

<script>
    (function($) {
        var yLG = $('.youtube-play-video-wrap-<?php echo esc_attr($uniqId);?>');
        yLG.on('click', function(e) {
            e.stopPropagation();
            e.preventDefault();

            $(this).lightGallery({
                iframe: true,
                youtubePlayerParams: {
                    modestbranding: 1,
                    showinfo: 0,
                    rel: 0,
                    controls: 0
                },
                dynamic: true,
                dynamicEl: [{
                    src  : $('.video-popup-wrap-<?php echo esc_attr($uniqId);?>').find('iframe').attr('src')
                }],
                download: false,
                mode: 'lg-fade',
            });
        })
    })(jQuery);
</script>