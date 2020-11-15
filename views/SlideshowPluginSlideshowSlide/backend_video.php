<?php

if ( $data instanceof stdClass ) :

	$properties = $data->properties;

	$name = htmlspecialchars( $data->name );

	$videoId           = '';
	$showRelatedVideos = 'false';

	if ( isset( $properties['videoId'] ) ) {
		$videoId = htmlspecialchars( $properties['videoId'] );
	}

	if (
		isset( $properties['showRelatedVideos'] ) &&
		'true' === $properties['showRelatedVideos']
	) {
		$showRelatedVideos = 'true';
	}

	?>

	<div class="widefat sortable-slides-list-item postbox">

		<div class="handlediv" title="<?php esc_html_e( 'Click to toggle' ); ?>"><br></div>

		<div class="hndle">
			<div class="slide-icon video-slide-icon"></div>
			<div class="slide-title">
				<?php esc_html_e( 'Video slide', 'slideshow-jquery-image-gallery' ); ?>
			</div>
			<div class="clear"></div>
		</div>

		<div class="inside">

			<div class="slideshow-group">

				<div class="slideshow-label"><?php esc_html_e( 'Youtube Video ID', 'slideshow-jquery-image-gallery' ); ?></div>
				<input
						type="text" name="<?php echo $name; ?>[videoId]" value="<?php echo $videoId; ?>"
						style="width: 100%;"/>

			</div>

			<div class="slideshow-group">

				<div class="slideshow-label"><?php esc_html_e( 'Show related videos', 'slideshow-jquery-image-gallery' ); ?></div>
				<label>
					<input
							type="radio" name="<?php echo $name; ?>[showRelatedVideos]"
							value="true" <?php checked( 'true', $showRelatedVideos ); ?>><?php esc_html_e( 'Yes', 'slideshow-jquery-image-gallery' ); ?>
				</label>
				<label>
					<input
							type="radio" name="<?php echo $name; ?>[showRelatedVideos]"
							value="false" <?php checked( 'false', $showRelatedVideos ); ?>><?php esc_html_e( 'No', 'slideshow-jquery-image-gallery' ); ?>
				</label>

			</div>

			<div class="slideshow-group slideshow-delete-slide">
				<span><?php esc_html_e( 'Delete slide', 'slideshow-jquery-image-gallery' ); ?></span>
			</div>

			<input type="hidden" name="<?php echo $name; ?>[type]" value="video"/>

		</div>

	</div>
<?php endif; ?>
