<script type="text/javascript">
	var options = {
		markerTypes: {
			imageCustom: '<?php echo MarkNewPosts_MarkerType::IMAGE_CUSTOM; ?>'
		},
		messages: {
			customImageUrlRequired: '<?php _e('Please specify image URL'); ?>'
		}
	};
	MarkNewPostsAdminForm(jQuery, options);
</script>
<div class="mark-new-posts-options">
	<div class="mark-new-posts-title">
		<?php echo self::PLUGIN_NAME ?>
	</div>
	<div class="mark-new-posts-row">
		<div class="mark-new-posts-col">
			<?php _e('Marker placement', self::TEXT_DOMAIN); ?>
		</div>
		<div class="mark-new-posts-col">
			<select id="mark-new-posts-marker-placement" class="mark-new-posts-input">
				<?php
					$option = $this->options->marker_placement;
					$this->echo_option($option, MarkNewPosts_MarkerPlacement::TITLE_BEFORE, __('Before post title', self::TEXT_DOMAIN));
					$this->echo_option($option, MarkNewPosts_MarkerPlacement::TITLE_AFTER, __('After post title', self::TEXT_DOMAIN));
				?>
			</select>
		</div>
	</div>
	<div class="mark-new-posts-row">
		<div class="mark-new-posts-col">
			<?php _e('Marker type', self::TEXT_DOMAIN) ?>
		</div>
		<div class="mark-new-posts-col">
			<select id="mark-new-posts-marker-type" class="mark-new-posts-input">
				<?php
					$option = $this->options->marker_type;
					$this->echo_option($option, MarkNewPosts_MarkerType::CIRCLE, __('Circle', self::TEXT_DOMAIN));
					$this->echo_option($option, MarkNewPosts_MarkerType::TEXT, __('"New" text', self::TEXT_DOMAIN));
					$this->echo_option($option, MarkNewPosts_MarkerType::FLAG, __('Flag (&#9872;)', self::TEXT_DOMAIN));
					$this->echo_option($option, MarkNewPosts_MarkerType::IMAGE_DEFAULT, __('Image (default)', self::TEXT_DOMAIN));
					$this->echo_option($option, MarkNewPosts_MarkerType::IMAGE_CUSTOM, __('Image (custom)', self::TEXT_DOMAIN));
					$this->echo_option($option, MarkNewPosts_MarkerType::NONE, __('None', self::TEXT_DOMAIN));
				?>
			</select>
		</div>
	</div>
	<div id="mark-new-posts-custom-image-row" class="mark-new-posts-row">
		<div class="mark-new-posts-col">
			<?php _e('Image URL', self::TEXT_DOMAIN) ?>
		</div>
		<div class="mark-new-posts-col">
			<input type="text" id="mark-new-posts-custom-image-url" class="mark-new-posts-input"
				value="<?php echo $this->options->custom_image_url; ?>">
		</div>
	</div>
	<div class="mark-new-posts-row">
		<input type="checkbox" id="mark-new-posts-set-read-after-opening" autocomplete="off"
			<?php if ($this->options->set_read_after_opening) { echo 'checked="checked"'; } ?>>
		<label for="mark-new-posts-set-read-after-opening"><?php _e('Mark posts as read only after opening', self::TEXT_DOMAIN); ?></label>
	</div>
	<div class="mark-new-posts-buttons-set">
		<button id="mark-new-posts-save-options-btn" class="mark-new-posts-button mark-new-posts-button-green">
			<?php _e('Save', self::TEXT_DOMAIN); ?>
		</button>
		<button id="mark-new-posts-reset-options-btn" class="mark-new-posts-button mark-new-posts-button-blue">
			<?php _e('Reset', self::TEXT_DOMAIN); ?>
		</button>
	</div>
	<div class="mark-new-posts-clearfix"></div>
	<div id="mark-new-posts-message" class="mark-new-posts-message"></div>
	<div class="mark-new-posts-clearfix"></div>
</div>