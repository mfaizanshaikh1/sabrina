<?php

function stm_theme_import_sliders($layout)
{
	if (class_exists('RevSlider')) {
		$path = STM_CONFIGURATIONS_PATH . '/demos/' . $layout . '/sliders/';
		$slider_path = $path . 'home_slider.zip';
		if (file_exists($slider_path)) {
			$slider = new RevSlider();
			$slider->importSliderFromPost(true, true, $slider_path);
		}

		$slider_2_path = $path . 'home_slider_2.zip';
		if (file_exists($slider_2_path)) {
			$slider = new RevSlider();
			$slider->importSliderFromPost(true, true, $slider_2_path);
		}
	}
}