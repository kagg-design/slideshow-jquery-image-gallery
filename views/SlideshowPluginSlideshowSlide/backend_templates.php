<?php if ( $data instanceof stdClass ) : ?>
	<div class="text-slide-template" style="display: none;">
		<div class="widefat sortable-slides-list-item postbox">

			<div class="handlediv" title="<?php esc_html_e( 'Click to toggle' ); ?>"><br></div>

			<div class="hndle">
				<div class="slide-icon text-slide-icon"></div>
				<div class="slide-title">
					<?php esc_html_e( 'Text slide', 'slideshow-jquery-image-gallery' ); ?>
				</div>
				<div class="clear"></div>
			</div>

			<div class="inside">

				<div class="slideshow-group">

					<div class="slideshow-left slideshow-label"><?php esc_html_e( 'Title', 'slideshow-jquery-image-gallery' ); ?></div>
					<div class="slideshow-right">
						<select class="titleElementTagID">
							<?php foreach ( SlideshowPluginSlideInserter::getElementTags() as $elementTagID => $elementTag ) : ?>
								<option value="<?php echo $elementTagID; ?>"><?php echo $elementTag; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="clear"></div>
					<input type="text" class="title" style="width: 100%;" />

				</div>

				<div class="slideshow-group">

					<div class="slideshow-left slideshow-label"><?php esc_html_e( 'Description', 'slideshow-jquery-image-gallery' ); ?></div>
					<div class="slideshow-right">
						<select class="descriptionElementTagID">
							<?php foreach ( SlideshowPluginSlideInserter::getElementTags() as $elementTagID => $elementTag ) : ?>
								<option value="<?php echo $elementTagID; ?>"><?php echo $elementTag; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div clear="clear"></div>
					<textarea class="description" cols="" rows="7" style="width: 100%;"></textarea>

				</div>

				<div class="slideshow-group">

					<div class="slideshow-label"><?php esc_html_e( 'Text color', 'slideshow-jquery-image-gallery' ); ?></div>
					<input type="text" class="textColor" value="000000" />

					<div class="slideshow-label"><?php esc_html_e( 'Background color', 'slideshow-jquery-image-gallery' ); ?></div>
					<input type="text" class="color" value="FFFFFF" />
					<div style="font-style: italic;"><?php esc_html_e( '(Leave empty for a transparent background)', 'slideshow-jquery-image-gallery' ); ?></div>

				</div>

				<div class="slideshow-group">

					<div class="slideshow-label"><?php esc_html_e( 'URL', 'slideshow-jquery-image-gallery' ); ?></div>
					<input type="text" class="url" value="" style="width: 100%;" />

					<div class="slideshow-label slideshow-left"><?php esc_html_e( 'Open URL in', 'slideshow-jquery-image-gallery' ); ?></div>
					<select class="urlTarget slideshow-right">
						<option value="_self"><?php esc_html_e( 'Same window', 'slideshow-jquery-image-gallery' ); ?></option>
						<option value="_blank"><?php esc_html_e( 'New window', 'slideshow-jquery-image-gallery' ); ?></option>
					</select>
					<div class="clear"></div>

					<div class="slideshow-label slideshow-left"><?php esc_html_e( 'Don\'t let search engines follow link', 'slideshow-jquery-image-gallery' ); ?></div>
					<input type="checkbox" class="noFollow slideshow-right" />
					<div class="clear"></div>

				</div>

				<div class="slideshow-group slideshow-delete-slide">
					<span><?php esc_html_e( 'Delete slide', 'slideshow-jquery-image-gallery' ); ?></span>
				</div>

				<input type="hidden" class="type" value="text" />

			</div>

		</div>
	</div>

	<div class="video-slide-template" style="display: none;">
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
					<input type="text" class="videoId" style="width: 100%;" />

				</div>

				<div class="slideshow-group">

					<div class="slideshow-label"><?php esc_html_e( 'Show related videos', 'slideshow-jquery-image-gallery' ); ?></div>
					<label><input type="radio" class="showRelatedVideos" value="true"><?php esc_html_e( 'Yes', 'slideshow-jquery-image-gallery' ); ?></label>
					<label><input type="radio" class="showRelatedVideos" value="false" checked="checked""><?php esc_html_e( 'No', 'slideshow-jquery-image-gallery' ); ?></label>

				</div>

				<div class="slideshow-group slideshow-delete-slide">
					<span><?php esc_html_e( 'Delete slide', 'slideshow-jquery-image-gallery' ); ?></span>
				</div>

				<input type="hidden" class="type" value="video" />

			</div>

		</div>
	</div>

	<div class="image-slide-template" style="display: none;">
		<div class="widefat sortable-slides-list-item postbox">

			<div class="handlediv" title="<?php esc_html_e( 'Click to toggle' ); ?>"><br></div>

			<div class="hndle">
				<div class="slide-icon image-slide-icon"></div>
				<div class="slide-title">
					<?php esc_html_e( 'Image slide', 'slideshow-jquery-image-gallery' ); ?>
				</div>
				<div class="clear"></div>
			</div>

			<div class="inside">

				<div class="slideshow-group">

					<img width="80" height="60" src="" class="attachment attachment-80x60" alt="" title="" style="float: none; margin: 0; padding: 0;" />

				</div>

				<div class="slideshow-group">

					<div class="slideshow-left slideshow-label"><?php esc_html_e( 'Title', 'slideshow-jquery-image-gallery' ); ?></div>
					<div class="slideshow-right">
						<select class="titleElementTagID">
							<?php foreach ( SlideshowPluginSlideInserter::getElementTags() as $elementTagID => $elementTag ) : ?>
								<option value="<?php echo $elementTagID; ?>"><?php echo $elementTag; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="clear"></div>
					<input type="text" class="title" style="width: 100%;" />

				</div>

				<div class="slideshow-group">

					<div class="slideshow-left slideshow-label"><?php esc_html_e( 'Description', 'slideshow-jquery-image-gallery' ); ?></div>
					<div class="slideshow-right">
						<select class="descriptionElementTagID">
							<?php foreach ( SlideshowPluginSlideInserter::getElementTags() as $elementTagID => $elementTag ) : ?>
								<option value="<?php echo $elementTagID; ?>"><?php echo $elementTag; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="clear"></div>
					<textarea class="description" rows="3" cols="" style="width: 100%;"></textarea><br />

				</div>

				<div class="slideshow-group">

					<div class="slideshow-label"><?php esc_html_e( 'URL', 'slideshow-jquery-image-gallery' ); ?></div>
					<input type="text" class="url" value="" style="width: 100%;" /><br />

					<div class="slideshow-label slideshow-left"><?php esc_html_e( 'Open URL in', 'slideshow-jquery-image-gallery' ); ?></div>
					<select class="urlTarget slideshow-right">
						<option value="_self"><?php esc_html_e( 'Same window', 'slideshow-jquery-image-gallery' ); ?></option>
						<option value="_blank"><?php esc_html_e( 'New window', 'slideshow-jquery-image-gallery' ); ?></option>
					</select>
					<div class="clear"></div>

					<div class="slideshow-label slideshow-left"><?php esc_html_e( 'Don\'t let search engines follow link', 'slideshow-jquery-image-gallery' ); ?></div>
					<input type="checkbox" class="noFollow slideshow-right" />

				</div>

				<div class="slideshow-group">

					<div class="slideshow-label"><?php esc_html_e( 'Alternative text', 'slideshow-jquery-image-gallery' ); ?></div>
					<input type="text" class="alternativeText" style="width: 100%;" />

				</div>

				<div class="slideshow-group slideshow-delete-slide">
					<span><?php esc_html_e( 'Delete slide', 'slideshow-jquery-image-gallery' ); ?></span>
				</div>

				<input type="hidden" class="type" value="attachment" />
				<input type="hidden" class="postId" value="" />

			</div>

		</div>
	</div>
<?php endif; ?>
