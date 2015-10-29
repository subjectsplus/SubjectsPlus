function CopyClone() {

	var myCopyClone = {

		settings : {
			cloneButton : '.clone-button',
			copyButton : '.copy-button',
			cloneFavoriteButton : '.clone-favorite',
			plusletIdSelector : 'data-pluslet-id'
		},
		strings : {},
		bindUiActions : function() {

			var ps = Pluslet();

			$('body').on(
					'click',
					myCopyClone.settings.cloneButton,
					function() {

						var origin_id = $(this).parent().parent().parent()
								.attr('data-pluslet-id');
						var origin_title = $(this).parent().parent().find(
								'.box-search-label').text();

						ps.dropPluslet('', 'Clone', origin_id, origin_title);

					});

			$('body').on(
					'click',
					myCopyClone.settings.copyButton,
					function() {

						var origin_id = $(this).parent().parent().parent()
								.attr(myCopyClone.settings.plusletIdSelector);
						var origin_title = $(this).parent().parent().parent()
								.text().replace(" /Clone Copy/g", "");

						// Get the type and pass it to the dropPluset function
						
						var type = $("#pluslet-" + origin_id).attr('name');

						ps.dropPluslet(origin_id, type, origin_title);

					});

			$('body').on(
					'dblclick',
					myCopyClone.settings.cloneFavoriteButton,
					function() {

						var origin_id = $(this).attr(
								myCopyClone.settings.plusletIdSelector);
						var origin_title = $(this).html();

						ps.dropPluslet('', 'Clone', origin_id, origin_title);

					});

		},
		init : function() {
			myCopyClone.bindUiActions();
		}
	};

	return myCopyClone;
}
