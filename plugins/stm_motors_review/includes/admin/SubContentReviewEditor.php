<?php
/* Second Post Editor TinyMCE Editor */
class SubContentReviewEditor {

	public $meta_key = 'subcontent';
	public $meta_label = 'Top content'; // Headline above editor
	public $post_type = array( 'page' ); // The post type in which the editor should display
	public $wpautop = true; // Automatically create paragraphs?

	function __construct() {
		add_action( 'edit_form_after_title', array( &$this, 'edit_form_after_editor' ) );
		add_action( 'save_post', array( &$this, 'save_post' ) );
		add_action( 'init', array( &$this, 'init' ) );
	}

	public function init() {
		$this->meta_key = apply_filters( 'motors-review-sce-meta_key', $this->meta_key );
		$this->post_type = apply_filters( 'motors-review-sce-post_type', $this->post_type );
		$this->meta_label = apply_filters( 'motors-review-sce-meta_label', $this->meta_label );
		$this->wpautop = apply_filters( 'motors-review-sce-wpautop', $this->wpautop );
	}

	public function edit_form_after_editor() {
		if ( !is_admin() ) { return; }

		if ( in_array( get_post_type( $GLOBALS['post'] ), $this->post_type ) ) { return; }

		$value = $this->get_the_review_subcontent();

		$sc_arg = array(
			'textarea_rows' => apply_filters( 'motors-review-sce-row', 10 ),
			'wpautop' => $this->wpautop,
			'media_buttons' => apply_filters( 'motors-review-sce-media_buttons', true ),
			'tinymce' => apply_filters( 'motors-review-sce-tinymce', true ),
		);

		echo '<h3 class="subcontentLabel" style="margin-top:15px;">' . $this->meta_label . '</h3>';
		wp_editor( $value, 'subcontent', $sc_arg );

	}

	public function save_post( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		if ( isset ( $_POST[ $this->meta_key ] ) ) {
			return update_post_meta( $post_id, $this->meta_key, $_POST[ $this->meta_key ] );
		}
		delete_post_meta( $post_id, $this->meta_key );

		return $post_id;
	}

	public function get_the_review_subcontent() {
		global $post;
		$subcontent = do_shortcode(get_post_meta( $post->ID, $this->meta_key, true ));
		if ( $this->wpautop == true ) {
			return wpautop( $subcontent );
		} else {
			return $subcontent;
		}
	}

}

if((isset($_POST["post_type"]) && $_POST["post_type"] == "stm_review") || (isset($_GET["post_type"]) && $_GET["post_type"] == "stm_review") || (isset($_GET['post']) && get_post_type($_GET["post"]) == 'stm_review')) $motorsReviewSCE = new SubContentReviewEditor();

function get_the_review_subcontent() {
	global $motorsReviewSCE;
	return $motorsReviewSCE->get_the_review_subcontent();
}

/**
 * use get_the_subcontent() where you want the content of the
 * second editor to display, just like the_content
 */
function the_review_subcontent() {
	echo get_the_review_subcontent();
}