/*
Author       : Dreamstechnologies
Template Name: Preclinic - Bootstrap Admin Template
*/

(function () {
    "use strict";

	// Stick Sidebar

	if ($(window).width() > 767) {
		if ($('.theiaStickySidebar').length > 0) {
			$('.theiaStickySidebar').theiaStickySidebar({
				// Settings
				additionalMarginTop: 30
			});
		}
	}

})();