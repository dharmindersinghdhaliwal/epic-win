<?php
/**
 * Common functions
 *
 * Common functions are ones that are used by more than one component, like
 * achievements, achievement_progress, events taxonomy...
 *
 * @package Achievements
 * @subpackage CommonFunctions
 */
if ( ! defined( 'ABSPATH' ) ) exit;
function dpa_time_since( $older_date, $newer_date = false, $gmt = false ) {
	echo dpa_get_time_since( $older_date, $newer_date, $gmt );
}
	function dpa_get_time_since( $older_date, $newer_date = false, $gmt = false ) {
		$unknown_text   = apply_filters( 'dpa_time_since_unknown_text',   _x( 'sometime',  'time', 'dpa' ) );
		$right_now_text = apply_filters( 'dpa_time_since_right_now_text', _x( 'right now', 'time', 'dpa' ) );
		$ago_text       = apply_filters( 'dpa_time_since_ago_text',       _x( '%s ago',    'time', 'dpa' ) );
		$chunks = array(
			array( 60 * 60 * 24 * 365 , __( 'year',   'dpa' ), __( 'years',   'dpa' ) ),
			array( 60 * 60 * 24 * 30 ,  __( 'month',  'dpa' ), __( 'months',  'dpa' ) ),
			array( 60 * 60 * 24 * 7,    __( 'week',   'dpa' ), __( 'weeks',   'dpa' ) ),
			array( 60 * 60 * 24 ,       __( 'day',    'dpa' ), __( 'days',    'dpa' ) ),
			array( 60 * 60 ,            __( 'hour',   'dpa' ), __( 'hours',   'dpa' ) ),
			array( 60 ,                 __( 'minute', 'dpa' ), __( 'minutes', 'dpa' ) ),
			array( 1,                   __( 'second', 'dpa' ), __( 'seconds', 'dpa' ) )
		);
		if ( ! empty( $older_date ) && ! is_numeric( $older_date ) ) {
			$time_chunks = explode( ':', str_replace( ' ', ':', $older_date ) );
			$date_chunks = explode( '-', str_replace( ' ', '-', $older_date ) );
			$older_date  = gmmktime( (int) $time_chunks[1], (int) $time_chunks[2], (int) $time_chunks[3], (int) $date_chunks[1], (int) $date_chunks[2], (int) $date_chunks[0] );
		}
		$newer_date = ( ! $newer_date ) ? strtotime( current_time( 'mysql', $gmt ) ) : $newer_date;
		$since = $newer_date - $older_date;
		if ( 0 > $since ) {
			$output = $unknown_text;
		} else {
			for ( $i = 0, $j = count( $chunks ); $i < $j; ++$i ) {
				$seconds = $chunks[$i][0];
				$count = floor( $since / $seconds );
				if ( 0 != $count ) {
					break;
				}
			}			
			if ( ! isset( $chunks[$i] ) ) {
				$output = $right_now_text;
			} else {
				$output = ( 1 == $count ) ? '1 '. $chunks[$i][1] : $count . ' ' . $chunks[$i][2];
				if ( $i + 2 < $j ) {
					$seconds2 = $chunks[$i + 1][0];
					$name2    = $chunks[$i + 1][1];
					$count2   = floor( ( $since - ( $seconds * $count ) ) / $seconds2 );
					if ( 0 != $count2 ) {
						$output .= ( 1 == $count2 ) ? _x( ',', 'Separator in time since', 'dpa' ) . ' 1 '. $name2 : _x( ',', 'Separator in time since', 'dpa' ) . ' ' . $count2 . ' ' . $chunks[$i + 1][2];
					}
				}
				if ( ! (int) trim( $output ) ) {
					$output = $right_now_text;
				}
			}
		}
		if ( $output !== $right_now_text ) {
			$output = sprintf( $ago_text, $output );
		}
		return apply_filters( 'dpa_get_time_since', $output, $older_date, $newer_date );
	}
