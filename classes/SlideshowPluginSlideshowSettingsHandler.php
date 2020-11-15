<?php
/**
 * Class SlideshowPluginSlideshowSettingsHandler handles all database/settings interactions for the slideshows.
 *
 * @since 2.1.20
 * @author Stefan Boonstra
 */
class SlideshowPluginSlideshowSettingsHandler {

	/** @var string $nonceAction */
	public static $nonceAction = 'slideshow-jquery-image-gallery-nonceAction';
	/** @var string $nonceName */
	public static $nonceName = 'slideshow-jquery-image-gallery-nonceName';

	/** @var string $settingsKey */
	public static $settingsKey = 'settings';
	/** @var string $styleSettingsKey */
	public static $styleSettingsKey = 'styleSettings';
	/** @var string $slidesKey */
	public static $slidesKey = 'slides';

	/** @var array $settings Used for caching by slideshow ID */
	public static $settings = [];
	/** @var array $styleSettings Used for caching by slideshow ID */
	public static $styleSettings = [];
	/** @var array $slides Used for caching by slideshow ID */
	public static $slides = [];

	/**
	 * Returns all settings that belong to the passed post ID retrieved from
	 * database, merged with default values from getDefaults(). Does not merge
	 * if mergeDefaults is false.
	 *
	 * If all data (including field information and description) is needed,
	 * set fullDefinition to true. See getDefaults() documentation for returned
	 * values. mergeDefaults must be true for this option to have any effect.
	 *
	 * If enableCache is set to true, results are saved into local storage for
	 * more efficient use. If data was already stored, cached data will be
	 * returned, unless $enableCache is set to false. Settings will not be
	 * cached.
	 *
	 * @param int     $slideshowId
	 * @param boolean $fullDefinition (optional, defaults to false)
	 * @param boolean $enableCache    (optional, defaults to true)
	 * @param boolean $mergeDefaults  (optional, defaults to true)
	 *
	 * @return mixed $settings
	 * @since 2.1.20
	 */
	public static function getAllSettings( $slideshowId, $fullDefinition = false, $enableCache = true, $mergeDefaults = true ) {
		$settings                            = [];
		$settings[ self::$settingsKey ]      = self::getSettings( $slideshowId, $fullDefinition, $enableCache, $mergeDefaults );
		$settings[ self::$styleSettingsKey ] = self::getStyleSettings( $slideshowId, $fullDefinition, $enableCache, $mergeDefaults );
		$settings[ self::$slidesKey ]        = self::getSlides( $slideshowId, $enableCache );

		return $settings;
	}

	/**
	 * Returns settings retrieved from database.
	 *
	 * For a full description of the parameters, see getAllSettings().
	 *
	 * @param int     $slideshowId
	 * @param boolean $fullDefinition (optional, defaults to false)
	 * @param boolean $enableCache    (optional, defaults to true)
	 * @param boolean $mergeDefaults  (optional, defaults to true)
	 *
	 * @return mixed $settings
	 * @since 2.1.20
	 */
	public static function getSettings( $slideshowId, $fullDefinition = false, $enableCache = true, $mergeDefaults = true ) {
		if ( ! is_numeric( $slideshowId ) ||
		     empty( $slideshowId ) ) {
			return [];
		}

		// Set caching to false and merging defaults to true when $fullDefinition is set to true.
		if ( $fullDefinition ) {
			$enableCache   = false;
			$mergeDefaults = true;
		}

		// If no cache is set, or cache is disabled.
		if ( ! isset( self::$settings[ $slideshowId ] ) ||
		     empty( self::$settings[ $slideshowId ] ) ||
		     ! $enableCache ) {
			// Meta data.
			$settingsMeta = get_post_meta(
				$slideshowId,
				self::$settingsKey,
				true
			);

			if ( ! $settingsMeta ||
			     ! is_array( $settingsMeta ) ) {
				$settingsMeta = [];
			}

			// If the settings should be merged with the defaults as a full definition, place each setting in an array referenced by 'value'.
			if ( $fullDefinition ) {
				foreach ( $settingsMeta as $key => $value ) {
					$settingsMeta[ $key ] = [ 'value' => $value ];
				}
			}

			// Get defaults.
			$defaults = [];

			if ( $mergeDefaults ) {
				$defaults = self::getDefaultSettings( $fullDefinition );
			}

			// Merge with defaults, recursively if a the full definition is required.
			if ( $fullDefinition ) {
				$settings = array_merge_recursive(
					$defaults,
					$settingsMeta
				);
			} else {
				$settings = array_merge(
					$defaults,
					$settingsMeta
				);
			}

			// Cache if cache is enabled.
			if ( $enableCache ) {
				self::$settings[ $slideshowId ] = $settings;
			}
		} else {
			// Get cached settings.
			$settings = self::$settings[ $slideshowId ];
		}

		// Return.
		return $settings;
	}

