var MarkNewPostsAdminForm = function ($, scriptOptions) {
	var MESSAGE_TIME = 3000;
	var ANIMATION_TIME = 300;
	var ui;
	var messageTimeout;

	$(document).ready(function() {
		initUi();

		initForm();

		var initialOptions = getOptionsFromForm();

		ui.saveOptionsBtn.on('click', function() {
			var options = getOptionsFromForm();
			if (!validateOptions(options)) {
				return;
			}
			var data = $.extend({}, options, {
				action: 'mark_new_posts_save_options'
			});
			clearMessage();
			setFormDisabled(true);
			$.post(ajaxurl, data, function (response) {
				var success = response.success;
				if (success) {
					initialOptions = options;
				}
				showMessage(success, response.message);
				setFormDisabled(false);
			});
		});

		ui.resetOptionsBtn.on('click', function() {
			ui.markerPlacement.val(initialOptions.markerPlacement);
			ui.markerType.val(initialOptions.markerType);
			ui.customImageUrl.val(initialOptions.customImageUrl);
			ui.setReadAfterOpening.prop('checked', initialOptions.setReadAfterOpening);
			initForm();
			clearMessage();
		});

		ui.markerType.on('change', function() {
			onMarkerTypeChange(false);
		});
	});

	var initUi = function() {
		ui = {
			markerPlacement: $('#mark-new-posts-marker-placement'),
			markerType: $('#mark-new-posts-marker-type'),
			customImageRow: $('#mark-new-posts-custom-image-row'),
			customImageUrl: $('#mark-new-posts-custom-image-url'),
			setReadAfterOpening: $('#mark-new-posts-set-read-after-opening'),
			saveOptionsBtn: $('#mark-new-posts-save-options-btn'),
			resetOptionsBtn: $('#mark-new-posts-reset-options-btn'),
			message: $('#mark-new-posts-message')
		};
	};

	var initForm = function() {
		onMarkerTypeChange(true);
	}

	var getOptionsFromForm = function() {
		return {
			markerPlacement: ui.markerPlacement.val(),
			markerType: ui.markerType.val(),
			customImageUrl: ui.customImageUrl.val().trim(),
			setReadAfterOpening: ui.setReadAfterOpening.is(':checked')
		};
	};

	var validateOptions = function(options) {
		var error = {
			field: null,
			message: null
		};
		if (options.markerType === scriptOptions.markerTypes.imageCustom && !options.customImageUrl) {
			error.field = 'customImageUrl';
			error.message = 'customImageUrlRequired';
		}
		var noError = true;
		if (error.field) {
			noError = false;
			ui[error.field].focus();
			showMessage(false, scriptOptions.messages[error.message]);
		}
		return noError;
	};

	var onMarkerTypeChange = function(first) {
		var action = ui.markerType.val() === scriptOptions.markerTypes.imageCustom ? 'show' : 'hide';
		var animationTime = first ? 0 : ANIMATION_TIME;
		ui.customImageRow[action](animationTime);
	};

	var showMessage = function(success, text) {
		var CLASS_SUCCESS = 'mark-new-posts-success';
		var CLASS_ERROR = 'mark-new-posts-error';
		ui.message
			.removeClass(CLASS_SUCCESS + ' ' + CLASS_ERROR)
			.addClass(success ? CLASS_SUCCESS : CLASS_ERROR)
			.text(text)
			.show();
		clearTimeout(messageTimeout);
		if (success) {
			messageTimeout = setTimeout(function() {
				ui.message.hide();
			}, MESSAGE_TIME);
		}
	};

	var clearMessage = function() {
		clearTimeout(messageTimeout);
		ui.message.hide();
	};

	var setFormDisabled = function(value) {
		var form =
			ui.markerPlacement
			.add(ui.markerType)
			.add(ui.customImageUrl)
			.add(ui.setReadAfterOpening)
			.add(ui.saveOptionsBtn)
			.add(ui.resetOptionsBtn);
		form.prop('disabled', value);
	};
};