function dpa_add_error( $code = '', $message = '', $data = '' ) {
	achievements()->errors->add( $code, $message, $data );
}
function dpa_has_errors() {
	$has_errors = achievements()->errors->get_error_codes() ? true : false; 

	return apply_filters( 'dpa_has_errors', $has_errors, achievements()->errors );
}
function dpa_version() {
	echo dpa_get_version();
}	function dpa_get_version() {
		return achievements()->version;
	}
function dpa_db_version() {
	echo dpa_get_db_version();
}
	function dpa_get_db_version() {
		return achievements()->db_version;
	}
function dpa_db_version_raw() {
	echo dpa_get_db_version_raw();
}
	function dpa_get_db_version_raw() {
		return get_option( '_dpa_db_version', '' );
	}
function dpa_integrate_into_buddypress() {
	return apply_filters( 'dpa_integrate_into_buddypress', achievements()->integrate_into_buddypress );
}
function dpa_is_developer_mode() {
	return apply_filters( 'dpa_is_developer_mode', defined( 'WP_DEBUG' ) && WP_DEBUG );
}
function dpa_get_paged() {
	global $wp_query;
	if ( get_query_var( 'paged' ) ) {
		$paged = get_query_var( 'paged' );
	} elseif ( ! empty( $wp_query->query['paged'] ) ) {
		$paged = $wp_query->query['paged'];
	}	
	if ( ! empty( $paged ) )
		return (int) $paged;
	return 1;
}
function dpa_get_leaderboard_paged() {
	return ( ! empty( $_GET['leaderboard-page'] ) ) ? (int) $_GET['leaderboard-page'] : 1;
}
function dpa_check_buddypress_is_active( $plugin_basename ) {
	if ( strpos( 'buddypress/bp-loader.php', $plugin_basename ) === false )
		return;
	dpa_delete_rewrite_rules();
}
function dpa_delete_rewrite_rules() {
	delete_option( 'rewrite_rules' );
}
function dpa_parse_args( $args, $defaults = array(), $filter_key = '' ) {
	if ( is_object( $args ) )
		$r = get_object_vars( $args );
	elseif ( is_array( $args ) )
		$r =& $args;
	else
		wp_parse_str( $args, $r );
	if ( ! empty( $filter_key ) )
		$r = apply_filters( 'dpa_before_' . $filter_key . '_parse_args', $r );
	if ( is_array( $defaults ) && ! empty( $defaults ) )
		$r = array_merge( $defaults, $r );
	if ( ! empty( $filter_key ) )
		$r = apply_filters( 'dpa_after_' . $filter_key . '_parse_args', $r );
	return $r;
}
function dpa_get_page_by_path( $path = '' ) {
	$retval = false;
	if ( ! empty( $path ) ) {
		if ( get_option( 'permalink_structure' ) )
			$retval = get_page_by_path( $path );
	}
	return apply_filters( 'dpa_get_page_by_path', $retval, $path );
}
function dpa_verify_nonce_request( $action = '', $query_arg = '_wpnonce' ) {
	$parsed_home = parse_url( home_url( '/', ( is_ssl() ? 'https://' : 'http://' ) ) );
	if ( isset( $parsed_home['port'] ) )
		$parsed_host = $parsed_home['host'] . ':' . $parsed_home['port'];
	else
		$parsed_host = $parsed_home['host'];
	$home_url = trim( strtolower( $parsed_home['scheme'] . '://' . $parsed_host . $parsed_home['path'] ), '/' );
	if ( isset( $parsed_home['port'] ) )
		$request_host = $_SERVER['HTTP_HOST'] . ':' . $_SERVER['SERVER_PORT'];
	else
		$request_host = $_SERVER['HTTP_HOST'];
	$scheme        = is_ssl() ? 'https://' : 'http://';
	$requested_url = strtolower( $scheme . $request_host . $_SERVER['REQUEST_URI'] );
	$result = isset( $_REQUEST[$query_arg] ) ? wp_verify_nonce( $_REQUEST[$query_arg], $action ) : false;
	if ( empty( $result ) || empty( $action ) || ( strpos( $requested_url, $home_url ) !== 0 ) )
		$result = false;
	do_action( 'dpa_verify_nonce_request', $action, $result );
	return $result;
}