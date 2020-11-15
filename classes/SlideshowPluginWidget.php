<?php
/**
 * Class SlideshowPluginWidget allows showing one of your slideshows in your widget area.
 *
 * @since 1.2.0
 * @author: Stefan Boonstra
 */
class SlideshowPluginWidget extends WP_Widget {

	/** @var string $widgetName */
	public static $widgetName = 'Slideshow';

	/**
	 * Initializes the widget
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		// Settings.
		$options = [
			'classname'   => 'SlideshowWidget',
			'description' => __( 'Enables you to show your slideshows in the widget area of your website.', 'slideshow-jquery-image-gallery' ),
		];

		// Create the widget.
		parent::__construct(
			'slideshowWidget',
			__( 'Slideshow Widget', 'slideshow-jquery-image-gallery' ),
			$options
		);
	}

	/**
	 * The widget as shown to the user.
	 *
	 * @param mixed array $args
	 * @param mixed array $instance
	 *
	 * @since 1.2.0
	 */
	public function widget( $args, $instance ) {
		// Get slideshowId.
		$slideshowId = '';
		if ( isset( $instance['slideshowId'] ) ) {
			$slideshowId = $instance['slideshowId'];
		}

		// Get title.
		$title = '';
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		}

		// Prepare slideshow for output to website.
		$output = SlideshowPlugin::prepare( $slideshowId );

		$beforeWidget = '';
		$afterWidget  = '';
		$beforeTitle  = '';
		$afterTitle   = '';
		if ( isset( $args['before_widget'] ) ) {
			$beforeWidget = $args['before_widget'];
		}

		if ( isset( $args['after_widget'] ) ) {
			$afterWidget = $args['after_widget'];
		}

		if ( isset( $args['before_title'] ) ) {
			$beforeTitle = $args['before_title'];
		}

		if ( isset( $args['after_title'] ) ) {
			$afterTitle = $args['after_title'];
		}

		// Output widget.
		echo $beforeWidget . ( ! empty( $title ) ? $beforeTitle . $title . $afterTitle : '' ) . $output . $afterWidget;
	}

	/**
	 * The form shown on the admins widget page. Here settings can be changed.
	 *
	 * @param mixed array $instance
	 *
	 * @return string
	 * @since 1.2.0
	 */
	public function form( $instance ) {
		// Defaults.
		$defaults = [
			'title'       => __( self::$widgetName, 'slideshow-jquery-image-gallery' ),
			'slideshowId' => - 1,
		];

		// Merge database settings with defaults.
		$instance = wp_parse_args( (array) $instance, $defaults );

		// Get slideshows.
		$slideshows = get_posts(
			[
				'numberposts' => - 1,
				'offset'      => 0,
				'post_type'   => SlideshowPluginPostType::$postType,
			]
		);

		$data             = new stdClass();
		$data->widget     = $this;
		$data->instance   = $instance;
		$data->slideshows = $slideshows;

		// Include form.
		SlideshowPluginMain::outputView( __CLASS__ . DIRECTORY_SEPARATOR . 'form.php', $data );
	}

	/**
	 * Updates widget's settings.
	 *
	 * @param mixed array $newInstance
	 * @param mixed array $instance
	 *
	 * @return mixed array $instance
	 * @since 1.2.0
	 */
	public function update( $newInstance, $instance ) {
		// Update title.
		if ( isset( $newInstance['title'] ) ) {
			$instance['title'] = $newInstance['title'];
		}

		// Update slideshowId.
		if ( isset( $newInstance['slideshowId'] ) &&
		     ! empty( $newInstance['slideshowId'] ) ) {
			$instance['slideshowId'] = $newInstance['slideshowId'];
		}

		// Save.
		return $instance;
	}

	/**
	 * Registers this widget (should be called upon widget_init action hook)
	 *
	 * @since 1.2.0
	 */
	public static function registerWidget() {
		register_widget( __CLASS__ );
	}
}
