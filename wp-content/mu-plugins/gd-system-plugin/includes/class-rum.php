<?php

namespace WPaaS;

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

final class RUM {

	/**
	 * Class constructor.
	 */
	public function __construct() {

		if ( ! self::is_enabled() ) {

			return;

		}

		add_action( 'wp_footer',    [ $this, 'print_inline_script' ], PHP_INT_MAX );
		add_action( 'admin_footer', [ $this, 'print_inline_script' ], PHP_INT_MAX );

	}

	/**
	 * Determine which builder platform was used to create the current page.
	 *
	 * Possible values:
	 *
	 * -- beaver-builder
	 * -- brizy
	 * -- divy
	 * -- elementor
	 * -- oxygen
	 * -- themify-builder
	 * -- visual-composer
	 * -- wp-block-editor (Gutenberg)
	 * -- wp-classic-editor
	 *
	 * Will return NULL when:
	 *
	 * -- The builder plugin used to construct the page is inactive.
	 * -- The WP Block Editor is being used but the page contains no blocks.
	 * -- A page builder platform can't be detected.
	 *
	 * @return string|null
	 */
	private static function get_page_builder() {

		global $post;

		if ( ! isset( $post->post_content ) ) {

			return null;

		}

		switch ( true ) {

			case ( class_exists( 'FLBuilderLoader' ) && 1 === (int) get_post_meta( $post->ID, '_fl_builder_enabled', true ) ):

				$builder = 'beaver-builder';

				break;

			case ( defined( 'BRIZY_VERSION' ) && get_post_meta( $post->ID, 'brizy_post_uid', true ) ):

				$builder = 'brizy';

				break;

			case ( defined( 'ET_BUILDER_VERSION' ) && 'on' === get_post_meta( $post->ID, '_et_pb_use_builder', true ) ):

				$builder = 'divi';

				break;

			case ( defined( 'ELEMENTOR_VERSION' ) && 'builder' === get_post_meta( $post->ID, '_elementor_edit_mode', true ) ):

				$builder = 'elementor';

				break;

			case ( defined( 'CT_VERSION' ) && get_post_meta( $post->ID, 'ct_builder_shortcodes', true ) ):

				$builder = 'oxygen';

				break;

			case ( defined( 'THEMIFY_VERSION' ) && get_post_meta( $post->ID, '_themify_builder_settings_json', true ) ):

				$builder = 'themify-builder';

				break;

			case ( defined( 'VCV_VERSION' ) && 'vc' === get_post_meta( $post->ID, '_vcv-page-template-type', true ) ):

				$builder = 'visual-composer';

				break;

			case class_exists( 'Classic_Editor' ):

				// Normalize old options: https://plugins.trac.wordpress.org/browser/classic-editor/trunk/classic-editor.php?rev=2084072#L254
				$default = in_array( get_option( 'classic-editor-replace' ), [ 'block', 'no-replace' ], true ) ? 'block-editor' : 'classic-editor';
				$builder = ( 'allow' === get_option( 'classic-editor-allow-users' ) ) ? get_post_meta( $post->ID, 'classic-editor-remember', true ) : $default;
				$builder = in_array( $builder, [ 'block-editor', 'classic-editor' ], true ) ? $builder : $default;
				$builder = 'wp-' . $builder;

				break;

			default:

				$builder = ( false !== strpos( $post->post_content, '<!-- wp:' ) ) ? 'wp-block-editor' : null;

		}

		return $builder;

	}

	/**
	 * Add the RUM code to the footer of all pages.
	 *
	 * @action wp_footer
	 * @action admin_footer
	 */
	public function print_inline_script() {

		global $wp_version;

		$env  = Plugin::get_env();
		$host = in_array( $env, [ 'dev', 'test' ], true ) ? "{$env}-secureserver.net" : 'secureserver.net';
		$uri  = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : null;

		?>
		<script>'undefined'=== typeof _trfq || (window._trfq = []);'undefined'=== typeof _trfd && (window._trfd=[]),_trfd.push({'tccl.baseHost':'<?php echo esc_js( $host ); ?>'}),_trfd.push({'ap':'wpaas'},{'server':'<?php echo esc_js( gethostname() ); ?>'},{'xid':'<?php echo absint( Plugin::xid() ); ?>'},{'wp':'<?php echo esc_js( $wp_version ); ?>'},{'php':'<?php echo esc_js( PHP_VERSION ); ?>'},{'loggedin':'<?php echo is_user_logged_in() ? 1 : 0; ?>'},{'cdn':'<?php echo CDN::is_enabled() ? 1 : 0; ?>'},{'builder':'<?php echo esc_js( self::get_page_builder() ); ?>'},{'theme':'<?php echo esc_js( sanitize_title( get_template() ) ); ?>'})</script>
		<script src='https://img1.wsimg.com/tcc/tcc_l.combined.1.0.6.min.js'></script>
		<?php

	}

	/**
	 * Return whether RUM should be enabled on the current page load.
	 *
	 * @return bool
	 */
	public static function is_enabled() {

		$rum_enabled = (bool) apply_filters( 'wpaas_rum_enabled', defined( 'GD_RUM_ENABLED' ) ? GD_RUM_ENABLED : false );
		$temp_domain = defined( 'GD_TEMP_DOMAIN' ) ? GD_TEMP_DOMAIN : null;
		$is_nocache  = (bool) filter_input( INPUT_GET, 'nocache' );
		$is_gddebug  = (bool) filter_input( INPUT_GET, 'gddebug' );

		return ( $rum_enabled && $temp_domain && ! $is_nocache && ! $is_gddebug && ! WP_DEBUG );

	}

}
