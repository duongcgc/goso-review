<?php
/*
Plugin Name: Goso Review
Plugin URI: http://gosodesign.com/
Description: Review Shortcode Plugin for Authow theme.
Version: 2.7
Author: GosoDesign
Author URI: http://themeforest.net/user/gosodesign?ref=gosodesign
*/

/**
 * Include files
 */
require_once( dirname( __FILE__ ) . '/inc/functions.php' );
require_once( dirname( __FILE__ ) . '/inc/schema-markup.php' );
require_once( dirname( __FILE__ ) . '/inc/default-options.php' );
require_once( dirname( __FILE__ ) . '/inc/shortcodes.php' );

add_action( 'init', function () {
	if ( class_exists( 'AuthowFW\Customizer\CustomizerOptionAbstract' ) ) {
		require_once( dirname( __FILE__ ) . '/inc/options/section.php' );
		require_once( dirname( __FILE__ ) . '/inc/options/settings.php' );
		\AuthowFW\ReviewCustomizer::getInstance();
	} else {
		require_once( dirname( __FILE__ ) . '/inc/customize.php' );
	}
} );
require_once( dirname( __FILE__ ) . '/inc/helper.php' );
/**
 * Add admin meta box style
 */
function goso_load_admin_metabox_review_style() {
	$screen = get_current_screen();
	if ( $screen->id == 'post' ) {
		wp_enqueue_media();
		wp_enqueue_script( 'jquery-ui-datepicker' );

		wp_enqueue_style( 'goso_meta_box_review_styles', plugin_dir_url( __FILE__ ) . 'css/admin-css.css' );
		wp_enqueue_script( 'goso_meta_box_review', plugin_dir_url( __FILE__ ) . 'js/admin-review.js', array(
			'jquery',
			'jquery-ui-datepicker'
		), '3.0', true );

		wp_localize_script( 'goso_meta_box_review', 'GosoReview', array(
			'WidgetImageTitle'  => esc_html__( 'Select an image', 'goso' ),
			'WidgetImageButton' => esc_html__( 'Insert into widget', 'goso' ),
			'review_title'      => esc_html__( 'Review Title for Point', 'goso' ),
			'review_number'     => esc_html__( 'Review Number for Point', 'goso' ),
			'review_desc'       => esc_html__( 'Minimum is 1, Maximum is 10. Example: 8', 'goso' ),
			'ajaxUrl'           => admin_url( 'admin-ajax.php' ),
			'nonce'             => wp_create_nonce( 'ajax-nonce' ),
		) );
	}
}

add_action( 'admin_enqueue_scripts', 'goso_load_admin_metabox_review_style' );

/* Load Oswald font from selfhosting */
function goso_load_oswald_for_review() {
	if ( get_theme_mod( 'goso_disable_default_fonts' ) && ! get_theme_mod( 'goso_disable_all_fonts' ) ) {
		?>
        <style type="text/css">@font-face {
				font-family: 'Oswald';
				font-style: font-display: swap;
				normal;
				font-weight: 400;
				src: local('Oswald Regular'), local('Oswald-Regular'), url(<?php echo plugin_dir_url( __FILE__ ); ?>fonts/TK3iWkUHHAIjg752HT8Ghe4.woff2) format('woff2');
				unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
			}
			@font-face {
				font-family: 'Oswald';
				font-style: normal;
				font-weight: 400;
				src: local('Oswald Regular'), local('Oswald-Regular'), url(<?php echo plugin_dir_url( __FILE__ ); ?>fonts/TK3iWkUHHAIjg752Fj8Ghe4.woff2) format('woff2');
				unicode-range: U+0102-0103, U+0110-0111, U+1EA0-1EF9, U+20AB;
			}
			@font-face {
				font-family: 'Oswald';
				font-style: normal;
				font-weight: 400;
				src: local('Oswald Regular'), local('Oswald-Regular'), url(<?php echo plugin_dir_url( __FILE__ ); ?>fonts/TK3iWkUHHAIjg752Fz8Ghe4.woff2) format('woff2');
				unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
			}
			@font-face {
				font-family: 'Oswald';
				font-style: normal;
				font-weight: 400;
				src: local('Oswald Regular'), local('Oswald-Regular'), url(<?php echo plugin_dir_url( __FILE__ ); ?>fonts/TK3iWkUHHAIjg752GT8G.woff2) format('woff2');
				unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
			}</style>
		<?php
	}
}

