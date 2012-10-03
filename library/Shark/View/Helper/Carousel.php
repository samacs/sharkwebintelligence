<?php
class Shark_View_Helper_Carousel extends Zend_View_Helper_Abstract {

	public function carousel($slides, $type = 'twitter-carousel') {
		switch ($type) {
			case 'twitter-carousel':
			default:
				return $this->_buildTwitterCarousel($slides);
		}
	}

	private function _buildTwitterCarousel($slides, $width = 880, $height = 335) {
		static $id = 1;
		$output = '<div id="carousel-' . $id . '" class="carousel slide">';
		$output .= '<div class="carousel-inner">';
		$i = 0;
		foreach ($slides as $slide) {
			if ($i == 0) {
				$output .= '<div class="item active">';
			} else {
				$output .= '<div class="item">';
			}
			if (isset($slide->link) && $slide->link !== null) {
				$output .= '<a href="' . $slide->link . '" title="' . $slide->title . '">';
				//$output .= '<img src="' . $slide->image . '" alt="' . $slide->title . '" width="' . $width . '" height="' . $height . '">';
				$output .= '<img src="' . $slide->image . '" alt="' . $slide->title . '">';
				$output .= '</a>';
			} else {
				$output .= '<img src="' . $slide->image . '" alt="' . $slide->title . '" width="' . $width . '" height="' . $height . '">';
			}
			if (isset($slide->text) && $slide->text !== null) {
				$output .= '<div class="carousel-caption">';
				if (isset($slide->link) && $slide->link !== null) {
					$output .= '<a href="' . $slide->link . '" title="' . $slide->title . '">';
					$output .= '<h4>' . $slide->title . '</h4>';
					$output .= '<p>' . $slide->text . '</p>';
					$output .= '</a>';
				} else {
					$output .= '<h4>' . $slide->title . '</h4>';
					$output .= '<p>' . $slide->text . '</p>';
				}
				$output .= '</div>';
			}
			$output .= '</div>';
			$i++;
		}
		$output .= '</div>';
		//$output .= '<a href="#carousel-' . $id . '" class="left carousel-control" data-slide="prev">&lsaquo;</a>';
		//$output .= '<a href="#carousel-' . $id . '" class="right carousel-control" data-slide="next">&rsaquo;</a>';
		$output .= '</div>';
		$id++;
		return $output;
	}
}