<div class="mark-new-posts-settings">
	<div class="mark-new-posts-title">
		<?php echo self::PLUGIN_NAME ?>
	</div>
	<div class="mark-new-posts-row">
		<div class="mark-new-posts-col">
			Marker placement:
		</div>
		<div class="mark-new-posts-col">
			<select id="mark-new-posts-marker-placement" class="mark-new-posts-input" disabled>
				<?php
					$option_name = 'marker_placement';
					$this->echo_option($option_name, self::MARKER_PLACEMENT_TITLE, 'Post title');
				?>
			</select>
		</div>
	</div>
	<div class="mark-new-posts-row">
		<div class="mark-new-posts-col">
			Marker type:
		</div>
		<div class="mark-new-posts-col">
			<select id="mark-new-posts-marker-type" class="mark-new-posts-input">
				<?php
					$option_name = 'marker_type';
					$this->echo_option($option_name, self::MARKER_TYPE_CIRCLE, 'Circle');
					$this->echo_option($option_name, self::MARKER_TYPE_TEXT, '"New" text');
					$this->echo_option($option_name, self::MARKER_TYPE_NONE, 'None');
				?>
			</select>
		</div>
	</div>
	<div class="mark-new-posts-buttons-set">
		<div id="mark-new-posts-save-settings-btn" class="mark-new-posts-button">Save</div>
		<div id="mark-new-posts-reset-settings-btn" class="mark-new-posts-button">Reset</div>
	</div>
	<div class="mark-new-posts-clearfix"></div>
	<div id="mark-new-posts-message" class="mark-new-posts-message"></div>
	<div class="mark-new-posts-clearfix"></div>
</div>