<?php
/**
 *  Plugin Name: Slideshow
 * Plugin URI: http://wordpress.org/extend/plugins/slideshow-jquery-image-gallery/
 * Description: The slideshow plugin is easily deployable on your website. Add any image that has already been uploaded to add to your slideshow, add text slides, or even add a video. Options and styles are customizable for every single slideshow on your website.
 * Version: 3.2
 * Requires at least: 3.5
 * Author: StefanBoonstra
 * Author URI: http://stefanboonstra.com/
 * License: GPLv2
 * Text Domain: slideshow-jquery-image-gallery
 *
 * @package slideshow
 */

/**
 * Class SlideshowPluginMain fires up the application on plugin load and provides some
 * methods for the other classes to use like the auto-includer and the
 * base path/url returning method.
 *
 * @since 1.0.0
 * @author Stefan Boonstra
 */
class SlideshowPluginMain {

	/**
	 * Version.
	 *
	 * @var string
	 */
	public static $version = '3.2';

	/**
	 * Bootstraps the application by assigning the right functions to
	 * the right action hooks.
	 *
	 * @since 1.0.0
	 */
	public static function bootStrap() {
		self::autoInclude();

		// Initialize localization on init.
		add_action( 'init', [ __CLASS__, 'localize' ] );

		// Enqueue hooks.
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueueFrontendScripts' ] );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueueBackendScripts' ] );

		// Ajax requests.
		SlideshowPluginAJAX::init();

		// Register slideshow post type.
		SlideshowPluginPostType::init();

		// Add general settings page.
		SlideshowPluginGeneralSettings::init();

		// Initialize stylesheet builder.
		SlideshowPluginSlideshowStylesheet::init();

		// Deploy slideshow on do_action('slideshow_deploy'); hook.
		add_action( 'slideshow_deploy', [ 'SlideshowPlugin', 'deploy' ] );

		// Initialize shortcode.
		SlideshowPluginShortcode::init();

		// Register widget.
		add_action( 'widgets_init', [ 'SlideshowPluginWidget', 'registerWidget' ] );

		// Initialize plugin updater.
		SlideshowPluginInstaller::init();
	}

	/**
	 * Enqueues frontend scripts and styles.
	 *
	 * Should always be called on the wp_enqueue_scripts hook.
	 *
	 * @since 2.3.0
	 */
	public static function enqueueFrontendScripts() {
		// Enqueue slideshow script if lazy loading is enabled.
		if ( SlideshowPluginGeneralSettings::getEnableLazyLoading() ) {
			wp_enqueue_script(
				'slideshow-jquery-image-gallery-script',
				self::getPluginUrl() . '/js/min/all.frontend.min.js',
				[ 'jquery' ],
				self::$version,
				true
			);

			wp_localize_script(
				'slideshow-jquery-image-gallery-script',
				'slideshow_jquery_image_gallery_script_adminURL',
				admin_url()
			);
		}
	}

	/**
	 * Enqueues backend scripts and styles.
	 *
	 * Should always be called on the admin_enqueue_scrips hook.
	 *
	 * @since 2.2.12
	 */
	public static function enqueueBackendScripts() {
		// Function get_current_screen() should be defined, as this method is expected to fire at 'admin_enqueue_scripts'.
		if ( ! function_exists( 'get_current_screen' ) ) {
			return;
		}

		$currentScreen = get_current_screen();

		// Enqueue 3.5 uploader.
		if ( 'slideshow' === $currentScreen->post_type && function_exists( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}

		wp_enqueue_script(
			'slideshow-jquery-image-gallery-backend-script',
			self::getPluginUrl() . '/js/min/all.backend.min.js',
			[
				'jquery',
				'jquery-ui-sortable',
				'wp-color-picker',
			],
			self::$version,
			true
		);

		wp_enqueue_style(
			'slideshow-jquery-image-gallery-backend-style',
			self::getPluginUrl() . '/css/all.backend.css',
			[
				'wp-color-picker',
			],
			self::$version
		);
	}

	/**
	 * Translates the plugin
	 *
	 * @since 1.0.0
	 */
	public static function localize() {
		load_plugin_textdomain(
			'slideshow-jquery-image-gallery',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages/'
		);
	}

	/**
	 * Returns url to the base directory of this plugin.
	 *
	 * @return string pluginUrl
	 * @since 1.0.0
	 */
	public static function getPluginUrl() {
		return plugins_url( '', __FILE__ );
	}

	/**
	 * Returns path to the base directory of this plugin
	 *
	 * @return string pluginPath
	 * @since 1.0.0
	 */
	public static function getPluginPath() {
		return dirname( __FILE__ );
	}

	/**
	 * Outputs the passed view. It's good practice to pass an object like an stdClass to the $data variable, as it can
	 * be easily checked for validity in the view itself using "instanceof".
	 *
	 * @param string   $view View.
	 * @param stdClass $data (Optional, defaults to stdClass).
	 *
	 * @since 2.3.0
	 */
	public static function outputView( $view, $data = null ) {
		if ( ! ( $data instanceof stdClass ) ) {
			$data = new stdClass();
		}

		$file = self::getPluginPath() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view;

		if ( file_exists( $file ) ) {
			include $file;
		}
	}

	/**
	 * Uses self::outputView to render the passed view. Returns the rendered view instead of outputting it.
	 *
	 * @param string   $view View.
	 * @param stdClass $data (Optional, defaults to null).
	 *
	 * @return string
	 * @since 2.3.0
	 */
	public static function getView( $view, $data = null ) {
		ob_start();
		self::outputView( $view, $data );

		return ob_get_clean();
	}

	/**
	 * This function will load classes automatically on-call.
	 *
	 * @since 1.0.0
	 */
	public static function autoInclude() {
		if ( ! function_exists( 'spl_autoload_register' ) ) {
			return;
		}

		/**
		 * Autoloader.
		 *
		 * @param string $name Name.
		 */
		function slideshowPluginAutoLoader( $name ) {
			$name = str_replace( '\\', DIRECTORY_SEPARATOR, $name );
			$file = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $name . '.php';

			if ( is_file( $file ) ) {
				require_once $file;
			}
		}

		spl_autoload_register( 'slideshowPluginAutoLoader' );
	}
}

/**
 * Activate plugin
 */
SlideShowPluginMain::bootStrap();
