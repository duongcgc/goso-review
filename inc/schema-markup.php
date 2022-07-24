<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Goso_Review_Schema_Markup' ) ):
	class Goso_Review_Schema_Markup {

		public function __construct() {
			add_action( 'goso_amp_post_template_head', array( __CLASS__, 'amp_json_schema' ) );
			add_action( 'wp_head', array( __CLASS__, 'amp_json_schema' ) );
		}
		public static function amp_json_schema(){
			global $post;
			if ( is_singular() && has_shortcode( $post->post_content, 'goso_review') ) {
				$review_id = get_the_ID();
				if ( ! empty( $id ) && is_numeric( $id ) ) {
					$review_id = $id;
				}

				// Get review meta
				//$review_type = get_theme_mod('goso_review_type') ? get_theme_mod('goso_review_type') : 'Product';
				//$review_edit = get_post_meta( $review_id, 'goso_review_etype', true );
				//if( $review_edit ) { $review_type = $review_edit; }
				$review_title = get_post_meta( $review_id, 'goso_review_title', true );
				$review_des = get_post_meta( $review_id, 'goso_review_des', true );
				$review_1 = get_post_meta( $review_id, 'goso_review_1', true );
				$review_1num = get_post_meta( $review_id, 'goso_review_1_num', true );
				$review_2 = get_post_meta( $review_id, 'goso_review_2', true );
				$review_2num = get_post_meta( $review_id, 'goso_review_2_num', true );
				$review_3 = get_post_meta( $review_id, 'goso_review_3', true );
				$review_3num = get_post_meta( $review_id, 'goso_review_3_num', true );
				$review_4 = get_post_meta( $review_id, 'goso_review_4', true );
				$review_4num = get_post_meta( $review_id, 'goso_review_4_num', true );
				$review_5 = get_post_meta( $review_id, 'goso_review_5', true );
				$review_5num = get_post_meta( $review_id, 'goso_review_5_num', true );
				$review_6 = get_post_meta( $review_id, 'goso_review_6', true );
				$review_6num = get_post_meta( $review_id, 'goso_review_6_num', true );
				$review_7 = get_post_meta( $review_id, 'goso_review_7', true );
				$review_7num = get_post_meta( $review_id, 'goso_review_7_num', true );
				$review_8 = get_post_meta( $review_id, 'goso_review_8', true );
				$review_8num = get_post_meta( $review_id, 'goso_review_8_num', true );
				$review_9 = get_post_meta( $review_id, 'goso_review_9', true );
				$review_9num = get_post_meta( $review_id, 'goso_review_9_num', true );
				$review_10 = get_post_meta( $review_id, 'goso_review_10', true );
				$review_10num = get_post_meta( $review_id, 'goso_review_10_num', true );
				$review_good = get_post_meta( $review_id, 'goso_review_good', true );
				$review_bad = get_post_meta( $review_id, 'goso_review_bad', true );

				// Turn review good and bad into an array
				$review_good_array = '';
				$review_bad_array = '';
				if( $review_good ):
					$review_good_array = preg_split( '/\r\n|[\r\n]/', $review_good );
				endif;
				if( $review_bad ):
					$review_bad_array = preg_split( '/\r\n|[\r\n]/', $review_bad );
				endif;

				// Global score and based number point
				$total_score = 0;
				$total_num = 0;

				$review_meta        = get_post_meta( $review_id, 'goso_review_meta', true );
				$review_ct_image    = isset( $review_meta['goso_review_ct_image'] ) ? $review_meta['goso_review_ct_image'] : '';
				$review_address     = isset( $review_meta['goso_review_address'] ) ? $review_meta['goso_review_address'] : '';
				$review_phone       = isset( $review_meta['goso_review_phone'] ) ? $review_meta['goso_review_phone'] : '';
				$review_website     = isset( $review_meta['goso_review_website'] ) ? $review_meta['goso_review_website'] : '';
				$review_price       = isset( $review_meta['goso_review_price'] ) ? $review_meta['goso_review_price'] : '';
				$review_linkbuy     = isset( $review_meta['goso_review_linkbuy'] ) ? $review_meta['goso_review_linkbuy'] : '';
				$review_textbuy     = isset( $review_meta['goso_review_textbuy'] ) ? $review_meta['goso_review_textbuy'] : '';
				$schema_markup_type = isset( $review_meta['goso_review_schema_markup'] ) ? $review_meta['goso_review_schema_markup'] : '';
				$img_size_pre       = isset( $review_meta['goso_review_img_size'] ) ? $review_meta['goso_review_img_size'] : '';

				$schema_options_val = get_post_meta( $review_id, 'goso_review_schema_options', true );
				$schema_type_val    = isset( $schema_options_val[ $schema_markup_type ] ) ? $schema_options_val[ $schema_markup_type ] : array();

				// Hide featured image
				$hide_img     = goso_predata_customize_pmeta( $review_meta, 'goso_rv_hide_featured_img', 'goso_rv_hide_featured_img' );
				$hide_schema   = goso_predata_customize_pmeta( $review_meta, 'goso_review_hide_schema', 'goso_rv_hide_schema'  );

				$total_average = goso_get_review_average_score( $review_id );
				$total_average = $total_average/2;

				if( $total_average ){
					$total_average = number_format( $total_average, 1, '.', '' );
				}

				if( 'none' != $schema_markup_type ) {
					self::output_schema( array(
						'goso_review'    => $review_meta,
						'schema_type'     => $schema_markup_type,
						'schema_type_val' => $schema_type_val,
						'ratingValue'     => $total_average,
						'post_id'         => $review_id
					) );
				}
			}
		}

		public static function output_schema( $args ) {
			global $post;

			$goso_review = $schema_type = $schema_type_val = $ratingValue = $post_id = '';

			$args = shortcode_atts( array(
				'goso_review'    => '',
				'schema_type'     => '',
				'schema_type_val' => '',
				'ratingValue'     => '',
				'post_id'         => ''
			), $args );

			extract( $args );

			if( 'none' == $schema_type ) {
				return '';
			}

			if( ! $post_id ){
				$post_id = get_the_ID();
			}

			if( $ratingValue ) {
				$ratingValue = number_format( $ratingValue, 1, '.', '' );
			}

			$json = [];
			if( 'Product' == $schema_type ){
				$json = self::output_schema_product(  $goso_review, $schema_type, $schema_type_val, $ratingValue, $post_id );
			}elseif( 'Restaurant' == $schema_type ){
				$json = self::output_schema_restaurant(  $goso_review, $schema_type, $schema_type_val, $ratingValue, $post_id );
			}elseif( 'Book' == $schema_type ){
				$json = self::output_schema_book(  $goso_review, $schema_type, $schema_type_val, $ratingValue, $post_id );
			}elseif( 'Game' == $schema_type ){
				$json = self::output_schema_game(  $goso_review, $schema_type, $schema_type_val, $ratingValue, $post_id );
			}elseif( 'Movie' == $schema_type ){
				$json = self::output_schema_movie(  $goso_review, $schema_type, $schema_type_val, $ratingValue, $post_id );
			}elseif( 'MusicRecording' == $schema_type ){
				$json = self::output_schema_musicrecording(  $goso_review, $schema_type, $schema_type_val, $ratingValue, $post_id );
			}elseif( 'Painting' == $schema_type ){
				$json = self::output_schema_painting(  $goso_review, $schema_type, $schema_type_val, $ratingValue, $post_id );
			}elseif( 'SoftwareApplication' == $schema_type ){
				$json = self::output_schema_software(  $goso_review, $schema_type, $schema_type_val, $ratingValue, $post_id );
			}elseif( 'Store' == $schema_type ){
				$json = self::output_schema_store(  $goso_review, $schema_type, $schema_type_val, $ratingValue, $post_id );
			}elseif( 'TVSeries' == $schema_type ){
				$json = self::output_schema_store(  $goso_review, $schema_type, $schema_type_val, $ratingValue, $post_id );
			}elseif( 'WebSite' == $schema_type ){
				$json = self::output_schema_store(  $goso_review, $schema_type, $schema_type_val, $ratingValue, $post_id );
			}elseif( 'Course' == $schema_type ){
				$json = self::output_schema_course(  $goso_review, $schema_type, $schema_type_val, $ratingValue, $post_id );
			}elseif( 'Event' == $schema_type ){
				$json = self::output_schema_event(  $goso_review, $schema_type, $schema_type_val, $ratingValue, $post_id );
			}

			echo '<script type="application/ld+json" class="goso-review-schema">' . wp_json_encode( $json ) . '</script>';

		}
		public static function output_schema_event( $goso_review, $schema_type, $schema_type_val, $ratingValue, $post_id ) {


			$json = array(
				'@context'      => 'https://schema.org/',
				'@type'         => 'Review',
				'itemReviewed' => array(
					'@type'               => $schema_type,
					'name'                => isset( $schema_type_val['name'] ) && $schema_type_val['name'] ? $schema_type_val['name'] : '',
					'startDate'           => isset( $schema_type_val['startDate'] ) && $schema_type_val['startDate'] ? $schema_type_val['startDate'] : '',
					'endDate'             => isset( $schema_type_val['endDate'] ) && $schema_type_val['endDate'] ? $schema_type_val['endDate'] : '',
					'eventAttendanceMode' => "https://schema.org/OfflineEventAttendanceMode",
					'eventStatus'         => "https://schema.org/EventScheduled",
					'location'            => array(
						'@type'   => 'Place',
						'name'    => isset( $schema_type_val['location_name'] ) && $schema_type_val['location_name'] ? $schema_type_val['location_name'] : '',
						'address' => array(
							'@type'         => "PostalAddress",
							'streetAddress' => isset( $schema_type_val['location_address'] ) && $schema_type_val['location_address'] ? $schema_type_val['location_address'] : '',
						)
					),
					'image'               => self::get_url_image( $post_id, $goso_review ),
					'description'         => isset( $schema_type_val['description'] ) && $schema_type_val['description'] ? $schema_type_val['description'] : '',
					'offers'              => array(
						'@type'         => "Offer",
						'url'           => isset( $schema_type_val['url'] ) && $schema_type_val['url'] ? $schema_type_val['url'] : '',
						'price'         => isset( $schema_type_val['price'] ) && $schema_type_val['price'] ? $schema_type_val['price'] : '',
						'priceCurrency' => isset( $schema_type_val['priceCurrency'] ) && $schema_type_val['priceCurrency'] ? $schema_type_val['priceCurrency'] : '',
						'availability'  => "https://schema.org/InStock",
						'validFrom'     => isset( $schema_type_val['validFrom'] ) && $schema_type_val['validFrom'] ? $schema_type_val['validFrom'] : '',
					),
					'performer'           => array(
						'@type' => "PerformingGroup",
						'name'  => get_bloginfo( 'name' ),
					),
					'organizer'           => array(
						'@type'  => "Organization",
						'url'    => isset( $schema_type_val['url'] ) && $schema_type_val['url'] ? $schema_type_val['url'] : '',
						'name'   => get_bloginfo( 'name' ),
						'sameAs' => get_the_permalink( $post_id ),
					),
				),
				'reviewRating'  => array(
					'@type'       => 'Rating',
					'ratingValue' => $ratingValue,
				),
				'name'          => self::get_post_title(),
				'author'        => array(
					'@type' => 'Person',
					'name'  => self::get_post_author(),
				),
				'reviewBody'    => self::get_post_title(),
				'organizer'     => array(
					'@type'      => "Organization",
					'name'       => get_bloginfo( 'name' ),
					'sameAs'     => get_the_permalink( $post_id ),
				),
				'datePublished' => get_the_date( 'Y-m-d' ),
			);

			return $json;
		}
		public static function output_schema_course( $goso_review, $schema_type, $schema_type_val, $ratingValue, $post_id ) {
			$json = array(
				'@context'     => 'https://schema.org/',
				'@type'        => 'Review',
				'itemReviewed' => array(
					'@type' => $schema_type,
					'provider'    => array(
						'@type' => "Organization",
						'name'  => get_bloginfo( 'name' ),
						'sameAs'  => get_the_permalink( $post_id ),
					),
				),
				'reviewRating' => array(
					'@type'       => 'Rating',
					'ratingValue' => $ratingValue,
				),
				'name'         => self::get_post_title(),
				'author'       => array(
					'@type' => 'Person',
					'name'  => self::get_post_author(),
				),
				'reviewBody'   => self::get_post_title(),
				'publisher' => array(
					'@type'      => "Organization",
					'name'       => get_bloginfo( 'name' ),
					'sameAs'     => get_the_permalink( $post_id ),
				),
				'datePublished'    => get_the_date( 'Y-m-d' ),
			);

			if( $schema_type_val ){
				foreach ( $schema_type_val as $key => $value ) {
					if ( $value && in_array( $key, array( 'openingHours','servesCuisine' ) ) ) {
						$value = preg_split( '/\r\n|[\r\n]/', $value );
					}

					$json['itemReviewed'][ $key ] = $value;
					$json[ $key ] = $value;
				}
			}

			if( $ratingValue ){
				$json['itemReviewed'][ 'aggregateRating' ] = array(
					'@type' => 'AggregateRating',
					'ratingValue' => $ratingValue,
					'reviewCount' => $ratingValue ? round( $ratingValue ) : '',
				);
			}

			return $json;
		}
		public static function output_schema_store( $goso_review, $schema_type, $schema_type_val, $ratingValue, $post_id ) {
			$json = array(
				'@context'     => 'https://schema.org/',
				'@type'        => 'Review',
				'itemReviewed' => array(
					'@type' => $schema_type,
					'image' => self::get_url_image( $post_id, $goso_review ),
				),
				'reviewRating' => array(
					'@type'       => 'Rating',
					'ratingValue' => $ratingValue,
				),
				'name'         => self::get_post_title(),
				'author'       => array(
					'@type' => 'Person',
					'name'  => self::get_post_author(),
				),
				'reviewBody'   => self::get_post_title(),
				'publisher'    => array(
					'@type' => "Organization",
					'name'  => get_bloginfo( 'name' )
				),
				'datePublished'    => get_the_date( 'Y-m-d' ),
			);

			if( $schema_type_val ){
				foreach ( $schema_type_val as $key => $value ) {
					if ( $value && in_array( $key, array( 'openingHours','servesCuisine' ) ) ) {
						$value = preg_split( '/\r\n|[\r\n]/', $value );
					}

					$json['itemReviewed'][ $key ] = $value;
					$json[ $key ] = $value;
				}
			}

			if( $ratingValue ){
				$json['itemReviewed'][ 'aggregateRating' ] = array(
					'@type' => 'AggregateRating',
					'ratingValue' => $ratingValue,
					'reviewCount' => $ratingValue ? round( $ratingValue ) : '',
				);
			}

			return $json;
		}
		public static function output_schema_software( $goso_review, $schema_type, $schema_type_val, $ratingValue, $post_id ) {
			$json = array(
				'@context'     => 'https://schema.org/',
				'@type'        => 'Review',
				'itemReviewed' => array(
					'@type' => $schema_type,
					'image' => self::get_url_image( $post_id, $goso_review ),
				),
				'reviewRating' => array(
					'@type'       => 'Rating',
					'ratingValue' => $ratingValue,
				),
				'name'         => self::get_post_title(),
				'author'       => array(
					'@type' => 'Person',
					'name'  => self::get_post_author(),
				),
				'reviewBody'   => self::get_post_title(),
				'publisher'    => array(
					'@type' => "Organization",
					'name'  => get_bloginfo( 'name' )
				),
				'datePublished'    => get_the_date( 'Y-m-d' ),
			);

			if( $schema_type_val ){
				foreach ( $schema_type_val as $key => $value ) {
					if ( $value && in_array( $key, array( 'mpn','url','price', 'priceCurrency','availability','priceValidUntil' ) ) ) {
						$offers[$key] =  $value;
					}

					if ( $value && in_array( $key, array( 'openingHours','servesCuisine' ) ) ) {
						$value = preg_split( '/\r\n|[\r\n]/', $value );
					}

					$json['itemReviewed'][ $key ] = $value;
					$json[ $key ] = $value;
				}
			}
			if( $offers ){
				$json['itemReviewed'][ 'offers' ] = array_merge( array( "@type" => "Offer" ), $offers  );
			}

			if( $ratingValue ){
				$json['itemReviewed'][ 'aggregateRating' ] = array(
					'@type' => 'AggregateRating',
					'ratingValue' => $ratingValue,
					'reviewCount' => $ratingValue ? round( $ratingValue ) : '',
				);
			}

			return $json;
		}
		public static function output_schema_painting( $goso_review, $schema_type, $schema_type_val, $ratingValue, $post_id ) {
			$json = array(
				'@context'     => 'https://schema.org/',
				'@type'        => 'Review',
				'itemReviewed' => array(
					'@type' => $schema_type,
					'image' => self::get_url_image( $post_id, $goso_review ),
				),
				'reviewRating' => array(
					'@type'       => 'Rating',
					'ratingValue' => $ratingValue,
				),
				'name'         => self::get_post_title(),
				'author'       => array(
					'@type' => 'Person',
					'name'  => self::get_post_author(),
				),
				'reviewBody'   => self::get_post_title(),
				'publisher'    => array(
					'@type' => "Organization",
					'name'  => get_bloginfo( 'name' )
				),
				'datePublished'    => get_the_date( 'Y-m-d' ),
			);

			if( $schema_type_val ){
				foreach ( $schema_type_val as $key => $value ) {
					if ( $value && in_array( $key, array( 'openingHours','servesCuisine' ) ) ) {
						$value = preg_split( '/\r\n|[\r\n]/', $value );
					}

					$json['itemReviewed'][ $key ] = $value;
					$json[ $key ] = $value;
				}
			}

			if( $ratingValue ){
				$json['itemReviewed'][ 'aggregateRating' ] = array(
					'@type' => 'AggregateRating',
					'ratingValue' => $ratingValue,
					'reviewCount' => $ratingValue ? round( $ratingValue ) : '',
				);
			}

			return $json;
		}
		public static function output_schema_musicrecording( $goso_review, $schema_type, $schema_type_val, $ratingValue, $post_id ) {
			$json = array(
				'@context'     => 'https://schema.org/',
				'@type'        => 'Review',
				'itemReviewed' => array(
					'@type' => $schema_type,
					'image' => self::get_url_image( $post_id, $goso_review ),
				),
				'reviewRating' => array(
					'@type'       => 'Rating',
					'ratingValue' => $ratingValue,
				),
				'name'         => self::get_post_title(),
				'author'       => array(
					'@type' => 'Person',
					'name'  => self::get_post_author(),
				),
				'reviewBody'   => self::get_post_title(),
				'publisher'    => array(
					'@type' => "Organization",
					'name'  => get_bloginfo( 'name' )
				),
				'datePublished'    => get_the_date( 'Y-m-d' ),
			);

			if( $schema_type_val ){
				foreach ( $schema_type_val as $key => $value ) {
					if ( $value && in_array( $key, array( 'openingHours','servesCuisine' ) ) ) {
						$value = preg_split( '/\r\n|[\r\n]/', $value );
					}

					$json['itemReviewed'][ $key ] = $value;
					$json[ $key ] = $value;
				}
			}

			if( $ratingValue ){
				$json['itemReviewed'][ 'aggregateRating' ] = array(
					'@type' => 'AggregateRating',
					'ratingValue' => $ratingValue,
					'reviewCount' => $ratingValue ? round( $ratingValue ) : '',
				);
			}

			return $json;
		}
		public static function output_schema_movie( $goso_review, $schema_type, $schema_type_val, $ratingValue, $post_id ) {
			$json = array(
				'@context'     => 'https://schema.org/',
				'@type'        => 'Review',
				'itemReviewed' => array(
					'@type' => $schema_type,
					'image' => self::get_url_image( $post_id, $goso_review ),
					'sameAs' => get_the_permalink( $post_id )
				),
				'reviewRating' => array(
					'@type'       => 'Rating',
					'ratingValue' => $ratingValue,
				),
				'name'         => self::get_post_title(),
				'author'       => array(
					'@type' => 'Person',
					'name'  => self::get_post_author(),
				),
				'reviewBody'   => self::get_post_title(),
				'publisher'    => array(
					'@type' => "Organization",
					'name'  => get_bloginfo( 'name' )
				),
				'datePublished'    => get_the_date( 'Y-m-d' ),
			);

			if( $schema_type_val ){
				foreach ( $schema_type_val as $key => $value ) {
					if ( $value && in_array( $key, array( 'openingHours','servesCuisine' ) ) ) {
						$value = preg_split( '/\r\n|[\r\n]/', $value );
					}

					$json['itemReviewed'][ $key ] = $value;
					$json[ $key ] = $value;
				}
			}

			if( $ratingValue ){
				$json['itemReviewed'][ 'aggregateRating' ] = array(
					'@type' => 'AggregateRating',
					'ratingValue' => $ratingValue,
					'reviewCount' => $ratingValue ? round( $ratingValue ) : '',
				);
			}

			return $json;
		}
		public static function output_schema_game( $goso_review, $schema_type, $schema_type_val, $ratingValue, $post_id ) {
			$json = array(
				'@context'     => 'https://schema.org/',
				'@type'        => 'Review',
				'itemReviewed' => array(
					'@type' => $schema_type,
					'image' => self::get_url_image( $post_id, $goso_review ),
				),
				'reviewRating' => array(
					'@type'       => 'Rating',
					'ratingValue' => $ratingValue,
				),
				'name'         => self::get_post_title(),
				'author'       => array(
					'@type' => 'Person',
					'name'  => self::get_post_author(),
				),
				'reviewBody'   => self::get_post_title(),
				'publisher'    => array(
					'@type' => "Organization",
					'name'  => get_bloginfo( 'name' ),
				),
				'datePublished'    => get_the_date( 'Y-m-d' ),
			);

			if( $schema_type_val ){
				foreach ( $schema_type_val as $key => $value ) {
					if ( $value && in_array( $key, array( 'openingHours','servesCuisine' ) ) ) {
						$value = preg_split( '/\r\n|[\r\n]/', $value );
					}

					$json['itemReviewed'][ $key ] = $value;
					$json[ $key ] = $value;
				}
			}

			if( $ratingValue ){
				$json['itemReviewed'][ 'aggregateRating' ] = array(
					'@type' => 'AggregateRating',
					'ratingValue' => $ratingValue,
					'reviewCount' => $ratingValue ? round( $ratingValue ) : '',
				);
			}

			return $json;
		}
		public static function output_schema_book( $goso_review, $schema_type, $schema_type_val, $ratingValue, $post_id ) {
			$json = array(
				'@context'     => 'https://schema.org/',
				'@type'        => 'Review',
				'itemReviewed' => array(
					'@type' => $schema_type,
					'image' => self::get_url_image( $post_id, $goso_review ),
				),
				'reviewRating' => array(
					'@type'       => 'Rating',
					'ratingValue' => $ratingValue,
				),
				'name'         => self::get_post_title(),
				'author'       => array(
					'@type' => 'Person',
					'name'  => self::get_post_author(),
				),
				'reviewBody'   => self::get_post_title(),
				'publisher'    => array(
					'@type' => "Organization",
					'name'  => get_bloginfo( 'name' ),
				),
				'datePublished'    => get_the_date( 'Y-m-d' ),
			);

			if( $schema_type_val ){
				foreach ( $schema_type_val as $key => $value ) {
					if ( $value && in_array( $key, array( 'openingHours','servesCuisine' ) ) ) {
						$value = preg_split( '/\r\n|[\r\n]/', $value );
					}

					$json['itemReviewed'][ $key ] = $value;
					$json[ $key ] = $value;
				}
			}

			if( $ratingValue ){
				$json['itemReviewed'][ 'aggregateRating' ] = array(
					'@type' => 'AggregateRating',
					'ratingValue' => $ratingValue,
					'reviewCount' => $ratingValue ? round( $ratingValue ) : '',
				);
			}

			return $json;
		}
		public static function output_schema_restaurant( $goso_review, $schema_type, $schema_type_val, $ratingValue, $post_id ) {
			$json = array(
				'@context'     => 'https://schema.org/',
				'@type'        => 'Review',
				'itemReviewed' => array(
					'@type' => $schema_type,
					'image' => self::get_url_image( $post_id, $goso_review ),
					"name" => "Schema.org Ontology",
				),
				'reviewRating' => array(
					'@type'       => 'Rating',
					'ratingValue' => $ratingValue,
				),
				'name'         => self::get_post_title(),
				'author'       => array(
					'@type' => 'Person',
					'name'  => self::get_post_author(),
				),
				'reviewBody'   => self::get_post_title(),
				'publisher'    => array(
					'@type' => "Organization",
					'name'  => get_bloginfo( 'name' ),
				)
			);

			$offers = array();
			if( $schema_type_val ){
				foreach ( $schema_type_val as $key => $value ) {
					if ( $value && in_array( $key, array( 'mpn','url','price', 'priceCurrency','availability','priceValidUntil' ) ) ) {
						$offers[$key] =  $value;
						continue;
					}

					if( $value && 'brand' == $key ){
						$value = array(
							"@type" => "Thing",
							"name"  => $value
						);
					}

					if ( $value && in_array( $key, array( 'director', 'actor' ) ) ) {
						$mul_value = preg_split( '/\r\n|[\r\n]/', $value );
						$value     = array();
						foreach ( (array) $mul_value as $mul_value_item ) {
							$value[] = array(
								"@type" => "Person",
								"name"  => $mul_value_item
							);
						}
					}

					if ( $value && in_array( $key, array( 'openingHours','servesCuisine' ) ) ) {
						$value = preg_split( '/\r\n|[\r\n]/', $value );
					}

					$json['itemReviewed'][ $key ] = $value;
				}
			}

			if( $ratingValue ){
				$json['itemReviewed'][ 'aggregateRating' ] = array(
					'@type' => 'AggregateRating',
					'ratingValue' => $ratingValue,
					'reviewCount' => $ratingValue ? round( $ratingValue ) : '',
				);
			}

			if( $offers ){
				$json['itemReviewed'][ 'offers' ] = array_merge( array( "@type" => "Offer" ), $offers  );
			}

			return $json;
		}
		public static function output_schema_product( $goso_review, $schema_type, $schema_type_val, $ratingValue, $post_id ) {
			$json = array(
				'@context'     => 'https://schema.org/',
				'@type'        => 'Review',
				'itemReviewed' => array(
					'@type' => $schema_type,
					'image' => self::get_url_image( $post_id, $goso_review ),
					'review' => array(
						"@type" => "Review",
						'reviewRating' => array(
							'@type' => 'Rating',
							'ratingValue' => $ratingValue,
						),
						'author'       => array(
							'@type' => 'Person',
							'name'  => self::get_post_author(),
						),
						'reviewBody'   => self::get_review_des( $post_id ),
					)
				),
				'reviewRating' => array(
					'@type'       => 'Rating',
					'ratingValue' => $ratingValue,
				),
				'name'         => self::get_post_title(),
				'author'       => array(
					'@type' => 'Person',
					'name'  => self::get_post_author(),
				),
				'reviewBody'   => self::get_post_title(),
				'publisher'    => array(
					'@type' => "Organization",
					'name'  => get_bloginfo( 'name' ),
				)
			);

			$offers = array();
			if( $schema_type_val ){
				foreach ( $schema_type_val as $key => $value ) {
					if ( $value && in_array( $key, array( 'mpn','url','price', 'priceCurrency','availability','priceValidUntil' ) ) ) {
						$offers[$key] =  $value;
						continue;
					}

					if( $value && 'brand' == $key ){
						$value = array(
							"@type" => "Thing",
							"name"  => $value
						);
					}

					if ( $value && in_array( $key, array( 'director', 'actor' ) ) ) {
						$mul_value = preg_split( '/\r\n|[\r\n]/', $value );
						$value     = array();
						foreach ( (array) $mul_value as $mul_value_item ) {
							$value[] = array(
								"@type" => "Person",
								"name"  => $mul_value_item
							);
						}
					}

					if ( $value && in_array( $key, array( 'openingHours','servesCuisine' ) ) ) {
						$value = preg_split( '/\r\n|[\r\n]/', $value );
					}

					$json['itemReviewed'][ $key ] = $value;
				}
			}

			if( $ratingValue ){
				$json['itemReviewed'][ 'aggregateRating' ] = array(
					'@type' => 'AggregateRating',
					'ratingValue' => $ratingValue,
					'reviewCount' => $ratingValue ? round( $ratingValue ) : '',
				);
			}

			if( $offers ){
				$json['itemReviewed'][ 'offers' ] = array_merge( array( "@type" => "Offer" ), $offers  );
			}

			return $json;
		}

		public static function output_schema_old( $args ) {

			$goso_review = $schema_type = $schema_type_val = $ratingValue = $post_id = '';

			$args = shortcode_atts( array(
				'goso_review'    => '',
				'schema_type'     => '',
				'schema_type_val' => '',
				'ratingValue'     => '',
				'post_id'         => ''
			), $args );

			extract( $args );

			if( 'none' == $schema_type || ! $schema_type_val ) {
				return '';
			}

			if( ! $schema_type_val ){
				return '';
			}



			$review_ct_image    = isset( $goso_review['goso_review_ct_image'] ) ? $goso_review['goso_review_ct_image'] : '';
			$url_review_ct_image = wp_get_attachment_thumb_url( $review_ct_image );
			if( ! $url_review_ct_image && has_post_thumbnail( get_the_ID() ) ){
				$url_review_ct_image = get_the_post_thumbnail_url( get_the_ID(),'thumbnail' );
			}

			$json = array(
				'@context' => 'http://schema.org',
				'@type' => $schema_type,
				'image' => $url_review_ct_image,
			);

			$offers = array();
			foreach ( $schema_type_val as $key => $value ) {
				if( 'Product' == $schema_type ){

					if ( $value && in_array( $key, array( 'mpn','url','price', 'priceCurrency','availability','priceValidUntil' ) ) ) {
						$offers[$key] =  $value;
						continue;
					}

					if( $value && 'brand' == $key ){
						$value = array(
							"@type" => "Thing",
							"name"  => $value
						);
					}
				}
				if( 'SoftwareApplication' == $schema_type ){
					if ( $value && in_array( $key, array( 'price', 'priceCurrency' ) ) ) {
						$offers[$key] =  $value;
						continue;
					}
				}


				if ( $value && in_array( $key, array( 'director', 'actor' ) ) ) {
					$mul_value = preg_split( '/\r\n|[\r\n]/', $value );
					$value     = array();
					foreach ( (array) $mul_value as $mul_value_item ) {
						$value[] = array(
							"@type" => "Person",
							"name"  => $mul_value_item
						);
					}
				}

				if ( $value && in_array( $key, array( 'openingHours','servesCuisine' ) ) ) {
					$value = preg_split( '/\r\n|[\r\n]/', $value );
				}

				$json[ $key ] = $value;
			}

			if( $ratingValue &&  'Painting' != $schema_type ){
				$json[ 'aggregateRating' ] = array(
					'@type' => 'AggregateRating',
					'ratingValue' => $ratingValue,
					'reviewCount' => $ratingValue ? round( $ratingValue ) : '',
				);
			}


			if( $offers ){
				$json[ 'offers' ] = array_merge( array( "@type" => "Offer" ), $offers  );
			}

			$json[ 'review' ] = self::get_schema_markup_rating( $schema_type, $schema_type_val, $ratingValue );

			if( ! $json ){
				return '';
			}

			echo '<script type="application/ld+json">' . wp_json_encode( $json, JSON_PRETTY_PRINT ) . '</script>';

		}

		public static function get_post_title(){
			global $post;
			$post_title   = isset( $post->post_title ) ? $post->post_title : '';
			return $post_title ? $post_title : 'title';
		}

		public static function get_review_des( $post_id){
			return get_post_meta( $post_id, 'goso_review_des', true );
		}

		public static function get_post_author(){
			global $post;

			$post_author = isset( $post->post_author ) ? $post->post_author : '';
			$author      = get_the_author_meta( 'display_name', $post_author );

			return $author ? $author : 'author';
		}


		public static function get_url_image( $post_id, $goso_review ) {
			$review_ct_image = isset( $goso_review['goso_review_ct_image'] ) ? $goso_review['goso_review_ct_image'] : '';

			$url_review_ct_image = wp_get_attachment_thumb_url( $review_ct_image );
			if ( ! $url_review_ct_image && has_post_thumbnail( $post_id ) ) {
				$url_review_ct_image = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
			}

			$url_review_ct_image = "http://www.example.com/seafood-restaurant.jpg";

			return $url_review_ct_image;
		}

		public static function get_schema_markup_rating($schema_type, $schema_type_val, $ratingValue ){
			return array(
				'@type'        => 'Review',
				'reviewRating' => array(
					'@type'       => 'Rating',
					'ratingValue' => $ratingValue,
					'bestRating'  => 5,
				),
				'author'       => array(
					'@type' => 'Person',
					'name'  => isset( $schema_type_val['author'] ) ? $schema_type_val['author'] : ''
				)
			);
		}

		public static function get_list_schema() {
			return array(
				'none'                => goso_review_tran_setting( 'goso_review_text_none' ),
				'Book'                => goso_review_tran_setting( 'goso_review_text_book' ),
				'Course'              => goso_review_tran_setting( 'goso_review_text_course' ),
				'Event'               => goso_review_tran_setting( 'goso_review_text_event' ),
				'Game'                => goso_review_tran_setting( 'goso_review_text_game' ),
				'Movie'               => goso_review_tran_setting( 'goso_review_text_movie' ),
				'MusicRecording'      => goso_review_tran_setting( 'goso_review_text_musicreco' ),
				'Product'             => goso_review_tran_setting( 'goso_review_text_product' ),
				'Restaurant'          => goso_review_tran_setting( 'goso_review_text_restaurant' ),
				'SoftwareApplication' => goso_review_tran_setting( 'goso_review_text_sfapp' ),
				'Store'               => goso_review_tran_setting( 'goso_review_text_store' ),
				'TVSeries'            => goso_review_tran_setting( 'goso_review_text_tvseries' ),
			);
		}

		public static function get_schema_types( $type = '' ) {
			$schema_types = array(
				'Book'                => self::get_book_fields(),
				'Course'              => self::get_course_fields(),
				'Event'               => self::get_event_fields(),
				'Game'                => self::get_game_fields(),
				'Movie'               => self::get_movie_fields(),
				'MusicRecording'      => self::get_music_recording_fields(),
				'Product'             => self::get_product_fields(),
				'Restaurant'          => self::get_restaurant_fields(),
				'SoftwareApplication' => self::get_software_application_fields(),
				'Store'               => self::get_store_fields(),
				'TVSeries'            => self::get_TVSeries_fields(),
				'WebSite'             => self::get_webSite_fields()
			);

			$return_schema_types = apply_filters( 'goso_review_schema_types', $schema_types );

			return isset( $return_schema_types[$type] ) ? $return_schema_types[$type] : array();
		}

		public static function get_course_fields() {
			return array(
				array(
					'name'    => 'name',
					'label'   => goso_review_tran_setting('goso_reviewt_coursetitle'),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'description',
					'label'   => goso_review_tran_setting('goso_reviewt_coursedesc'),
					'type'    => 'textarea',
					'default' => ''
				),
			);
		}
		public static function get_event_fields() {
			return array(
				array(
					'name'    => 'name',
					'label'   => goso_review_tran_setting('goso_reviewt_eventtitle'),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'url',
					'label'   => goso_review_tran_setting('goso_reviewt_eventurl'),
					'type'    => 'text',
					'default' => ''
				),
				array(
					'name'    => 'startDate',
					'label'   => goso_review_tran_setting('goso_reviewt_eventsdate'),
					'type'    => 'date',
					'default' => '',
				),
				array(
					'name'    => 'endDate',
					'label'   => goso_review_tran_setting('goso_reviewt_eventedate'),
					'type'    => 'date',
					'default' => '',
				),
				array(
					'name'    => 'location_name',
					'label'   => goso_review_tran_setting('goso_reviewt_eventlname'),
					'type'    => 'text',
					'default' => ''
				),
				array(
					'name'    => 'location_address',
					'label'   => goso_review_tran_setting('goso_reviewt_eventladdress'),
					'type'    => 'text',
					'default' => ''
				),
				array(
					'name'    => 'price',
					'label'   => goso_review_tran_setting('goso_reviewt_eventprice'),
					'type'    => 'text',
					'default' => ''
				),
				array(
					'name'    => 'priceCurrency',
					'label'   => goso_review_tran_setting('goso_reviewt_eventpricec'),
					'type'    => 'text',
					'default' => ''
				),array(
					'name'    => 'validFrom',
					'label'   => goso_review_tran_setting('goso_reviewt_eventvalidFrom'),
					'type'    => 'date',
					'default' => ''
				),
				array(
					'name'    => 'description',
					'label'   => goso_review_tran_setting('goso_reviewt_eventdesc'),
					'type'    => 'textarea',
					'default' => ''
				),
			);
		}

		public static function get_book_fields() {
			return array(
				array(
					'name'    => 'name',
					'label'   => goso_review_tran_setting('goso_reviewt_btitle'),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'url',
					'label'   => goso_review_tran_setting('goso_reviewt_burl'),
					'type'    => 'text',
					'default' => ''
				),
				array(
					'name'    => 'author',
					'label'   => goso_review_tran_setting('goso_reviewt_bauthor'),
					'type'    => 'text',
					'default' => ''
				),
				array(
					'name'    => 'bookEdition',
					'label'   => goso_review_tran_setting('goso_reviewt_bedition'),
					'type'    => 'text',
					'default' => ''
				),
				array(
					'name'    => 'bookFormat',
					'label'   => goso_review_tran_setting('goso_reviewt_bformat'),
					'type'    => 'select',
					'default' => '',
					'options' => array(
						''                => esc_html__( 'Default', 'goso' ),
						'AudiobookFormat' => 'AudiobookFormat',
						'EBook'           => 'EBook',
						'Hardcover'       => 'Hardcover',
						'Paperback'       => 'Paperback'
					)
				),
				array(
					'name'    => 'datePublished',
					'label'   => goso_review_tran_setting('goso_reviewt_bdate'),
					'type'    => 'date',
					'default' => '',
				),
				array(
					'name'    => 'illustrator',
					'label'   => goso_review_tran_setting('goso_reviewt_billustrator'),
					'type'    => 'text',
					'default' => ''
				),
				array(
					'name'    => 'isbn',
					'label'   => goso_review_tran_setting('goso_reviewt_bISBN'),
					'type'    => 'text',
					'default' => ''
				),
				array(
					'name'    => 'numberOfPages',
					'label'   => goso_review_tran_setting('goso_reviewt_bnumberofpage'),
					'type'    => 'number',
					'default' => ''
				),
				array(
					'name'    => 'description',
					'label'   => goso_review_tran_setting('goso_reviewt_bdesc'),
					'type'    => 'textarea',
					'default' => ''
				),
			);
		}
		public static function get_game_fields() {
			return array(
				array(
					'name'    => 'name',
					'label'   => goso_review_tran_setting('goso_reviewt_game_title'),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'url',
					'label'   => goso_review_tran_setting('goso_reviewt_game_url'),
					'type'    => 'text',
					'default' => ''
				),
				array(
					'name'    => 'description',
					'label'   => goso_review_tran_setting('goso_reviewt_game_desc'),
					'type'    => 'textarea',
					'default' => ''
				)

			);
		}
		public static function get_movie_fields() {
			return array(
				array(
					'name'    => 'name',
					'label'   => esc_html__( 'Movie title', 'goso' ),
					'label'   => goso_review_tran_setting('goso_reviewt_mv_title'),
					'type'    => 'text',
					'default' => '',
				),

				array(
					'name'    => 'url',
					'label'   => esc_html__( 'URL', 'goso' ),
					'label'   => goso_review_tran_setting('goso_reviewt_mv_url'),
					'type'    => 'text',
					'default' => ''
				),
				array(
					'name'    => 'dateCreated',
					'label'   => esc_html__( 'Date published', 'goso' ),
					'label'   => goso_review_tran_setting('goso_reviewt_mv_date'),
					'type'    => 'date',
					'default' => '',
				),
				array(
					'name'    => 'description',
					'label'   => esc_html__( 'Movie description', 'goso' ),
					'label'   => goso_review_tran_setting('goso_reviewt_mv_desc'),
					'type'    => 'textarea',
					'default' => ''
				),
				array(
					'name'      => 'director',
					'label'     => esc_html__( 'Director(s)', 'goso' ),
					'label'   => goso_review_tran_setting('goso_reviewt_mv_dir'),
					'type'      => 'textarea',
					'default'   => '',
					'desc'      => esc_html__( 'Add one director per line', 'goso' ),
				),
				array(
					'name'      => 'actor',
					'label'     => esc_html__( 'Actor(s)', 'goso' ),
					'label'   => goso_review_tran_setting('goso_reviewt_mv_actor'),
					'type'      => 'textarea',
					'default'   => '',
					'desc'      => esc_html__( 'Add one actor per line', 'goso' ),
				),
				array(
					'name'      => 'genre',
					'label'     => esc_html__( 'Genre', 'goso' ),
					'label'   => goso_review_tran_setting('goso_reviewt_mv_genre'),
					'type'      => 'textarea',
					'default'   => ''
				),
			);
		}
		public static function get_music_recording_fields() {
			return array(
				array(
					'name'    => 'name',
					'label'   => esc_html__( 'Track name', 'goso' ),
					'label'   => goso_review_tran_setting('goso_reviewt_music_name'),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'url',
					'label'   => esc_html__( 'URL', 'goso' ),
					'label'   => goso_review_tran_setting('goso_reviewt_music_url'),
					'type'    => 'text',
					'default' => ''
				),
				array(
					'name'    => 'byArtist',
					'label'   => esc_html__( 'Author', 'goso' ),
					'label'   => goso_review_tran_setting('goso_reviewt_music_author'),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'duration',
					'label'   => esc_html__( 'Track Duration', 'goso' ),
					'label'   => goso_review_tran_setting('goso_reviewt_music_dur'),
					'type'    => 'text',
					'default' => ''
				),
				array(
					'name'    => 'inAlbum',
					'label'   => esc_html__( 'Album name', 'goso' ),
					'label'   => goso_review_tran_setting('goso_reviewt_music_album'),
					'type'    => 'text',
					'default' => ''
				),
				array(
					'name'      => 'genre',
					'label'     => esc_html__( 'Genre', 'goso' ),
					'label'   => goso_review_tran_setting('goso_reviewt_music_genre'),
					'type'      => 'textarea',
					'default'   => ''
				),
			);
		}
		public static function get_painting_fields() {
			return array(
				array(
					'name'    => 'name',
					'label'   => esc_html__( 'Name', 'goso' ),
					'label'   => goso_review_tran_setting('goso_reviewt_painting_name'),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'author',
					'label'   => esc_html__( 'Author', 'goso' ),
					'label'   => goso_review_tran_setting('goso_reviewt_painting_author'),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'url',
					'label'   => esc_html__( 'URL', 'goso' ),
					'label'   => goso_review_tran_setting('goso_reviewt_painting_url'),
					'type'    => 'text',
					'default' => ''
				),
				array(
					'name'    => 'datePublished',
					'label'   => esc_html__( 'Date published', 'goso' ),
					'label'   => goso_review_tran_setting('goso_reviewt_painting_date_pub'),
					'type'    => 'date',
					'default' => '',
				),
				array(
					'name'      => 'genre',
					'label'     => esc_html__( 'Genre', 'goso' ),
					'label'   => goso_review_tran_setting('goso_reviewt_painting_genre'),
					'type'      => 'textarea',
					'default'   => ''
				)

			);
		}
		public static function get_place_fields() {
			return array(
				array(
					'name'    => 'name',
					'label'   => goso_review_tran_setting('goso_reviewt_place_name'),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'url',
					'label'   => goso_review_tran_setting('goso_reviewt_place_url'),
					'type'    => 'text',
					'default' => ''
				),
				array(
					'name'    => 'description',
					'label'   => goso_review_tran_setting('goso_reviewt_place_desc'),
					'type'    => 'textarea',
					'default' => ''
				)
			);
		}
		public static function get_product_fields() {
			return array(
				array(
					'name'    => 'name',
					'label'   => goso_review_tran_setting('goso_reviewt_prod_name'),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'url',
					'label'   => goso_review_tran_setting('goso_reviewt_prod_url'),
					'type'    => 'text',
					'default' => ''
				),
				array(
					'name'    => 'price',
					'label'   => goso_review_tran_setting('goso_reviewt_prod_price'),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'priceCurrency',
					'label'   => goso_review_tran_setting('goso_reviewt_prod_currency'),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'availability',
					'label'   => goso_review_tran_setting('goso_reviewt_prod_avai'),
					'type'    => 'select',
					'default' => '',
					'options' => array(
						''                    => '---',
						'Discontinued'        => 'Discontinued',
						'InStock'             => 'In Stock',
						'InStoreOnly'         => 'In Store Only',
						'LimitedAvailability' => 'Limited',
						'OnlineOnly'          => 'Online Only',
						'OutOfStock'          => 'Out Of Stock',
						'PreOrder'            => 'Pre Order',
						'PreSale'             => 'Pre Sale',
						'SoldOut'             => 'Sold Out'
					)
				),
				array(
					'name'    => 'brand',
					'label'   => goso_review_tran_setting('goso_reviewt_prod_band'),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'sku',
					'label'   => goso_review_tran_setting('goso_reviewt_prod_suk'),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'mpn',
					'label'   => goso_review_tran_setting('goso_reviewt_prod_mpn'),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'priceValidUntil',
					'label'   => goso_review_tran_setting('goso_reviewt_prod_pricevali'),
					'type'    => 'date',
					'default' => '',
				),
				array(
					'name'    => 'description',
					'label'   => goso_review_tran_setting('goso_reviewt_prod_desc'),
					'type'    => 'textarea',
					'default' => ''
				),
			);
		}
		public static function get_restaurant_fields() {
			return array(
				array(
					'name'    => 'name',
					'label'   => goso_review_tran_setting('goso_reviewt_restau_name'),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'url',
					'label'   => goso_review_tran_setting('goso_reviewt_restau_url'),
					'type'    => 'text',
					'default' => ''
				),
				array(
					'name'    => 'address',
					'label'   => goso_review_tran_setting('goso_reviewt_restau_address'),
					'type'    => 'text',
					'default' => ''
				),array(
					'name'    => 'priceRange',
					'label'   => goso_review_tran_setting('goso_reviewt_restau_price'),
					'type'    => 'text',
					'default' => ''
				),array(
					'name'    => 'telephone',
					'label'   => goso_review_tran_setting('goso_reviewt_restau_telephone'),
					'type'    => 'text',
					'default' => ''
				),array(
					'name'    => 'servesCuisine',
					'label'   => goso_review_tran_setting('goso_reviewt_restau_serves'),
					'type'    => 'textarea',
					'default' => '',
					'desc'    => esc_html__( 'Add one cuisine  per line', 'goso' ),
				),array(
					'name'    => 'openingHours',
					'label'   => goso_review_tran_setting('goso_reviewt_restau_ophours'),
					'type'    => 'textarea',
					'default' => '',
					'desc'    => esc_html__( 'Add one opening hour per line', 'goso' ),
				),
				array(
					'name'    => 'description',
					'label'   => goso_review_tran_setting('goso_reviewt_restau_desc'),
					'type'    => 'textarea',
					'default' => ''
				),
			);
		}
		public static function get_software_application_fields() {
			return array(
				array(
					'name'    => 'name',
					'label'   => goso_review_tran_setting('goso_reviewt_app_name'),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'url',
					'label'   => goso_review_tran_setting('goso_reviewt_app_url'),
					'type'    => 'text',
					'default' => ''
				),
				array(
					'name'    => 'price',
					'label'   => goso_review_tran_setting('goso_reviewt_app_price'),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'priceCurrency',
					'label'   => goso_review_tran_setting('goso_reviewt_app_currency'),
					'type'    => 'text',
					'default' => ''
				),
				array(
					'name'    => 'operatingSystem',
					'label'   => goso_review_tran_setting('goso_reviewt_app_opsystem'),
					'type'    => 'text',
					'default' => '',
					'desc'    => esc_html__( 'For example, "Windows 7", "OSX 10.6", "Android 1.6"', 'goso' )
				),
				array(
					'name'    => 'applicationCategory',
					'label'   => goso_review_tran_setting('goso_reviewt_app_app_cat'),
					'type'    => 'text',
					'default' => '',
					'desc'    => esc_html__( 'For example, "Game", "Multimedia"', 'goso' )
				),
				array(
					'name'    => 'description',
					'label'   => goso_review_tran_setting('goso_reviewt_app_desc'),
					'type'    => 'textarea',
					'default' => ''
				),
			);
		}
		public static function get_store_fields() {
			return array(
				array(
					'name'    => 'name',
					'label'   => esc_html__( 'Store Name', 'goso' ),
					'label'   => goso_review_tran_setting('goso_reviewt_store_name'),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'url',
					'label'   => esc_html__( 'URL', 'goso' ),
					'label'   => goso_review_tran_setting('goso_reviewt_store_url'),
					'type'    => 'text',
					'default' => ''
				),
				array(
					'name'    => 'address',
					'label'   => esc_html__( 'Address', 'goso' ),
					'label'   => goso_review_tran_setting('goso_reviewt_store_address'),
					'type'    => 'text',
					'default' => ''
				),array(
					'name'    => 'priceRange',
					'label'   => esc_html__( 'Price range', 'goso' ),
					'label'   => goso_review_tran_setting('goso_reviewt_store_price'),
					'type'    => 'text',
					'default' => ''
				),array(
					'name'    => 'telephone',
					'label'   => esc_html__( 'Telephone', 'goso' ),
					'label'   => goso_review_tran_setting('goso_reviewt_store_telephone'),
					'type'    => 'text',
					'default' => ''
				),
				array(
					'name'    => 'description',
					'label'   => esc_html__( 'Store Description', 'goso' ),
					'label'   => goso_review_tran_setting('goso_reviewt_store_desc'),
					'type'    => 'textarea',
					'default' => ''
				),
			);
		}
		public static function get_TVSeries_fields() {
			return array(
				array(
					'name'    => 'name',
					'label'   => goso_review_tran_setting('goso_reviewt_tv_name'),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'url',
					'label'   => goso_review_tran_setting('goso_reviewt_tv_url'),
					'type'    => 'text',
					'default' => ''
				),
				array(
					'name'    => 'description',
					'label'   => goso_review_tran_setting('goso_reviewt_tv_desc'),
					'type'    => 'textarea',
					'default' => ''
				),
			);
		}
		public static function get_webSite_fields() {
			return array(
				array(
					'name'    => 'name',
					'label'   => goso_review_tran_setting('goso_reviewt_web_name'),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'url',
					'label'   => goso_review_tran_setting('goso_reviewt_web_url'),
					'type'    => 'text',
					'default' => ''
				),
				array(
					'name'    => 'description',
					'label'   => goso_review_tran_setting('goso_reviewt_web_desc'),
					'type'    => 'textarea',
					'default' => ''
				),
			);
		}
		public static function get_schema_filed( $type,$schema_val , $post_id ){
			$datas = self::get_schema_types( $type );
			$schema_options_val = get_post_meta( $post_id, 'goso_review_schema_options', true );

			echo '<div class="goso-review_schema_fields goso-review_' . $type .  '_fields" ' . ( $schema_val != $type ? 'style="display:none;"' : '' ) . '>';

			foreach ( (array)$datas as $field_type => $field ){
				$type_field = isset( $field['type'] ) ? $field['type'] : '';
				$name       = isset( $field['name'] ) ? $field['name'] : '';
				$label      = isset( $field['label'] ) ? $field['label'] : '';
				$desc       = isset( $field['desc'] ) ? $field['desc'] : '';
				$options    = isset( $field['options'] ) ? $field['options'] : array();
				$default    = isset( $field['default'] ) ? $field['default'] : '';

				$opt_id   = 'goso_review_schema_options_' . $type_field . '_' . $name;
				$opt_name = 'goso_review_schema_options[' . $type . '][' . $name . ']';
				$opt_val  = isset( $schema_options_val[ $type ][ $name ] ) ? $schema_options_val[ $type ][ $name ] : $default;

				if( 'image' == $type_field ) {
					echo '<div>';
				}else{
					echo '<p ' . (  'textarea' != $type_field ? 'class="col-6"' : '' ) . '>';
				}

				?>
				<label for="goso_review_schema_markup" class="goso-format-row"><?php echo $label; ?></label>
				<?php
				if( 'textarea' == $type_field ) {
					?>
					<textarea style="width:100%; height:120px;" name="<?php echo $opt_name; ?>" class="<?php echo $opt_id; ?>"><?php echo $opt_val; ?></textarea>
					<?php
				}elseif( 'image' == $type_field ) {
					$url_image = wp_get_attachment_thumb_url( $opt_val );
					?>
					<div class="goso-widget-image media-widget-control" style="max-width: 350px;">
						<input name="<?php echo $opt_name; ?>" type="hidden" class="goso-widget-image__input" value="<?php echo esc_attr( $opt_val ); ?>">
						<img src="<?php echo esc_url( $url_image ); ?>" class="goso-widget-image__image<?php echo $url_image ? '' : ' hidden'; ?>">
						<div class="placeholder <?php echo( $url_image ? 'hidden' : '' ); ?>"><?php _e( 'No image selected' ); ?></div>
						<button class="button goso-widget-image__select_review"><?php esc_html_e( 'Select' ); ?></button>
						<button class="button goso-widget-image__remove"><?php esc_html_e( 'Remove' ); ?></button>
					</div>
					<?php
				}elseif( 'select' == $type_field ) {
					?>
					<select name="<?php echo $opt_name; ?>" class="<?php echo $opt_id; ?>">
						<?php
						foreach ( $options as $option_val => $option_label ) {
							echo '<option value="' . $option_val . '" ' . selected( $opt_val, $option_val, false ) . '>' . $option_label . '</option>';
						}
						?>
					</select>
					<?php
				}elseif( 'date' == $type_field ) {
					?>
					<input class="goso-datepicker" type="text" name="<?php echo $opt_name; ?>" class="<?php echo $opt_id; ?>" value="<?php echo $opt_val; ?>" size="30" />
					<?php
				}elseif( 'number' == $type_field ) {
					?>
					<input type="number" name="<?php echo $opt_name; ?>" class="<?php echo $opt_id; ?>" value="<?php echo $opt_val; ?>" size="30" />
					<?php
				} else {
					?>
					<input  type="text" name="<?php echo $opt_name; ?>" class="<?php echo $opt_id; ?>" value="<?php echo $opt_val; ?>">
					<?php
				}
				?>

				<?php if( $desc ): ?>
					<span class="goso-recipe-description"><?php echo $desc; ?></span>
				<?php endif; ?>
				<?php
				echo ( 'image' == $type_field ? '</div><p></p>' : '</p>' );
			}
			echo '</div>';
		}
	}
	new Goso_Review_Schema_Markup;
endif;