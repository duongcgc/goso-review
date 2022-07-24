<?php
/**
 * Callback function for sanitizing checkbox settings.
 * Use for GosoDesign
 *
 * @param $input
 *
 * @return string default value if type is not exists
 */
function goso_review_sanitize_checkbox_field( $input ) {
	if ( $input == 1 ) {
		return true;
	}
	else {
		return false;
	}
}

/**
 * Customize colors
 * @since 3.0
 */
function goso_review_customizer_css() {
	?>
	<style type="text/css">
		<?php if(get_theme_mod( 'goso_review_border_color' )) : ?>.wrapper-goso-review { border-color:<?php echo get_theme_mod( 'goso_review_border_color' ); ?>; }<?php endif; ?>
		<?php if(get_theme_mod( 'goso_review_title_color' )) : ?>.goso-review-container.goso-review-count h4 { color:<?php echo get_theme_mod( 'goso_review_title_color' ); ?>; }<?php endif; ?>
		<?php if(get_theme_mod( 'goso_review_desc_color' )) : ?>.post-entry .goso-review-desc p { color:<?php echo get_theme_mod( 'goso_review_desc_color' ); ?>; }<?php endif; ?>
		<?php if(get_theme_mod( 'goso_review_point_title_color' )) : ?>.goso-review-text { color:<?php echo get_theme_mod( 'goso_review_point_title_color' ); ?>; }<?php endif; ?>
		<?php if(get_theme_mod( 'goso_review_process_main_color' )) : ?>.goso-review-process { background-color:<?php echo get_theme_mod( 'goso_review_process_main_color' ); ?>; }<?php endif; ?>
		<?php if(get_theme_mod( 'goso_review_process_run_color' )) : ?>.goso-review .goso-review-process span { background-color:<?php echo get_theme_mod( 'goso_review_process_run_color' ); ?>; }<?php endif; ?>
		<?php if(get_theme_mod( 'goso_review_title_good_color' )) : ?>.goso-review-stuff .goso-review-good h5 { color:<?php echo get_theme_mod( 'goso_review_title_good_color' ); ?>; }<?php endif; ?>
		<?php if(get_theme_mod( 'goso_review_good_icon' )) : ?>.goso-review .goso-review-good ul li:before { color:<?php echo get_theme_mod( 'goso_review_good_icon' ); ?>; }<?php endif; ?>
		<?php if(get_theme_mod( 'goso_review_bad_icon' )) : ?>.goso-review .goso-review-bad ul li:before { color:<?php echo get_theme_mod( 'goso_review_bad_icon' ); ?>; }<?php endif; ?>
		<?php if(get_theme_mod( 'goso_review_average_total_bg' )) : ?>.goso-review .goso-review-score-total { background-color:<?php echo get_theme_mod( 'goso_review_average_total_bg' ); ?>; }<?php endif; ?>
		<?php if(get_theme_mod( 'goso_review_average_total_color' )) : ?>.goso-review-score-num { color:<?php echo get_theme_mod( 'goso_review_average_total_color' ); ?>; }<?php endif; ?>
		<?php if(get_theme_mod( 'goso_review_average_text_color' )) : ?>.goso-review-score-total span { color:<?php echo get_theme_mod( 'goso_review_average_text_color' ); ?>; }<?php endif; ?>
		<?php if(get_theme_mod( 'goso_review_piechart_text' )) : ?>.goso-chart-text { color:<?php echo get_theme_mod( 'goso_review_piechart_text' ); ?>; }<?php endif; ?>
	</style>
	<?php
}
add_action( 'wp_head', 'goso_review_customizer_css' );
