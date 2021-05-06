<?php


add_action('init', 'stm_motors_review_include_customizer');

function stm_motors_review_include_customizer()
{
    require_once __DIR__ . '/customizer/customizer.class.php';
}

function motors_review_wp_head()
{
	?>
	<script type="text/javascript">
        var stm_ajaxurl = '<?php echo esc_url(admin_url('admin-ajax.php')); ?>';
	</script>
	<?php
}

add_action('wp_head', 'motors_review_wp_head');

function review_pagination($pagination = array(), $defaults = array())
{
	$pagination['prev_text'] = '<i class="fa fa-chevron-left"></i>';
	$pagination['next_text'] = '<i class="fa fa-chevron-right"></i>';

	$pagination['type'] = 'array';

	$pagination = wp_parse_args($pagination, $defaults);

	$pagination = paginate_links($pagination);
	if (!empty($pagination)):
		$has_prev = '';
		$has_next = '';
		foreach ($pagination as $page) {
			if (strpos($page, 'prev page-numbers') !== false) $has_prev = 'stm_has_prev';
			if (strpos($page, 'next page-numbers') !== false) $has_next = 'stm_has_next';
		}


		ob_start();

		?>
		<ul class="page-numbers clearfix stm_review_pagination <?php echo esc_attr($has_prev . ' ' . $has_next) ?>">
			<?php foreach ($pagination as $key => $page):
				$class = 'stm_page_num';
				if (strpos($page, 'prev') !== false) $class = 'stm_prev';
				if (strpos($page, 'next') !== false) $class = 'stm_next';
				?>
				<li class="<?php echo esc_attr($class); ?>">
					<?php echo wp_kses_post($page); ?>
				</li>
			<?php endforeach; ?>
		</ul>

		<?php $pagination = ob_get_clean();
	endif;

	return $pagination;
}

function review_get_terms_array($id, $taxonomy, $filter, $link = false, $args = '')
{
	$terms = wp_get_post_terms($id, $taxonomy);
	if (!is_wp_error($terms) and !empty($terms)) {
		if ($link) {
			$links = array();
			if (!empty($args)) $args = stm_motors_array_as_string($args);
			foreach ($terms as $term) {
				$url = get_term_link($term);
				$links[] = "<a {$args} href='{$url}' title='{$term->name}'>{$term->name}</a>";
			}
			$terms = $links;
		} else {
			$terms = wp_list_pluck($terms, $filter);
		}
	} else {
		$terms = array();
	}

	return apply_filters('review_get_terms_array', $terms);
}

function stm_review_body_class_list($classes) {

	$reviewSidebarMode = get_theme_mod('review_archive_sidebar_position', 'none');
	$archivePageView = get_theme_mod('review_archive', 'list');
	if(isset($_GET['view_type'])) {
		$view = $_GET['view_type'];
	} else {
		$view = $archivePageView;
	}

	$classes[] = 'stm_motors_review_list no_margin sidebar_' . $reviewSidebarMode . ' event_' . $view;

	return $classes;
}

function getCarInfoTitle($key = '') {
	$carInfo = array(
		'fuel' => esc_html__('Fuel Type', 'stm_motors_review'),
		'engine' => esc_html__('Engine', 'stm_motors_review'),
		'transmission' => esc_html__('Transmission', 'stm_motors_review'),
		'drive' => esc_html__('Drives', 'stm_motors_review'),
		'msrp' => esc_html__('MSRP', 'stm_motors_review'),
		'city_mpg' => esc_html__('City MPG', 'stm_motors_review'),
		'highway_mpg' => esc_html__('Highway MPG', 'stm_motors_review')
	);

	if(!empty($key)) {
		return $carInfo[$key];
	}

	return $carInfo;
}

function stm_single_review_counter()
{
	if (is_singular('stm_review')) {
		//Views
		$cookies = '';

		if (empty($_COOKIE['stm_review_watched'])) {
			$cookies = get_the_ID();
			setcookie('stm_review_watched', $cookies, time() + (86400 * 30), '/');
			stm_increase_review_rating(get_the_ID());
		}

		if (!empty($_COOKIE['stm_review_watched'])) {
			$cookies = $_COOKIE['stm_review_watched'];
			$cookies = explode(',', $cookies);

			if (!in_array(get_the_ID(), $cookies)) {
				$cookies[] = get_the_ID();

				$cookies = implode(',', $cookies);

				stm_increase_review_rating(get_the_ID());
				setcookie('stm_review_watched', $cookies, time() + (86400 * 30), '/');
			}
		}

		if (!empty($_COOKIE['stm_review_watched'])) {
			$watched = explode(',', $_COOKIE['stm_review_watched']);
		}
	}
}

function stm_increase_review_rating($post_id)
{
	$current_rating = intval(get_post_meta($post_id, 'stm_review_views', true));
	if (empty($current_rating)) {
		update_post_meta($post_id, 'stm_review_views', 1);
	} else {
		$current_rating = $current_rating + 1;
		update_post_meta($post_id, 'stm_review_views', $current_rating);
	}
}

add_action('wp', 'stm_single_review_counter', 10, 1);

function the_excerpt_max_charlength($charlength) {
    $excerpt = get_the_excerpt(get_the_ID());
    $charlength++;

    if(!empty($excerpt)) {
        if (mb_strlen($excerpt) > $charlength) {
            $subex = mb_substr($excerpt, 0, $charlength - 5);
            $exwords = explode(' ', $subex);
            $excut = -(mb_strlen($exwords[count($exwords) - 1]));
            if ($excut < 0) {
                echo mb_substr($subex, 0, $excut);
            } else {
                echo apply_filters('stm_mr_subex_filter', $subex);
            }

            echo '...';
        } else if (!empty($excerpt)) {
            echo apply_filters('stm_mr_excerpt_filter', $excerpt);
        }
    }
}

function string_max_charlength($str, $charlength) {
    $charlength++;

    $newStr = '';

    if ( mb_strlen( $str ) > $charlength ) {
        $subex = mb_substr( $str, 0, $charlength - 5 );
        $exwords = explode( ' ', $subex );
        $excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
        if ( $excut < 0 ) {
            $newStr .= mb_substr( $subex, 0, $excut );
        } else {
            $newStr .= $subex;
        }

        $newStr .= '...';
    } else if(!empty($str)) {
        $newStr .= $str;
    }

    return $newStr;
}

if (!function_exists('stm_review_post_type')) {
    function stm_review_post_type()
    {
        return apply_filters('stm_review_post_type', 'stm_review');
    }
}


if (!function_exists('stm_review_archive_link')) {
    function stm_review_archive_link()
    {
        return get_post_type_archive_link(stm_review_post_type());;
    }
}