<?php
/**
 * @author : GosoDesign
 */

namespace AuthowFW\Customizer;

/**
 * Class Theme Authow Customizer
 */
class ReviewOption extends CustomizerOptionAbstract {

	public $panelID = '';

	public function set_option() {
		$this->set_section();
	}

	public function set_section() {
		$this->add_lazy_section( 'goso_new_section_review_section', esc_html__( 'Posts Review Options', 'authow' ), $this->panelID );
	}
}
