<?php
	class MarkNewPosts_Options {
		public $marker_placement;
		public $marker_type;
		public $set_read_after_opening;
		public $custom_image_url;
	}

	class MarkNewPosts_MarkerPlacement {
		const TITLE_BEFORE = 0;
		const TITLE_AFTER = 1;
	}

	class MarkNewPosts_MarkerType {
		const NONE = 0;
		const CIRCLE = 1;
		const TEXT = 2;
		const IMAGE_DEFAULT = 3;
		const IMAGE_CUSTOM = 4;
		const FLAG = 5;
	}
?>