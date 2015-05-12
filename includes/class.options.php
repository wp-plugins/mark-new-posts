<?php
	class MarkNewPosts_Options {
		public $marker_placement;
		public $marker_type;
		public $set_read_after_opening;
	}

	class MarkNewPosts_MarkerPlacement {
		const TITLE = 0;
	}

	class MarkNewPosts_MarkerType {
		const NONE = 0;
		const CIRCLE = 1;
		const TEXT = 2;
		const IMAGE_DEFAULT = 3;
		const IMAGE_CUSTOM = 4;
	}
?>