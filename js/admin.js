(function ($) {
	var MESSAGE_TIME = 3000;
	var ui;
	var messageTimeout;

	$(document).ready(function() {
		initUi();

		var initialSettings = getSettingsFromForm();

		ui.saveSettingsBtn.on('click', function() {
			var settings = getSettingsFromForm();
			var data = $.extend({}, settings, {
				action: 'mark_new_posts_save_settings'
			});
			$.post(ajaxurl, data, function (response) {
				var success = response.success;
				if (success) {
					initialSettings = settings;
				}
				showMessage(success, response.message);
			});
		});

		ui.resetSettingsBtn.on('click', function() {
			ui.markerPlacement.val(initialSettings.markerPlacement);
			ui.markerType.val(initialSettings.markerType);
		});
	});

	var initUi = function() {
		ui = {
			markerPlacement: $('#mark-new-posts-marker-placement'),
			markerType: $('#mark-new-posts-marker-type'),
			saveSettingsBtn: $('#mark-new-posts-save-settings-btn'),
			resetSettingsBtn: $('#mark-new-posts-reset-settings-btn'),
			message: $('#mark-new-posts-message')
		};
	};

	var getSettingsFromForm = function() {
		return {
			markerPlacement: ui.markerPlacement.val(),
			markerType: ui.markerType.val()
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
})(jQuery);