	/**
	 * Returns style settings retrieved from database.
	 *
	 * For a full description of the parameters, see getAllSettings().
	 *
	 * @param int     $slideshowId
	 * @param boolean $fullDefinition (optional, defaults to false)
	 * @param boolean $enableCache    (optional, defaults to true)
	 * @param boolean $mergeDefaults  (optional, defaults to true)
	 *
	 * @return mixed $settings
	 * @since 2.1.20
	 */
	public static function getStyleSettings( $slideshowId, $fullDefinition = false, $enableCache = true, $mergeDefaults = true ) {
		if ( ! is_numeric( $slideshowId ) ||
		     empty( $slideshowId ) ) {
			return [];
		}

		// Set caching to false and merging defaults to true when $fullDefinition is set to true.
		if ( $fullDefinition ) {
			$enableCache   = false;
			$mergeDefaults = true;
		}

		// If no cache is set, or cache is disabled.
		if ( ! isset( self::$styleSettings[ $slideshowId ] ) ||
		     empty( self::$styleSettings[ $slideshowId ] ) ||
		     ! $enableCache ) {

			// Meta data.
			$styleSettingsMeta = get_post_meta(
				$slideshowId,
				self::$styleSettingsKey,
				true
			);

			if ( ! $styleSettingsMeta ||
			     ! is_array( $styleSettingsMeta ) ) {
				$styleSettingsMeta = [];
			}

			// If the settings should be merged with the defaults as a full definition, place each setting in an array referenced by 'value'.
			if ( $fullDefinition ) {
				foreach ( $styleSettingsMeta as $key => $value ) {
					$styleSettingsMeta[ $key ] = [ 'value' => $value ];
				}
			}

			// Get defaults.
			$defaults = [];

			if ( $mergeDefaults ) {
				$defaults = self::getDefaultStyleSettings( $fullDefinition );
			}

			// Merge with defaults, recursively if a the full definition is required.
			if ( $fullDefinition ) {
				$styleSettings = array_merge_recursive(
					$defaults,
					$styleSettingsMeta
				);
			} else {
				$styleSettings = array_merge(
					$defaults,
					$styleSettingsMeta
				);
			}

			// Cache if cache is enabled.
			if ( $enableCache ) {
				self::$styleSettings[ $slideshowId ] = $styleSettings;
			}
		} else {
			// Get cached settings.
			$styleSettings = self::$styleSettings[ $slideshowId ];
		}

		// Return.
		return $styleSettings;
	}

