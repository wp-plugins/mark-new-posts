<div class="mark-new-posts-options">
	<div class="mark-new-posts-title">
		<?php echo self::PLUGIN_NAME ?>
	</div>
	<div class="mark-new-posts-row">
		<div class="mark-new-posts-col">
			<?php _e('Marker placement', self::TEXT_DOMAIN); ?>
		</div>
		<div class="mark-new-posts-col">
			<select id="mark-new-posts-marker-placement" class="mark-new-posts-input" disabled>
				<?php
					$option = $this->options->marker_placement;
					$this->echo_option($option, MarkNewPosts_MarkerPlacement::TITLE, __('Post title', self::TEXT_DOMAIN));
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
					$this->echo_option($option, MarkNewPosts_MarkerType::IMAGE_DEFAULT, __('Image (default)', self::TEXT_DOMAIN));
					$this->echo_option($option, MarkNewPosts_MarkerType::NONE, __('None', self::TEXT_DOMAIN));
				?>
			</select>
		</div>
	</div>
	<div class="mark-new-posts-row">
		<div class="mark-new-posts-col">
			<?php _e('Mark posts as read only after opening', self::TEXT_DOMAIN); ?>
		</div>
		<div class="mark-new-posts-col">
			<input type="checkbox" id="mark-new-posts-set-read-after-opening" autocomplete="off"
				<?php if ($this->options->set_read_after_opening) { echo 'checked="checked"'; } ?>>
		</div>
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