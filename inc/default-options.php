<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if( ! function_exists( 'goso_review_tran_default_setting' ) ):
	function goso_review_tran_default_setting( $name ) {
		$defaults = array(
			'goso_review_price_text'         => esc_html__( 'Price:', 'goso' ),

			'goso_review_text_thing'         => esc_html__( 'Thing', 'goso' ),
			'goso_review_text_none'          => esc_html__( 'None', 'goso' ),
			'goso_review_text_book'          => esc_html__( 'Book', 'goso' ),
			'goso_review_text_game'          => esc_html__( 'Game', 'goso' ),
			'goso_review_text_movie'         => esc_html__( 'Movie', 'goso' ),
			'goso_review_text_musicreco'     => esc_html__( 'MusicRecording', 'goso' ),
			'goso_review_text_painting'      => esc_html__( 'Painting', 'goso' ),
			'goso_review_text_place'         => esc_html__( 'Place', 'goso' ),
			'goso_review_text_product'       => esc_html__( 'Product', 'goso' ),
			'goso_review_text_restaurant'    => esc_html__( 'Restaurant', 'goso' ),
			'goso_review_text_sfapp'         => esc_html__( 'SoftwareApplication', 'goso' ),
			'goso_review_text_store'         => esc_html__( 'Store', 'goso' ),
			'goso_review_text_tvseries'      => esc_html__( 'TVSeries', 'goso' ),
			'goso_review_text_webSite'       => esc_html__( 'WebSite', 'goso' ),
			'goso_review_text_course'        => esc_html__( 'Course', 'goso' ),
			'goso_review_text_event'         => esc_html__( 'Event', 'goso' ),
			'goso_review_text_howto'         => esc_html__( 'How to', 'goso' ),

			// Booking
			'goso_reviewt_btitle'            => esc_html__( 'Book Title', 'goso' ),
			'goso_reviewt_burl'              => esc_html__( 'URL', 'goso' ),
			'goso_reviewt_bauthor'           => esc_html__( 'Book Author', 'goso' ),
			'goso_reviewt_bedition'          => esc_html__( 'Book Edition', 'goso' ),
			'goso_reviewt_bformat'           => esc_html__( 'Book Format', 'goso' ),
			'goso_reviewt_bdate'             => esc_html__( 'Date published', 'goso' ),
			'goso_reviewt_billustrator'      => esc_html__( 'Illustrator', 'goso' ),
			'goso_reviewt_bISBN'             => esc_html__( 'ISBN', 'goso' ),
			'goso_reviewt_bnumberofpage'     => esc_html__( 'Number Of Pages', 'goso' ),
			'goso_reviewt_bdesc'             => esc_html__( 'Book Description', 'goso' ),

			// Game
			'goso_reviewt_game_title'        => esc_html__( 'Game title', 'goso' ),
			'goso_reviewt_game_url'          => esc_html__( 'URL', 'goso' ),
			'goso_reviewt_game_desc'         => esc_html__( 'Game description', 'goso' ),

			// Movie
			'goso_reviewt_mv_title'          => esc_html__( 'Movie title', 'goso' ),
			'goso_reviewt_mv_url'            => esc_html__( 'URL', 'goso' ),
			'goso_reviewt_mv_date'           => esc_html__( 'Date published', 'goso' ),
			'goso_reviewt_mv_desc'           => esc_html__( 'Movie description', 'goso' ),
			'goso_reviewt_mv_dir'            => esc_html__( 'Director(s)', 'goso' ),
			'goso_reviewt_mv_actor'          => esc_html__( 'Actor(s)', 'goso' ),
			'goso_reviewt_mv_genre'          => esc_html__( 'Genre', 'goso' ),

			// Music recording
			'goso_reviewt_music_name'        => esc_html__( 'Track name', 'goso' ),
			'goso_reviewt_music_url'         => esc_html__( 'URL', 'goso' ),
			'goso_reviewt_music_author'      => esc_html__( 'Author', 'goso' ),
			'goso_reviewt_music_dur'         => esc_html__( 'Track Duration', 'goso' ),
			'goso_reviewt_music_album'       => esc_html__( 'Album name', 'goso' ),
			'goso_reviewt_music_genre'       => esc_html__( 'Genre', 'goso' ),

			// Painting
			'goso_reviewt_painting_name'     => esc_html__( 'Name', 'goso' ),
			'goso_reviewt_painting_author'   => esc_html__( 'Author', 'goso' ),
			'goso_reviewt_painting_url'      => esc_html__( 'URL', 'goso' ),
			'goso_reviewt_painting_date_pub' => esc_html__( 'Date published', 'goso' ),
			'goso_reviewt_painting_genre'    => esc_html__( 'Genre', 'goso' ),

			// Place
			'goso_reviewt_place_name'        => esc_html__( 'Place Name', 'goso' ),
			'goso_reviewt_place_url'         => esc_html__( 'URL', 'goso' ),
			'goso_reviewt_place_desc'        => esc_html__( 'Place Description', 'goso' ),

			// Product
			'goso_reviewt_prod_name'         => esc_html__( 'Product Name', 'goso' ),
			'goso_reviewt_prod_url'          => esc_html__( 'URL', 'goso' ),
			'goso_reviewt_prod_price'        => esc_html__( 'Price', 'goso' ),
			'goso_reviewt_prod_currency'     => esc_html__( 'Currency', 'goso' ),
			'goso_reviewt_prod_avai'         => esc_html__( 'Availability', 'goso' ),
			'goso_reviewt_prod_band'         => esc_html__( 'Brand', 'goso' ),
			'goso_reviewt_prod_suk'          => esc_html__( 'SKU', 'goso' ),
			'goso_reviewt_prod_desc'         => esc_html__( 'Product Description', 'goso' ),
			'goso_reviewt_prod_pricevali'    => esc_html__( 'Price Valid Until', 'goso' ),
			'goso_reviewt_prod_mpn'          => esc_html__( 'Product MPN', 'goso' ),

			// Restaurant
			'goso_reviewt_restau_name'       => esc_html__( 'Restaurant Name', 'goso' ),
			'goso_reviewt_restau_url'        => esc_html__( 'URL', 'goso' ),
			'goso_reviewt_restau_address'    => esc_html__( 'Address', 'goso' ),
			'goso_reviewt_restau_price'      => esc_html__( 'Price range', 'goso' ),
			'goso_reviewt_restau_telephone'  => esc_html__( 'Telephone', 'goso' ),
			'goso_reviewt_restau_serves'     => esc_html__( 'Serves cuisine', 'goso' ),
			'goso_reviewt_restau_ophours'    => esc_html__( 'Opening hours', 'goso' ),
			'goso_reviewt_restau_desc'       => esc_html__( 'Restaurant Description', 'goso' ),

			// Software application
			'goso_reviewt_app_name'          => esc_html__( 'Name', 'goso' ),
			'goso_reviewt_app_url'           => esc_html__( 'URL', 'goso' ),
			'goso_reviewt_app_price'         => esc_html__( 'Price', 'goso' ),
			'goso_reviewt_app_currency'      => esc_html__( 'Currency', 'goso' ),
			'goso_reviewt_app_opsystem'      => esc_html__( 'Operating System', 'goso' ),
			'goso_reviewt_app_app_cat'       => esc_html__( 'Application Category', 'goso' ),
			'goso_reviewt_app_desc'          => esc_html__( 'Description', 'goso' ),

			// Store
			'goso_reviewt_store_name'        => esc_html__( 'Store Name', 'goso' ),
			'goso_reviewt_store_url'         => esc_html__( 'URL', 'goso' ),
			'goso_reviewt_store_address'     => esc_html__( 'Address', 'goso' ),
			'goso_reviewt_store_price'       => esc_html__( 'Price range', 'goso' ),
			'goso_reviewt_store_telephone'   => esc_html__( 'Telephone', 'goso' ),
			'goso_reviewt_store_desc'        => esc_html__( 'Store Description', 'goso' ),

			// TVSeries
			'goso_reviewt_tv_name'           => esc_html__( 'Name', 'goso' ),
			'goso_reviewt_tv_url'            => esc_html__( 'URL', 'goso' ),
			'goso_reviewt_tv_desc'           => esc_html__( 'Description', 'goso' ),

			// WebSite
			'goso_reviewt_web_name'          => esc_html__( 'Name', 'goso' ),
			'goso_reviewt_web_url'           => esc_html__( 'URL', 'goso' ),
			'goso_reviewt_web_desc'          => esc_html__( 'Description', 'goso' ),

			// Course
			'goso_reviewt_coursetitle' => esc_html__( 'Name', 'goso' ),
			'goso_reviewt_coursedesc'  => esc_html__( 'Description', 'goso' ),

			// Event
			'goso_reviewt_eventtitle'     => esc_html__( 'Name', 'goso' ),
			'goso_reviewt_eventdesc'      => esc_html__( 'Description', 'goso' ),
			'goso_reviewt_eventurl'       => esc_html__( 'Url', 'goso' ),
			'goso_reviewt_eventsdate'     => esc_html__( 'startDate', 'goso' ),
			'goso_reviewt_eventedate'     => esc_html__( 'endDate', 'goso' ),
			'goso_reviewt_eventlname'     => esc_html__( 'Location Name', 'goso' ),
			'goso_reviewt_eventladdress'  => esc_html__( 'Location Address', 'goso' ),
			'goso_reviewt_eventprice'     => esc_html__( 'Price', 'goso' ),
			'goso_reviewt_eventpricec'    => esc_html__( 'Price Currency', 'goso' ),
			'goso_reviewt_eventvalidFrom' => esc_html__( 'Url', 'goso' ),
			'goso_reviewt_eventvalidFrom' => esc_html__( 'Valid From Date', 'goso' ),

			// How to
		);


		return isset( $defaults[ $name ] ) ? $defaults[ $name ] : '';
	}
endif;

/**
 * Get theme settings.
 *
 * @param string $name
 *
 * @return mixed
 */
if ( ! function_exists( 'goso_review_tran_setting' ) ):
	function goso_review_tran_setting( $name ) {
		$value = get_theme_mod( $name, goso_review_tran_default_setting( $name ) );

		return do_shortcode( $value );
	}
endif;