	/**
	 * Returns slides retrieved from database.
	 *
	 * For a full description of the parameters, see getAllSettings().
	 *
	 * @param int     $slideshowId
	 * @param boolean $enableCache (optional, defaults to true)
	 *
	 * @return mixed $settings
	 * @since 2.1.20
	 */
	public static function getSlides( $slideshowId, $enableCache = true ) {
		if ( ! is_numeric( $slideshowId ) ||
		     empty( $slideshowId ) ) {
			return [];
		}

		// If no cache is set, or cache is disabled.
		if ( ! isset( self::$slides[ $slideshowId ] ) ||
		     empty( self::$slides[ $slideshowId ] ) ||
		     ! $enableCache ) {
			// Meta data.
			$slides = get_post_meta(
				$slideshowId,
				self::$slidesKey,
				true
			);
		} else {
			// Get cached settings.
			$slides = self::$slides[ $slideshowId ];
		}

		// Sort slides by order ID.
		if ( is_array( $slides ) ) {
			ksort( $slides );
		} else {
			$slides = [];
		}

		// Return.
		return array_values( $slides );
	}

	/**
	 * Get new settings from $_POST variable and merge them with
	 * the old and default settings.
	 *
	 * @param int $postId
	 *
	 * @return int $postId
	 * @since 2.1.20
	 */
	public static function save( $postId ) {
		// Verify nonce, check if user has sufficient rights and return on auto-save.
		if (
			get_post_type( $postId ) !== SlideshowPluginPostType::$postType ||
			( ! isset( $_POST[ self::$nonceName ] ) || ! wp_verify_nonce( $_POST[ self::$nonceName ], self::$nonceAction ) ) ||
			! current_user_can( 'slideshow-jquery-image-gallery-edit-slideshows', $postId ) ||
			( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		) {
			return $postId;
		}

		// Old settings.
		$oldSettings      = self::getSettings( $postId );
		$oldStyleSettings = self::getStyleSettings( $postId );

		// Get new settings from $_POST, making sure they're arrays.
		$newPostSettings      = [];
		$newPostStyleSettings = [];
		$newPostSlides        = [];

		if (
			isset( $_POST[ self::$settingsKey ] ) &&
			is_array( $_POST[ self::$settingsKey ] )
		) {
			$newPostSettings = $_POST[ self::$settingsKey ];
		}

		if (
			isset( $_POST[ self::$styleSettingsKey ] ) &&
			is_array( $_POST[ self::$styleSettingsKey ] )
		) {
			$newPostStyleSettings = $_POST[ self::$styleSettingsKey ];
		}

		if (
			isset( $_POST[ self::$slidesKey ] ) &&
			is_array( $_POST[ self::$slidesKey ] )
		) {
			$newPostSlides = $_POST[ self::$slidesKey ];
		}

		// Merge new settings with its old values.
		$newSettings = array_merge(
			$oldSettings,
			$newPostSettings
		);

		// Merge new style settings with its old values.
		$newStyleSettings = array_merge(
			$oldStyleSettings,
			$newPostStyleSettings
		);

		// Save settings.
		update_post_meta( $postId, self::$settingsKey, $newSettings );
		update_post_meta( $postId, self::$styleSettingsKey, $newStyleSettings );
		update_post_meta( $postId, self::$slidesKey, $newPostSlides );

		// Return.
		return $postId;
	}

	/**
	 * Returns an array of all defaults. The array will be returned
	 * like this:
	 * array([settingsKey] => array([settingName] => [settingValue]))
	 *
	 * If all default data (including field information and description)
	 * is needed, set fullDefinition to true. Data in the full definition is
	 * build up as follows:
	 * array([settingsKey] => array([settingName] => array('type' => [inputType], 'value' => [value], 'default' => [default], 'description' => [description], 'options' => array([options]), 'dependsOn' => array([dependsOn], [onValue]), 'group' => [groupName])))
	 *
	 * Finally, when you require the defaults as they were programmed in,
	 * set this parameter to false. When set to true, the database will
	 * first be consulted for user-customized defaults. Defaults to true.
	 *
	 * @since 2.1.20
	 * @param mixed   $key            (optional, defaults to null, getting all keys)
	 * @param boolean $fullDefinition (optional, defaults to false)
	 * @param boolean $fromDatabase   (optional, defaults to true)
	 * @return mixed $data
	 */
	public static function getAllDefaults( $key = null, $fullDefinition = false, $fromDatabase = true ) {
		$data                            = [];
		$data[ self::$settingsKey ]      = self::getDefaultSettings( $fullDefinition, $fromDatabase );
		$data[ self::$styleSettingsKey ] = self::getDefaultStyleSettings( $fullDefinition, $fromDatabase );

		return $data;
	}

	/**
	 * Returns an array of setting defaults.
	 *
	 * For a full description of the parameters, see getAllDefaults().
	 *
	 * @param boolean $fullDefinition (optional, defaults to false)
	 * @param boolean $fromDatabase   (optional, defaults to true)
	 *
	 * @return mixed $data
	 * @since 2.1.20
	 */
	static function getDefaultSettings( $fullDefinition = false, $fromDatabase = true ) {
		// Much used data for translation.
		$yes = __( 'Yes', 'slideshow-jquery-image-gallery' );
		$no  = __( 'No', 'slideshow-jquery-image-gallery' );

		// Default values.
		$data = [
			'animation'                   => 'slide',
			'slideSpeed'                  => '1',
			'descriptionSpeed'            => '0.4',
			'intervalSpeed'               => '5',
			'slidesPerView'               => '1',
			'maxWidth'                    => '0',
			'aspectRatio'                 => '3:1',
			'height'                      => '200',
			'imageBehaviour'              => 'natural',
			'showDescription'             => 'true',
			'hideDescription'             => 'true',
			'preserveSlideshowDimensions' => 'false',
			'enableResponsiveness'        => 'true',
			'play'                        => 'true',
			'loop'                        => 'true',
			'pauseOnHover'                => 'true',
			'controllable'                => 'true',
			'hideNavigationButtons'       => 'false',
			'showPagination'              => 'true',
			'hidePagination'              => 'true',
			'controlPanel'                => 'false',
			'hideControlPanel'            => 'true',
			'waitUntilLoaded'             => 'true',
			'showLoadingIcon'             => 'true',
			'random'                      => 'false',
			'avoidFilter'                 => 'true',
		];

		// Read defaults from database and merge with $data, when $fromDatabase is set to true.
		if ( $fromDatabase ) {
			$customData = get_option( SlideshowPluginGeneralSettings::$defaultSettings, [] );
			$data       = array_merge(
				$data,
				$customData
			);
		}

		// Full definition.
		if ( $fullDefinition ) {
			$descriptions = [
				'animation'                   => __( 'Animation used for transition between slides', 'slideshow-jquery-image-gallery' ),
				'slideSpeed'                  => __( 'Number of seconds the slide takes to slide in', 'slideshow-jquery-image-gallery' ),
				'descriptionSpeed'            => __( 'Number of seconds the description takes to slide in', 'slideshow-jquery-image-gallery' ),
				'intervalSpeed'               => __( 'Seconds between changing slides', 'slideshow-jquery-image-gallery' ),
				'slidesPerView'               => __( 'Number of slides to fit into one slide', 'slideshow-jquery-image-gallery' ),
				'maxWidth'                    => __( 'Maximum width. When maximum width is 0, maximum width is ignored', 'slideshow-jquery-image-gallery' ),
				'aspectRatio'                 => sprintf( '<a href="' . str_replace( '%', '%%', __( 'http://en.wikipedia.org/wiki/Aspect_ratio_(image)', 'slideshow-jquery-image-gallery' ) ) . '" title="' . __( 'More info', 'slideshow-jquery-image-gallery' ) . '" target="_blank">' . __( 'Proportional relationship%s between slideshow\'s width and height (width:height)', 'slideshow-jquery-image-gallery' ), '</a>' ),
				'height'                      => __( 'Slideshow\'s height', 'slideshow-jquery-image-gallery' ),
				'imageBehaviour'              => __( 'Image behaviour', 'slideshow-jquery-image-gallery' ),
				'preserveSlideshowDimensions' => __( 'Shrink slideshow\'s height when width shrinks', 'slideshow-jquery-image-gallery' ),
				'enableResponsiveness'        => __( 'Enable responsiveness (Shrink slideshow\'s width when page\'s width shrinks)', 'slideshow-jquery-image-gallery' ),
				'showDescription'             => __( 'Show title and description', 'slideshow-jquery-image-gallery' ),
				'hideDescription'             => __( 'Hide description box, pop up when mouse hovers over', 'slideshow-jquery-image-gallery' ),
				'play'                        => __( 'Automatically slide to the next slide', 'slideshow-jquery-image-gallery' ),
				'loop'                        => __( 'Return to the beginning of the slideshow after last slide', 'slideshow-jquery-image-gallery' ),
				'pauseOnHover'                => __( 'Pause slideshow when mouse hovers over', 'slideshow-jquery-image-gallery' ),
				'controllable'                => __( 'Activate navigation buttons', 'slideshow-jquery-image-gallery' ),
				'hideNavigationButtons'       => __( 'Hide navigation buttons, show when mouse hovers over', 'slideshow-jquery-image-gallery' ),
				'showPagination'              => __( 'Activate pagination', 'slideshow-jquery-image-gallery' ),
				'hidePagination'              => __( 'Hide pagination, show when mouse hovers over', 'slideshow-jquery-image-gallery' ),
				'controlPanel'                => __( 'Activate control panel (play and pause button)', 'slideshow-jquery-image-gallery' ),
				'hideControlPanel'            => __( 'Hide control panel, show when mouse hovers over', 'slideshow-jquery-image-gallery' ),
				'waitUntilLoaded'             => __( 'Wait until the next slide has loaded before showing it', 'slideshow-jquery-image-gallery' ),
				'showLoadingIcon'             => __( 'Show a loading icon until the first slide appears', 'slideshow-jquery-image-gallery' ),
				'random'                      => __( 'Randomize slides', 'slideshow-jquery-image-gallery' ),
				'avoidFilter'                 => sprintf( __( 'Avoid content filter (disable if \'%s\' is shown)', 'slideshow-jquery-image-gallery' ), SlideshowPluginShortcode::$bookmark ),
			];

			$data = [
				'animation'                   => [
					'type'        => 'select',
					'default'     => $data['animation'],
					'description' => $descriptions['animation'],
					'group'       => __( 'Animation', 'slideshow-jquery-image-gallery' ),
					'options'     => [
						'slide'      => __( 'Slide Left', 'slideshow-jquery-image-gallery' ),
						'slideRight' => __( 'Slide Right', 'slideshow-jquery-image-gallery' ),
						'slideUp'    => __( 'Slide Up', 'slideshow-jquery-image-gallery' ),
						'slideDown'  => __( 'Slide Down', 'slideshow-jquery-image-gallery' ),
						'crossFade'  => __( 'Cross Fade', 'slideshow-jquery-image-gallery' ),
						'directFade' => __( 'Direct Fade', 'slideshow-jquery-image-gallery' ),
						'fade'       => __( 'Fade', 'slideshow-jquery-image-gallery' ),
						'random'     => __( 'Random Animation', 'slideshow-jquery-image-gallery' ),
					],
				],
				'slideSpeed'                  => [
					'type'        => 'text',
					'default'     => $data['slideSpeed'],
					'description' => $descriptions['slideSpeed'],
					'group'       => __( 'Animation', 'slideshow-jquery-image-gallery' ),
				],
				'descriptionSpeed'            => [
					'type'        => 'text',
					'default'     => $data['descriptionSpeed'],
					'description' => $descriptions['descriptionSpeed'],
					'group'       => __( 'Animation', 'slideshow-jquery-image-gallery' ),
				],
				'intervalSpeed'               => [
					'type'        => 'text',
					'default'     => $data['intervalSpeed'],
					'description' => $descriptions['intervalSpeed'],
					'group'       => __( 'Animation', 'slideshow-jquery-image-gallery' ),
				],
				'slidesPerView'               => [
					'type'        => 'text',
					'default'     => $data['slidesPerView'],
					'description' => $descriptions['slidesPerView'],
					'group'       => __( 'Display', 'slideshow-jquery-image-gallery' ),
				],
				'maxWidth'                    => [
					'type'        => 'text',
					'default'     => $data['maxWidth'],
					'description' => $descriptions['maxWidth'],
					'group'       => __( 'Display', 'slideshow-jquery-image-gallery' ),
				],
				'aspectRatio'                 => [
					'type'        => 'text',
					'default'     => $data['aspectRatio'],
					'description' => $descriptions['aspectRatio'],
					'group'       => __( 'Display', 'slideshow-jquery-image-gallery' ),
					'dependsOn'   => [ 'settings[preserveSlideshowDimensions]', 'true' ],
				],
				'height'                      => [
					'type'        => 'text',
					'default'     => $data['height'],
					'description' => $descriptions['height'],
					'group'       => __( 'Display', 'slideshow-jquery-image-gallery' ),
					'dependsOn'   => [ 'settings[preserveSlideshowDimensions]', 'false' ],
				],
				'imageBehaviour'              => [
					'type'        => 'select',
					'default'     => $data['imageBehaviour'],
					'description' => $descriptions['imageBehaviour'],
					'group'       => __( 'Display', 'slideshow-jquery-image-gallery' ),
					'options'     => [
						'natural' => __( 'Natural and centered', 'slideshow-jquery-image-gallery' ),
						'crop'    => __( 'Crop to fit', 'slideshow-jquery-image-gallery' ),
						'stretch' => __( 'Stretch to fit', 'slideshow-jquery-image-gallery' ),
					],
				],
				'preserveSlideshowDimensions' => [
					'type'        => 'radio',
					'default'     => $data['preserveSlideshowDimensions'],
					'description' => $descriptions['preserveSlideshowDimensions'],
					'group'       => __( 'Display', 'slideshow-jquery-image-gallery' ),
					'options'     => [
						'true'  => $yes,
						'false' => $no,
					],
					'dependsOn'   => [ 'settings[enableResponsiveness]', 'true' ],
				],
				'enableResponsiveness'        => [
					'type'        => 'radio',
					'default'     => $data['enableResponsiveness'],
					'description' => $descriptions['enableResponsiveness'],
					'group'       => __( 'Display', 'slideshow-jquery-image-gallery' ),
					'options'     => [
						'true'  => $yes,
						'false' => $no,
					],
				],
				'showDescription'             => [
					'type'        => 'radio',
					'default'     => $data['showDescription'],
					'description' => $descriptions['showDescription'],
					'group'       => __( 'Display', 'slideshow-jquery-image-gallery' ),
					'options'     => [
						'true'  => $yes,
						'false' => $no,
					],
				],
				'hideDescription'             => [
					'type'        => 'radio',
					'default'     => $data['hideDescription'],
					'description' => $descriptions['hideDescription'],
					'group'       => __( 'Display', 'slideshow-jquery-image-gallery' ),
					'options'     => [
						'true'  => $yes,
						'false' => $no,
					],
					'dependsOn'   => [ 'settings[showDescription]', 'true' ],
				],
				'play'                        => [
					'type'        => 'radio',
					'default'     => $data['play'],
					'description' => $descriptions['play'],
					'group'       => __( 'Control', 'slideshow-jquery-image-gallery' ),
					'options'     => [
						'true'  => $yes,
						'false' => $no,
					],
				],
				'loop'                        => [
					'type'        => 'radio',
					'default'     => $data['loop'],
					'description' => $descriptions['loop'],
					'group'       => __( 'Control', 'slideshow-jquery-image-gallery' ),
					'options'     => [
						'true'  => $yes,
						'false' => $no,
					],
				],
				'pauseOnHover'                => [
					'type'        => 'radio',
					'default'     => $data['loop'],
					'description' => $descriptions['pauseOnHover'],
					'group'       => __( 'Control', 'slideshow-jquery-image-gallery' ),
					'options'     => [
						'true'  => $yes,
						'false' => $no,
					],
				],
				'controllable'                => [
					'type'        => 'radio',
					'default'     => $data['controllable'],
					'description' => $descriptions['controllable'],
					'group'       => __( 'Control', 'slideshow-jquery-image-gallery' ),
					'options'     => [
						'true'  => $yes,
						'false' => $no,
					],
				],
				'hideNavigationButtons'       => [
					'type'        => 'radio',
					'default'     => $data['hideNavigationButtons'],
					'description' => $descriptions['hideNavigationButtons'],
					'group'       => __( 'Control', 'slideshow-jquery-image-gallery' ),
					'options'     => [
						'true'  => $yes,
						'false' => $no,
					],
					'dependsOn'   => [ 'settings[controllable]', 'true' ],
				],
				'showPagination'              => [
					'type'        => 'radio',
					'default'     => $data['showPagination'],
					'description' => $descriptions['showPagination'],
					'group'       => __( 'Control', 'slideshow-jquery-image-gallery' ),
					'options'     => [
						'true'  => $yes,
						'false' => $no,
					],
				],
				'hidePagination'              => [
					'type'        => 'radio',
					'default'     => $data['hidePagination'],
					'description' => $descriptions['hidePagination'],
					'group'       => __( 'Control', 'slideshow-jquery-image-gallery' ),
					'options'     => [
						'true'  => $yes,
						'false' => $no,
					],
					'dependsOn'   => [ 'settings[showPagination]', 'true' ],
				],
				'controlPanel'                => [
					'type'        => 'radio',
					'default'     => $data['controlPanel'],
					'description' => $descriptions['controlPanel'],
					'group'       => __( 'Control', 'slideshow-jquery-image-gallery' ),
					'options'     => [
						'true'  => $yes,
						'false' => $no,
					],
				],
				'hideControlPanel'            => [
					'type'        => 'radio',
					'default'     => $data['hideControlPanel'],
					'description' => $descriptions['hideControlPanel'],
					'group'       => __( 'Control', 'slideshow-jquery-image-gallery' ),
					'options'     => [
						'true'  => $yes,
						'false' => $no,
					],
					'dependsOn'   => [ 'settings[controlPanel]', 'true' ],
				],
				'waitUntilLoaded'             => [
					'type'        => 'radio',
					'default'     => $data['waitUntilLoaded'],
					'description' => $descriptions['waitUntilLoaded'],
					'group'       => __( 'Miscellaneous', 'slideshow-jquery-image-gallery' ),
					'options'     => [
						'true'  => $yes,
						'false' => $no,
					],
				],
				'showLoadingIcon'             => [
					'type'        => 'radio',
					'default'     => $data['showLoadingIcon'],
					'description' => $descriptions['showLoadingIcon'],
					'group'       => __( 'Miscellaneous', 'slideshow-jquery-image-gallery' ),
					'options'     => [
						'true'  => $yes,
						'false' => $no,
					],
					'dependsOn'   => [ 'settings[waitUntilLoaded]', 'true' ],
				],
				'random'                      => [
					'type'        => 'radio',
					'default'     => $data['random'],
					'description' => $descriptions['random'],
					'group'       => __( 'Miscellaneous', 'slideshow-jquery-image-gallery' ),
					'options'     => [
						'true'  => $yes,
						'false' => $no,
					],
				],
				'avoidFilter'                 => [
					'type'        => 'radio',
					'default'     => $data['avoidFilter'],
					'description' => $descriptions['avoidFilter'],
					'group'       => __( 'Miscellaneous', 'slideshow-jquery-image-gallery' ),
					'options'     => [
						'true'  => $yes,
						'false' => $no,
					],
				],
			];
		}

		// Return.
		return $data;
	}

	/**
	 * Returns an array of style setting defaults.
	 *
	 * For a full description of the parameters, see getAllDefaults().
	 *
	 * @param boolean $fullDefinition (optional, defaults to false)
	 * @param boolean $fromDatabase   (optional, defaults to true)
	 *
	 * @return mixed $data
	 * @since 2.1.20
	 */
	public static function getDefaultStyleSettings( $fullDefinition = false, $fromDatabase = true ) {
		// Default style settings.
		$data = [
			'style' => 'style-light.css',
		];

		// Read defaults from database and merge with $data, when $fromDatabase is set to true.
		if ( $fromDatabase ) {
			$customData = get_option( SlideshowPluginGeneralSettings::$defaultStyleSettings, [] );
			$data       = array_merge(
				$data,
				$customData
			);
		}

		// Full definition.
		if ( $fullDefinition ) {
			$data = [
				'style' => [
					'type'        => 'select',
					'default'     => $data['style'],
					'description' => __( 'The style used for this slideshow', 'slideshow-jquery-image-gallery' ),
					'options'     => SlideshowPluginGeneralSettings::getStylesheets(),
				],
			];
		}

		// Return.
		return $data;
	}

	/**
	 * Returns an HTML inputField of the input setting.
	 *
	 * This function expects the setting to be in the 'fullDefinition'
	 * format that the getDefaults() and getSettings() methods both
	 * return.
	 *
	 * @param string $settingsKey
	 * @param string $settingsName
	 * @param mixed  $settings
	 * @param bool   $hideDependentValues (optional, defaults to true)
	 *
	 * @return mixed $inputField
	 * @since 2.1.20
	 */
	public static function getInputField( $settingsKey, $settingsName, $settings, $hideDependentValues = true ) {
		if ( ! is_array( $settings ) ||
		     empty( $settings ) ||
		     empty( $settingsName ) ) {
			return null;
		}

		$inputField   = '';
		$name         = $settingsKey . '[' . $settingsName . ']';
		$displayValue = ( ! isset( $settings['value'] ) || ( empty( $settings['value'] ) && ! is_numeric( $settings['value'] ) ) ? $settings['default'] : $settings['value'] );
		$class        = ( ( isset( $settings['dependsOn'] ) && $hideDependentValues ) ? 'depends-on-field-value ' . $settings['dependsOn'][0] . ' ' . $settings['dependsOn'][1] . ' ' : '' ) . $settingsKey . '-' . $settingsName;

		switch ( $settings['type'] ) {
			case 'text':
				$inputField .= '<input
					type="text"
					name="' . $name . '"
					class="' . $class . '"
					value="' . $displayValue . '"
				/>';

				break;

			case 'textarea':
				$inputField .= '<textarea
					name="' . $name . '"
					class="' . $class . '"
					rows="20"
					cols="60"
				>' . $displayValue . '</textarea>';

				break;

			case 'select':
				$inputField .= '<select name="' . $name . '" class="' . $class . '">';

				foreach ( $settings['options'] as $optionKey => $optionValue ) {
					$inputField .= '<option value="' . $optionKey . '" ' . selected( $displayValue, $optionKey, false ) . '>
						' . $optionValue . '
					</option>';
				}

				$inputField .= '</select>';

				break;

			case 'radio':
				foreach ( $settings['options'] as $radioKey => $radioValue ) {
					$inputField .= '<label style="padding-right: 10px;"><input
						type="radio"
						name="' . $name . '"
						class="' . $class . '"
						value="' . $radioKey . '" ' .
					               checked( $displayValue, $radioKey, false ) .
					               ' />' . $radioValue . '</label>';
				}

				break;

			default:
				$inputField = null;

				break;
		};

		// Return.
		return $inputField;
	}
}