add_action( 'wp_head', 'goso_load_oswald_for_review' );

/**
 * Add javascript for review plugin
 */
add_action( 'wp_enqueue_scripts', 'goso_register_review_scripts' );

function goso_register_review_scripts() {
	wp_enqueue_script( 'jquery-goso-piechart', plugin_dir_url( __FILE__ ) . 'js/jquery.easypiechart.min.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'jquery-goso-review', plugin_dir_url( __FILE__ ) . 'js/review.js', array( 'jquery' ), '1.0', true );
	if ( ! get_theme_mod( 'goso_disable_default_fonts' ) ) {
		wp_enqueue_style( 'goso-oswald', '//fonts.googleapis.com/css?family=Oswald:400&display=swap', array(), false, 'all' );
	}
}

/**
 * Adds Goso review meta box to the post editing screen
 */
function Goso_Review_Add_Custom_Metabox() {
	new Goso_Review_Add_Custom_Metabox_Class();
}

if ( is_admin() ) {
	add_action( 'load-post.php', 'Goso_Review_Add_Custom_Metabox' );
	add_action( 'load-post-new.php', 'Goso_Review_Add_Custom_Metabox' );
}

/**
 * The Class.
 */
class Goso_Review_Add_Custom_Metabox_Class {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box( $post_type ) {
		$post_types = array( 'post' );     //limit meta box to certain post types
		if ( in_array( $post_type, $post_types ) ) {
			add_meta_box(
				'goso_review_meta'
				, esc_html__( 'Add A Review For This Posts', 'authow' )
				, array( $this, 'render_meta_box_content' )
				, $post_type
				, 'advanced'
				, 'default'
			);
		}
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {

		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['goso_review_custom_box_nonce'] ) ) {
			return $post_id;
		}

		$nonce = $_POST['goso_review_custom_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'goso_review_custom_box' ) ) {
			return $post_id;
		}

		// If this is an autosave, our form has not been submitted,
		//     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		// Update the meta field.
		if ( isset( $_POST['goso_review_title'] ) ) {
			update_post_meta( $post_id, 'goso_review_title', $_POST['goso_review_title'] );
		}
		if ( isset( $_POST['goso_review_des'] ) ) {
			update_post_meta( $post_id, 'goso_review_des', $_POST['goso_review_des'] );
		}
		//if( isset( $_POST[ 'goso_review_etype' ] ) ) {
		//update_post_meta( $post_id, 'goso_review_etype', $_POST[ 'goso_review_etype' ] );
		//}
		if ( isset( $_POST['goso_review_1'] ) ) {
			update_post_meta( $post_id, 'goso_review_1', $_POST['goso_review_1'] );
		}
		if ( isset( $_POST['goso_review_1_num'] ) ) {
			update_post_meta( $post_id, 'goso_review_1_num', $_POST['goso_review_1_num'] );
		}
		if ( isset( $_POST['goso_review_2'] ) ) {
			update_post_meta( $post_id, 'goso_review_2', $_POST['goso_review_2'] );
		}
		if ( isset( $_POST['goso_review_2_num'] ) ) {
			update_post_meta( $post_id, 'goso_review_2_num', $_POST['goso_review_2_num'] );
		}
		if ( isset( $_POST['goso_review_3'] ) ) {
			update_post_meta( $post_id, 'goso_review_3', $_POST['goso_review_3'] );
		}
		if ( isset( $_POST['goso_review_3_num'] ) ) {
			update_post_meta( $post_id, 'goso_review_3_num', $_POST['goso_review_3_num'] );
		}
		if ( isset( $_POST['goso_review_4'] ) ) {
			update_post_meta( $post_id, 'goso_review_4', $_POST['goso_review_4'] );
		}
		if ( isset( $_POST['goso_review_4_num'] ) ) {
			update_post_meta( $post_id, 'goso_review_4_num', $_POST['goso_review_4_num'] );
		}
		if ( isset( $_POST['goso_review_5'] ) ) {
			update_post_meta( $post_id, 'goso_review_5', $_POST['goso_review_5'] );
		}
		if ( isset( $_POST['goso_review_5_num'] ) ) {
			update_post_meta( $post_id, 'goso_review_5_num', $_POST['goso_review_5_num'] );
		}
		if ( isset( $_POST['goso_review_6'] ) ) {
			update_post_meta( $post_id, 'goso_review_6', $_POST['goso_review_6'] );
		}
		if ( isset( $_POST['goso_review_6_num'] ) ) {
			update_post_meta( $post_id, 'goso_review_6_num', $_POST['goso_review_6_num'] );
		}
		if ( isset( $_POST['goso_review_7'] ) ) {
			update_post_meta( $post_id, 'goso_review_7', $_POST['goso_review_7'] );
		}
		if ( isset( $_POST['goso_review_7_num'] ) ) {
			update_post_meta( $post_id, 'goso_review_7_num', $_POST['goso_review_7_num'] );
		}
		if ( isset( $_POST['goso_review_8'] ) ) {
			update_post_meta( $post_id, 'goso_review_8', $_POST['goso_review_8'] );
		}
		if ( isset( $_POST['goso_review_8_num'] ) ) {
			update_post_meta( $post_id, 'goso_review_8_num', $_POST['goso_review_8_num'] );
		}
		if ( isset( $_POST['goso_review_9'] ) ) {
			update_post_meta( $post_id, 'goso_review_9', $_POST['goso_review_9'] );
		}
		if ( isset( $_POST['goso_review_9_num'] ) ) {
			update_post_meta( $post_id, 'goso_review_9_num', $_POST['goso_review_9_num'] );
		}
		if ( isset( $_POST['goso_review_10'] ) ) {
			update_post_meta( $post_id, 'goso_review_10', $_POST['goso_review_10'] );
		}
		if ( isset( $_POST['goso_review_10_num'] ) ) {
			update_post_meta( $post_id, 'goso_review_10_num', $_POST['goso_review_10_num'] );
		}

		if ( isset( $_POST['goso_review_good'] ) ) {
			update_post_meta( $post_id, 'goso_review_good', $_POST['goso_review_good'] );
		}
		if ( isset( $_POST['goso_review_bad'] ) ) {
			update_post_meta( $post_id, 'goso_review_bad', $_POST['goso_review_bad'] );
		}

		$review_meta = array(
			'goso_review_ct_image'      => isset( $_POST['goso_review_custom_image'] ) ? $_POST['goso_review_custom_image'] : '',
			'goso_review_address'       => isset( $_POST['goso_review_address'] ) ? $_POST['goso_review_address'] : '',
			'goso_review_phone'         => isset( $_POST['goso_review_phone'] ) ? $_POST['goso_review_phone'] : '',
			'goso_review_website'       => isset( $_POST['goso_review_website'] ) ? $_POST['goso_review_website'] : '',
			'goso_review_price'         => isset( $_POST['goso_review_price'] ) ? $_POST['goso_review_price'] : '',
			'goso_review_linkbuy'       => isset( $_POST['goso_review_linkbuy'] ) ? $_POST['goso_review_linkbuy'] : '',
			'goso_review_textbuy'       => isset( $_POST['goso_review_textbuy'] ) ? $_POST['goso_review_textbuy'] : '',
			'goso_review_schema_markup' => isset( $_POST['goso_review_schema_markup'] ) ? $_POST['goso_review_schema_markup'] : 'thing',
			'goso_review_img_size'      => isset( $_POST['goso_review_img_size'] ) ? $_POST['goso_review_img_size'] : 'thumbnail',

			'goso_rv_dis_point'         => isset( $_POST['goso_rv_dis_point'] ) ? $_POST['goso_rv_dis_point'] : '',
			'goso_rv_dis_goodbad'       => isset( $_POST['goso_rv_dis_goodbad'] ) ? $_POST['goso_rv_dis_goodbad'] : '',
			'goso_rv_dis_desc'          => isset( $_POST['goso_rv_dis_desc'] ) ? $_POST['goso_rv_dis_desc'] : '',
			'goso_rv_enable_sim_author' => isset( $_POST['goso_rv_enable_sim_author'] ) ? $_POST['goso_rv_enable_sim_author'] : '',
			'goso_rv_hide_featured_img' => isset( $_POST['goso_rv_hide_featured_img'] ) ? $_POST['goso_rv_hide_featured_img'] : '',
			'goso_rv_hide_schema'       => isset( $_POST['goso_rv_hide_schema'] ) ? $_POST['goso_rv_hide_schema'] : '',
		);

		update_post_meta( $post_id, 'goso_review_meta', $review_meta );

		if ( isset( $_POST['goso_review_schema_options'] ) ) {
			update_post_meta( $post_id, 'goso_review_schema_options', $_POST['goso_review_schema_options'] );
		}

	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'goso_review_custom_box', 'goso_review_custom_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$review_title = get_post_meta( $post->ID, 'goso_review_title', true );
		$review_des   = get_post_meta( $post->ID, 'goso_review_des', true );
		//$review_type = get_post_meta( $post->ID, 'goso_review_etype', true );
		$review_1     = get_post_meta( $post->ID, 'goso_review_1', true );
		$review_1num  = get_post_meta( $post->ID, 'goso_review_1_num', true );
		$review_2     = get_post_meta( $post->ID, 'goso_review_2', true );
		$review_2num  = get_post_meta( $post->ID, 'goso_review_2_num', true );
		$review_3     = get_post_meta( $post->ID, 'goso_review_3', true );
		$review_3num  = get_post_meta( $post->ID, 'goso_review_3_num', true );
		$review_4     = get_post_meta( $post->ID, 'goso_review_4', true );
		$review_4num  = get_post_meta( $post->ID, 'goso_review_4_num', true );
		$review_5     = get_post_meta( $post->ID, 'goso_review_5', true );
		$review_5num  = get_post_meta( $post->ID, 'goso_review_5_num', true );
		$review_6     = get_post_meta( $post->ID, 'goso_review_6', true );
		$review_6num  = get_post_meta( $post->ID, 'goso_review_6_num', true );
		$review_7     = get_post_meta( $post->ID, 'goso_review_7', true );
		$review_7num  = get_post_meta( $post->ID, 'goso_review_7_num', true );
		$review_8     = get_post_meta( $post->ID, 'goso_review_8', true );
		$review_8num  = get_post_meta( $post->ID, 'goso_review_8_num', true );
		$review_9     = get_post_meta( $post->ID, 'goso_review_9', true );
		$review_9num  = get_post_meta( $post->ID, 'goso_review_9_num', true );
		$review_10    = get_post_meta( $post->ID, 'goso_review_10', true );
		$review_10num = get_post_meta( $post->ID, 'goso_review_10_num', true );

		$review_good = get_post_meta( $post->ID, 'goso_review_good', true );
		$review_bad  = get_post_meta( $post->ID, 'goso_review_bad', true );

		$review_meta     = get_post_meta( $post->ID, 'goso_review_meta', true );
		$review_address  = isset( $review_meta['goso_review_address'] ) ? $review_meta['goso_review_address'] : '';
		$review_phone    = isset( $review_meta['goso_review_phone'] ) ? $review_meta['goso_review_phone'] : '';
		$review_website  = isset( $review_meta['goso_review_website'] ) ? $review_meta['goso_review_website'] : '';
		$review_price    = isset( $review_meta['goso_review_price'] ) ? $review_meta['goso_review_price'] : '';
		$review_linkbuy  = isset( $review_meta['goso_review_linkbuy'] ) ? $review_meta['goso_review_linkbuy'] : '';
		$review_textbuy  = isset( $review_meta['goso_review_textbuy'] ) ? $review_meta['goso_review_textbuy'] : '';
		$schema_value    = isset( $review_meta['goso_review_schema_markup'] ) ? $review_meta['goso_review_schema_markup'] : 'thing';
		$review_img_size = isset( $review_meta['goso_review_img_size'] ) ? $review_meta['goso_review_img_size'] : '';

		$review_ct_image     = isset( $review_meta['goso_review_ct_image'] ) ? $review_meta['goso_review_ct_image'] : '';
		$url_review_ct_image = wp_get_attachment_thumb_url( $review_ct_image );

		// Display the form, using the current value.
		?>

        <div class="goso-table-meta">
            <h3>Review settings</h3>
            <p>You can display your review for this post by using the following shortcode: <span
                        class="goso-review-shortcode">[goso_review]</span><br>If you do not need this feature, you
                should go to <strong>Plugins > Installed Plugins > and deactivate plugin "Goso Review"</strong></p>
            <p>
                <label for="goso_review_title" class="goso-format-row">Review Title:</label>
                <input style="width:100%;" type="text" name="goso_review_title" id="goso_review_title"
                       value="<?php if ( isset( $review_title ) ): echo $review_title; endif; ?>">
            </p>

            <div class="goso-grid-2">
                <p>
                    <label for="goso_review_title"
                           class="goso-format-row"><?php esc_html_e( 'Adress', 'goso' ); ?></label>
                    <input style="width:100%;" type="text" name="goso_review_address" id="goso_review_address"
                           value="<?php echo esc_attr( $review_address ); ?>">
                </p>
                <p>
                    <label for="goso_review_title"
                           class="goso-format-row"><?php esc_html_e( 'Phone', 'goso' ); ?></label>
                    <input style="width:100%;" type="text" name="goso_review_phone" id="goso_review_phone"
                           value="<?php echo esc_attr( $review_phone ); ?>">
                </p>
                <p>
                    <label for="goso_review_title"
                           class="goso-format-row"><?php esc_html_e( 'Website', 'goso' ); ?></label>
                    <input style="width:100%;" type="text" name="goso_review_website" id="goso_review_website"
                           value="<?php echo esc_attr( $review_website ); ?>">
                </p>
                <p>
                    <label for="goso_review_title"
                           class="goso-format-row"><?php esc_html_e( 'Product Price', 'goso' ); ?></label>
                    <input style="width:100%;" type="text" name="goso_review_price" id="goso_review_price"
                           value="<?php echo esc_attr( $review_price ); ?>">
                </p>
                <p>
                    <label for="goso_review_title"
                           class="goso-format-row"><?php esc_html_e( 'Link for Buy', 'goso' ); ?></label>
                    <input style="width:100%;" type="text" name="goso_review_linkbuy" id="goso_review_linkbuy"
                           value="<?php echo esc_attr( $review_linkbuy ); ?>">
                </p>
                <p>
                    <label for="goso_review_title"
                           class="goso-format-row"><?php esc_html_e( 'Custom "Buy Now" Text', 'goso' ); ?></label>
                    <input style="width:100%;" type="text" name="goso_review_textbuy" id="goso_review_textbuy"
                           value="<?php echo esc_attr( $review_textbuy ); ?>">
                </p>

            </div>
            <div>
                <label for="goso_review_type"
                       class="goso-format-row"><?php esc_html_e( 'Custom Image for Reviews Box', 'goso' ); ?>:</label>
                <div class="goso-widget-image media-widget-control" style="max-width: 150px;">
                    <input name="goso_review_custom_image" type="hidden" class="goso-widget-image__input"
                           value="<?php echo esc_attr( $review_ct_image ); ?>">
                    <img src="<?php echo esc_url( $url_review_ct_image ); ?>"
                         class="goso-widget-image__image<?php echo $review_ct_image ? '' : ' hidden'; ?>">
                    <div class="placeholder <?php echo( $url_review_ct_image ? 'hidden' : '' ); ?>"><?php _e( 'No image selected' ); ?></div>
                    <button class="button goso-widget-image__select_review"><?php esc_html_e( 'Select' ); ?></button>
                    <button class="button goso-widget-image__remove"><?php esc_html_e( 'Remove' ); ?></button>
                </div>
            </div>
            <div class="goso-grid-2" style="clear:both;">
                <p>
                    <label for="goso_review_img_size"
                           class="goso-format-row"><?php esc_html_e( 'Image size', 'goso' ); ?></label>
                    <input style="width:100%;" type="text" name="goso_review_img_size" id="goso_review_img_size"
                           value="<?php echo esc_attr( $review_img_size ); ?>">
                    <span class="goso-recipe-description">Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme).</span>
                </p>
            </div>
			<?php $list_schema = Goso_Review_Schema_Markup::get_list_schema(); ?>
            <p>
                <label for="goso_review_schema_markup"
                       class="goso-format-row goso-format-row2"><?php esc_html_e( 'Reviewed Item Schema', 'goso' ); ?></label>
                <select name="goso_review_schema_markup" id="goso_review_schema_markup">
					<?php
					foreach ( $list_schema as $schema_type => $schema_label ) {
						echo '<option value="' . $schema_type . '" ' . selected( $schema_value, $schema_type, false ) . '>' . $schema_label . '</option>';
					}
					?>
                </select>
            </p>
            <div id="goso_review_schema_options">
				<?php
				foreach ( $list_schema as $schema_type => $schema_label ) {
					if ( 'Thing' == $schema_type || 'none' == $schema_type ) {
						continue;
					}

					Goso_Review_Schema_Markup::get_schema_filed( $schema_type, $schema_value, $post->ID );
				}
				?>
            </div>
            <p>
                <label for="goso_review_des" class="goso-format-row">Description:</label>
                <textarea style="width:100%; height:120px;" name="goso_review_des"
                          id="goso_review_des"><?php if ( isset( $review_des ) ): echo $review_des; endif; ?></textarea>
                <span class="goso-recipe-description">You can write some description for your review here.</span>
            </p>
            <div class="goso-review-points" style="display: table; width: 100%;">
                <div class="goso-col-6 goso-review-left">
                    <p class="review-odd">
                        <label for="goso_review_1" class="goso-format-row">Review Title for Point 1:</label>
                        <input style="width:100px;" type="text" name="goso_review_1" id="goso_review_1"
                               value="<?php if ( isset( $review_1 ) ): echo $review_1; endif; ?>">
                        <span class="goso-recipe-description">Example: Design</span>
                    </p>
                    <p>
                        <label for="goso_review_1_num" class="goso-format-row">Review Number for Point 1:</label>
                        <input style="width:100px;" type="number" name="goso_review_1_num" id="goso_review_1_num"
                               value="<?php if ( isset( $review_1num ) ): echo $review_1num; endif; ?>">
                        <span class="goso-recipe-description">Minimum is 1, Maximum is 10. Example: 8</span>
                    </p>
                    <p class="review-odd">
                        <label for="goso_review_2" class="goso-format-row">Review Title for Point 2:</label>
                        <input style="width:100px;" type="text" name="goso_review_2" id="goso_review_2"
                               value="<?php if ( isset( $review_2 ) ): echo $review_2; endif; ?>">
                    </p>
                    <p>
                        <label for="goso_review_2_num" class="goso-format-row">Review Number for Point 2:</label>
                        <input style="width:100px;" type="number" name="goso_review_2_num" id="goso_review_2_num"
                               value="<?php if ( isset( $review_2num ) ): echo $review_2num; endif; ?>">
                        <span class="goso-recipe-description">Minimum is 1, Maximum is 10. Example: 8</span>
                    </p>
                    <p class="review-odd">
                        <label for="goso_review_3" class="goso-format-row">Review Title for Point 3:</label>
                        <input style="width:100px;" type="text" name="goso_review_3" id="goso_review_3"
                               value="<?php if ( isset( $review_3 ) ): echo $review_3; endif; ?>">
                    </p>
                    <p>
                        <label for="goso_review_3_num" class="goso-format-row">Review Number for Point 3:</label>
                        <input style="width:100px;" type="number" name="goso_review_3_num" id="goso_review_3_num"
                               value="<?php if ( isset( $review_3num ) ): echo $review_3num; endif; ?>">
                        <span class="goso-recipe-description">Minimum is 1, Maximum is 10. Example: 8</span>
                    </p>
                    <p class="review-odd">
                        <label for="goso_review_4" class="goso-format-row">Review Title for Point 4:</label>
                        <input style="width:100px;" type="text" name="goso_review_4" id="goso_review_4"
                               value="<?php if ( isset( $review_4 ) ): echo $review_4; endif; ?>">
                    </p>
                    <p>
                        <label for="goso_review_4_num" class="goso-format-row">Review Number for Point 4:</label>
                        <input style="width:100px;" type="number" name="goso_review_4_num" id="goso_review_4_num"
                               value="<?php if ( isset( $review_4num ) ): echo $review_4num; endif; ?>">
                        <span class="goso-recipe-description">Minimum is 1, Maximum is 10. Example: 8</span>
                    </p>
                    <p class="review-odd">
                        <label for="goso_review_5" class="goso-format-row">Review Title for Point 5:</label>
                        <input style="width:100px;" type="text" name="goso_review_5" id="goso_review_5"
                               value="<?php if ( isset( $review_5 ) ): echo $review_5; endif; ?>">
                    </p>
                    <p>
                        <label for="goso_review_5_num" class="goso-format-row">Review Number for Point 5:</label>
                        <input style="width:100px;" type="number" name="goso_review_5_num" id="goso_review_5_num"
                               value="<?php if ( isset( $review_5num ) ): echo $review_5num; endif; ?>">
                        <span class="goso-recipe-description">Minimum is 1, Maximum is 10. Example: 8</span>
                    </p>
                </div>
                <div class="goso-col-6 goso-review-right">
                    <p class="review-odd">
                        <label for="goso_review_6" class="goso-format-row">Review Title for Point 6:</label>
                        <input style="width:100px;" type="text" name="goso_review_6" id="goso_review_6"
                               value="<?php if ( isset( $review_6 ) ): echo $review_6; endif; ?>">
                        <span class="goso-recipe-description">Example: Design</span>
                    </p>
                    <p>
                        <label for="goso_review_6_num" class="goso-format-row">Review Number for Point 6:</label>
                        <input style="width:100px;" type="number" name="goso_review_6_num" id="goso_review_6_num"
                               value="<?php if ( isset( $review_6num ) ): echo $review_6num; endif; ?>">
                        <span class="goso-recipe-description">Minimum is 1, Maximum is 10. Example: 8</span>
                    </p>
                    <p class="review-odd">
                        <label for="goso_review_7" class="goso-format-row">Review Title for Point 7:</label>
                        <input style="width:100px;" type="text" name="goso_review_7" id="goso_review_7"
                               value="<?php if ( isset( $review_7 ) ): echo $review_7; endif; ?>">
                    </p>
                    <p>
                        <label for="goso_review_7_num" class="goso-format-row">Review Number for Point 7:</label>
                        <input style="width:100px;" type="number" name="goso_review_7_num" id="goso_review_7_num"
                               value="<?php if ( isset( $review_7num ) ): echo $review_7num; endif; ?>">
                        <span class="goso-recipe-description">Minimum is 1, Maximum is 10. Example: 8</span>
                    </p>
                    <p class="review-odd">
                        <label for="goso_review_8" class="goso-format-row">Review Title for Point 8:</label>
                        <input style="width:100px;" type="text" name="goso_review_8" id="goso_review_8"
                               value="<?php if ( isset( $review_8 ) ): echo $review_8; endif; ?>">
                    </p>
                    <p>
                        <label for="goso_review_8_num" class="goso-format-row">Review Number for Point 8:</label>
                        <input style="width:100px;" type="number" name="goso_review_8_num" id="goso_review_8_num"
                               value="<?php if ( isset( $review_8num ) ): echo $review_8num; endif; ?>">
                        <span class="goso-recipe-description">Minimum is 1, Maximum is 10. Example: 8</span>
                    </p>
                    <p class="review-odd">
                        <label for="goso_review_9" class="goso-format-row">Review Title for Point 9:</label>
                        <input style="width:100px;" type="text" name="goso_review_9" id="goso_review_9"
                               value="<?php if ( isset( $review_9 ) ): echo $review_9; endif; ?>">
                    </p>
                    <p>
                        <label for="goso_review_9_num" class="goso-format-row">Review Number for Point 9:</label>
                        <input style="width:100px;" type="number" name="goso_review_9_num" id="goso_review_9_num"
                               value="<?php if ( isset( $review_9num ) ): echo $review_9num; endif; ?>">
                        <span class="goso-recipe-description">Minimum is 1, Maximum is 10. Example: 8</span>
                    </p>
                    <p class="review-odd">
                        <label for="goso_review_10" class="goso-format-row">Review Title for Point 10:</label>
                        <input style="width:100px;" type="text" name="goso_review_10" id="goso_review_10"
                               value="<?php if ( isset( $review_10 ) ): echo $review_10; endif; ?>">
                    </p>
                    <p>
                        <label for="goso_review_10_num" class="goso-format-row">Review Number for Point 10:</label>
                        <input style="width:100px;" type="number" name="goso_review_10_num" id="goso_review_10_num"
                               value="<?php if ( isset( $review_10num ) ): echo $review_10num; endif; ?>">
                        <span class="goso-recipe-description">Minimum is 1, Maximum is 10. Example: 8</span>
                    </p>
                </div>
            </div>
            <p>
                <label for="goso_review_good" class="goso-format-row">The Goods:</label>
                <textarea style="width:100%; height:120px;" name="goso_review_good"
                          id="goso_review_good"><?php if ( isset( $review_good ) ): echo $review_good; endif; ?></textarea>
                <span class="goso-recipe-description">Type each the good on a new line.</span>
            </p>
            <p>
                <label for="goso_review_bad" class="goso-format-row">The Bads:</label>
                <textarea style="width:100%; height:120px;" name="goso_review_bad"
                          id="goso_review_bad"><?php if ( isset( $review_bad ) ): echo $review_bad; endif; ?></textarea>
                <span class="goso-recipe-description">Type each the bad on a new line.</span>
            </p>
        </div>
		<?php
		$list_checkbox = array(
			'goso_rv_hide_featured_img' => esc_html__( 'Featured Image on Reviews Box', 'goso' ),
			'goso_rv_hide_schema'       => esc_html__( 'Reviewed Schema Info', 'goso' ),
		);

		foreach ( $list_checkbox as $id => $title ) {
			$checkbox_value = isset( $review_meta[ $id ] ) ? $review_meta[ $id ] : '';
			?>
            <p>
                <label for="<?php echo esc_attr( $id ); ?>"
                       class="goso-format-row goso-format-row2"><?php echo $title; ?></label>
                <select name="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $id ); ?>">
                    <option value="" <?php selected( $checkbox_value, '' ) ?>><?php esc_html_e( 'Default' ) ?></option>
                    <option value="enable" <?php selected( $checkbox_value, 'enable' ) ?>><?php esc_html_e( 'Show' ) ?></option>
                    <option value="disable" <?php selected( $checkbox_value, 'disable' ) ?>><?php esc_html_e( 'Hide' ) ?></option>
                </select>
            </p>
			<?php
		}
	}
}
