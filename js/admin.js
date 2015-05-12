(function ($) {
	var MESSAGE_TIME = 3000;
	var ui;
	var messageTimeout;

	$(document).ready(function() {
		initUi();

		var initialOptions = getOptionsFromForm();

		ui.saveOptionsBtn.on('click', function() {
			var options = getOptionsFromForm();
			var data = $.extend({}, options, {
				action: 'mark_new_posts_save_options'
			});
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
			ui.setReadAfterOpening.prop('checked', initialOptions.setReadAfterOpening);
		});
	});

	var initUi = function() {
		ui = {
			markerPlacement: $('#mark-new-posts-marker-placement'),
			markerType: $('#mark-new-posts-marker-type'),
			setReadAfterOpening: $('#mark-new-posts-set-read-after-opening'),
			saveOptionsBtn: $('#mark-new-posts-save-options-btn'),
			resetOptionsBtn: $('#mark-new-posts-reset-options-btn'),
			message: $('#mark-new-posts-message')
		};
	};

	var getOptionsFromForm = function() {
		return {
			markerPlacement: ui.markerPlacement.val(),
			markerType: ui.markerType.val(),
			setReadAfterOpening: ui.setReadAfterOpening.is(':checked')
		};
	};

	var showMessage = function(success, text) {
		var CLASS_SUCCESS = 'mark-new-posts-success';
		var CLASS_ERROR = 'mark-new-posts-success';
		ui.message
			.removeClass(CLASS_SUCCESS + ' ' + CLASS_ERROR)
			.addClass(success ? CLASS_SUCCESS : CLASS_ERROR)
			.text(text)
			.show();
		clearTimeout(messageTimeout);
		messageTimeout = setTimeout(function() {
			ui.message.hide();
		}, MESSAGE_TIME);
	};

	var setFormDisabled = function(value) {
		var form =
			ui.markerType
			.add(ui.setReadAfterOpening)
			.add(ui.saveOptionsBtn)
			.add(ui.resetOptionsBtn);
		form.prop('disabled', value);
	};
})(jQuery);