/*
Author       : Dreamstechnologies
Template Name: Preclinic - Bootstrap Admin Template
*/

(function () {
    "use strict";

	// Variables declarations
	var $wrapper = $('.main-wrapper');
	var $pageWrapper = $('.page-wrapper');

	// Mobile menu sidebar overlay
	$('body').append('<div class="sidebar-overlay"></div>');

	$(document).on('click', '#mobile_btn', function() {
		$wrapper.toggleClass('slide-nav');
		$('.sidebar-overlay').toggleClass('opened');
		$('html').addClass('menu-opened');
		return false;
	});
	$(".sidebar-close").on("click", function () { 
		$wrapper.removeClass('slide-nav');
		$('.sidebar-overlay').removeClass('opened');
		$('html').removeClass('menu-opened');             
	});

	$(".sidebar-overlay").on("click", function () {
		$('html').removeClass('menu-opened');
		$(this).removeClass('opened');
		$wrapper.removeClass('slide-nav');
		$('.sidebar-overlay').removeClass('opened');
	});

	// Sidebar
	var Sidemenu = function() {
		this.$menuItem = $('.sidebar-menu a');
	};

	function init() {
		var $this = Sidemenu;
		$('.sidebar-menu a').on('click', function(e) {
			if($(this).parent().hasClass('submenu')) {
				e.preventDefault();
			}
			if(!$(this).hasClass('subdrop')) {
				$('ul', $(this).parents('ul:first')).slideUp(250);
				$('a', $(this).parents('ul:first')).removeClass('subdrop');
				$(this).next('ul').slideDown(350);
				$(this).addClass('subdrop');
			} else if($(this).hasClass('subdrop')) {
				$(this).removeClass('subdrop');
				$(this).next('ul').slideUp(350);
			}
		});
		$('.sidebar-menu ul li.submenu a.active').parents('li:last').children('a:first').addClass('active').trigger('click');
	}

		//Trial Item
		if($('.trial-item').length > 0) {
			$(".trial-item .close-icon").on("click", function () {
				$(this).closest(".trial-item").hide(); 
			});
		}
			
	
	// Sidebar Initiate
	init();
	$(document).on('mouseover', function(e) {
        e.stopPropagation();
        if ($('body').hasClass('mini-sidebar') && $('#toggle_btn').is(':visible')) {
            var targ = $(e.target).closest('.sidebar, .header-left').length;
            if (targ) {
               $('body').addClass('expand-menu');
                $('.subdrop + ul').slideDown();
            } else {
               $('body').removeClass('expand-menu');
                $('.subdrop + ul').slideUp();
            }
            return false;
        }
    });

	var selectAllItems = "#select-all";
	var checkboxItem = ".form-check.form-check-md :checkbox";
	$(selectAllItems).on('click', function(){	
		if (this.checked) {
		$(checkboxItem).each(function() {
			this.checked = true;
		});
		} else {
		$(checkboxItem).each(function() {
			this.checked = false;
		});
		}

		
	});


	// Toggle Button
	$(document).on('click', '#toggle_btn', function () {
		const $body = $('body');
		const $html = $('html');
		const isMini = $body.hasClass('mini-sidebar');
		const isFullWidth = $html.attr('data-layout') === 'full-width';
		const isHidden = $html.attr('data-layout') === 'hidden';
	
		if (isMini) {
			$body.removeClass('mini-sidebar');
			$(this).addClass('active');
			localStorage.setItem('screenModeNightTokenState', 'night');
			setTimeout(function () {
				$(".header-left").addClass("active");
			}, 100);
		} else {
			$body.addClass('mini-sidebar');
			$(this).removeClass('active');
			localStorage.removeItem('screenModeNightTokenState');
			setTimeout(function () {
				$(".header-left").removeClass("active");
			}, 100);
		}
	
		// If <html> has data-layout="full-width", apply full-width class to <body>
		if (isFullWidth) {
			$body.addClass('full-width').removeClass('mini-sidebar');
			$('.sidebar-overlay').addClass('opened');
			$(document).on('click', '.sidebar-close', function () {
				$('body').removeClass('full-width');
			});
		} else {
			$body.removeClass('full-width');
		}

		// If <html> has data-layout="hidden", apply hidden-layout class to <body>
		if (isHidden) {
			$body.toggleClass('hidden-layout');
			$body.removeClass('mini-sidebar');
			$(document).on('click', '.sidebar-close', function () {
				$('body').removeClass('full-width');
			});
		} 
	
		return false;
	});

		// Toggle Button
		$(document).on('click', '#toggle_btn2', function () {
			const $body = $('body');
			const $html = $('html');
			const isMini = $body.hasClass('mini-sidebar');
			const isFullWidth = $html.attr('data-layout') === 'full-width';
			const isHidden = $html.attr('data-layout') === 'hidden';
		
			if (isMini) {
				$body.removeClass('mini-sidebar');
				$(this).addClass('active');
				localStorage.setItem('screenModeNightTokenState', 'night');
				setTimeout(function () {
					$(".header-left").addClass("active");
				}, 100);
			} else {
				$body.addClass('mini-sidebar');
				$(this).removeClass('active');
				localStorage.removeItem('screenModeNightTokenState');
				setTimeout(function () {
					$(".header-left").removeClass("active");
				}, 100);
			}
		
			// If <html> has data-layout="full-width", apply full-width class to <body>
			if (isFullWidth) {
				$body.addClass('full-width').removeClass('mini-sidebar');
				$('.sidebar-overlay').addClass('opened');
				$(document).on('click', '.sidebar-close', function () {
					$('body').removeClass('full-width');
				});
			} else {
				$body.removeClass('full-width');
			}
	
			// If <html> has data-layout="hidden", apply hidden-layout class to <body>
			if (isHidden) {
				$body.toggleClass('hidden-layout');
				$body.removeClass('mini-sidebar');
				$(document).on('click', '.sidebar-close', function () {
					$('body').removeClass('full-width');
				});
			} 
		
			return false;
		});
	
/*	document.addEventListener("DOMContentLoaded", function () {
		const appStyle = document.getElementById("app-style");
		if (appStyle && appStyle.href.includes("rtl.min.css")) {
		  document.documentElement.setAttribute("dir", "rtl");
		}
	});*/

	// Select 2	
	if ($('.select2').length > 0) {
		$(".select2").select2();
	}

	// Select 2    
    if ($('.select').length > 0) {
        $('.select').select2({
            minimumResultsForSearch: -1,
            width: '100%'
        });
    }
	
	// Filter Close

	document.addEventListener("DOMContentLoaded", function () {
		if (document.querySelector('.filter-dropdown')) {
			const closeBtn = document.getElementById("close-filter");
			const filterDropdown = document.getElementById("filter-dropdown");
	
			if (closeBtn && filterDropdown) {
				closeBtn.addEventListener("click", function () {
					filterDropdown.classList.remove("show");
				});
			}
		}
	});	

	// Quill Editor

    if($('.editor').length > 0) {
        document.querySelectorAll('.editor').forEach((editor) => {
            new Quill(editor, {
              theme: 'snow'
            });
        });
    }

	// toggle-password
	if($('.toggle-password').length > 0) {
		$(document).on('click', '.toggle-password', function() {
			$(this).toggleClass("ti-eye-off ti-eye-slash");
			var input = $(".pass-input");
			if (input.attr("type") == "password") {
				input.attr("type", "text");
			} else {
				input.attr("type", "password");
			}
		});
	}
	if($('.toggle-passwords').length > 0) {
		$(document).on('click', '.toggle-passwords', function() {
			$(this).toggleClass("ti-eye-off ti-eye-slash");
			var input = $(".pass-inputs");
			if (input.attr("type") == "password") {
				input.attr("type", "text");
			} else {
				input.attr("type", "password");
			}
		});
	}
	if($('.toggle-passworda').length > 0) {
		$(document).on('click', '.toggle-passworda', function() {
			$(this).toggleClass("ti-eye-off ti-eye-slash");
			var input = $(".pass-inputa");
			if (input.attr("type") == "password") {
				input.attr("type", "text");
			} else {setTimeout
				input.attr("type", "password");
			}
		});
	}
	if($('.toggle-passwordb').length > 0) {
		$(document).on('click', '.toggle-passwordb', function() {
			$(this).toggleClass("ti-eye-off ti-eye-slash");
			var input = $(".pass-inputb");
			if (input.attr("type") == "password") {
				input.attr("type", "text");
			} else {setTimeout
				input.attr("type", "password");
			}
		});
	}
	if($('.toggle-passwordc').length > 0) {
		$(document).on('click', '.toggle-passwordc', function() {
			$(this).toggleClass("ti-eye-off ti-eye-slash");
			var input = $(".pass-inputc");
			if (input.attr("type") == "password") {
				input.attr("type", "text");
			} else {setTimeout
				input.attr("type", "password");
			}
		});
	}


	document.addEventListener("DOMContentLoaded", function () {
		document.addEventListener("click", function (event) {
			if (event.target.classList.contains("close-filter")) {
				const filterDropdown = event.target.closest(".dropdown-info");
				if (filterDropdown) {
					filterDropdown.classList.remove("show");
					console.log("Dropdown closed:", filterDropdown);
				}
			}
		});
	});
	
	
	// filter dropdown
	document.addEventListener("DOMContentLoaded", function () {
		if (document.querySelector('.filter-dropdown')) {
			const closeBtn = document.getElementById("close-filter");
			const filterDropdown = document.getElementById("filter-dropdown");
	
			if (closeBtn && filterDropdown) {
				closeBtn.addEventListener("click", function () {
					filterDropdown.classList.remove("show");
				});
			}
		}
	});


	// Custom Country Code Selector

	if ($('#phone').length > 0) {
		var input = document.querySelector("#phone");
		window.intlTelInput(input, {
			utilsScript: "assets/plugins/intltelinput/js/utils.js",
		});
	}

	if ($('#phone2').length > 0) {
		var input = document.querySelector("#phone2");
		window.intlTelInput(input, {
			utilsScript: "assets/plugins/intltelinput/js/utils.js",
		});
	}

	if ($('#phone3').length > 0) {
		var input = document.querySelector("#phone3");
		window.intlTelInput(input, {
			utilsScript: "assets/plugins/intltelinput/js/utils.js",
		});
	}

	// Profile upload
	document.addEventListener('DOMContentLoaded', function () {
		const profileSection = document.getElementById('profilePage');
		if (profileSection) {
		  const uploadTrigger = document.getElementById('uploadTrigger');
		  const fileInput = document.getElementById('profileUpload');
	
		  if (uploadTrigger && fileInput) {
			uploadTrigger.addEventListener('click', function () {
			  fileInput.click();
			});
		  }
		}
	  });

	document.addEventListener('DOMContentLoaded', function () {
		const profileSection = document.getElementById('profilePage');
		if (profileSection) {
		  const uploadTrigger = document.getElementById('uploadTrigger1');
		  const fileInput = document.getElementById('profileUpload1');
	
		  if (uploadTrigger && fileInput) {
			uploadTrigger.addEventListener('click', function () {
			  fileInput.click();
			});
		  }
		}
	  });

	document.addEventListener('DOMContentLoaded', function () {
		const profileSection = document.getElementById('profilePage');
		if (profileSection) {
		  const uploadTrigger = document.getElementById('uploadTrigger2');
		  const fileInput = document.getElementById('profileUpload2');
	
		  if (uploadTrigger && fileInput) {
			uploadTrigger.addEventListener('click', function () {
			  fileInput.click();
			});
		  }
		}
	  });

	document.addEventListener('DOMContentLoaded', function () {
		const profileSection = document.getElementById('profilePage');
		if (profileSection) {
		  const uploadTrigger = document.getElementById('uploadTrigger3');
		  const fileInput = document.getElementById('profileUpload3');
	
		  if (uploadTrigger && fileInput) {
			uploadTrigger.addEventListener('click', function () {
			  fileInput.click();
			});
		  }
		}
	  });

	document.addEventListener('DOMContentLoaded', function () {
		const profileSection = document.getElementById('profilePage');
		if (profileSection) {
		  const uploadTrigger = document.getElementById('uploadTrigger4');
		  const fileInput = document.getElementById('profileUpload4');
	
		  if (uploadTrigger && fileInput) {
			uploadTrigger.addEventListener('click', function () {
			  fileInput.click();
			});
		  }
		}
	  });

	document.addEventListener('DOMContentLoaded', function () {
		const profileSection = document.getElementById('profilePage');
		if (profileSection) {
		  const uploadTrigger = document.getElementById('uploadTrigger5');
		  const fileInput = document.getElementById('profileUpload5');
	
		  if (uploadTrigger && fileInput) {
			uploadTrigger.addEventListener('click', function () {
			  fileInput.click();
			});
		  }
		}
	  });

	//  Profile upload

	// Datetimepicker
	if($('.datepic').length > 0 ){
		$('.datepic').datetimepicker({
			format: 'DD-MM-YYYY',
			keepOpen: true,inline: true,
			icons: {
				up: "fas fa-angle-up",
				down: "fas fa-angle-down",
				next: 'fas fa-angle-right',
				previous: 'fas fa-angle-left'
			}
		});
	}

		// Datatable
	if($('.datatable').length > 0) {
		$('.datatable').DataTable({
			"bFilter": true,
			"sDom": 'fBtlpi',  
			"ordering": false,
			"language": {
				search: ' ',
				sLengthMenu: '_MENU_',
				searchPlaceholder: "Search",
				sLengthMenu: 'Row Per Page _MENU_ Entries',
				info: "_START_ - _END_ of _TOTAL_ items",
				paginate: {
					next: '<i class="ti ti-arrow-right"></i>',
					previous: '<i class="ti ti-arrow-left text-body"></i> '
				},
			},
			// "scrollX": true,         // Enable horizontal scrolling
			// "scrollCollapse": true,  // Adjust table size when the scroll is used
			"responsive": true,
			"autoWidth": false,
			initComplete: (settings, json)=>{
				$('.dataTables_filter').appendTo('#tableSearch');
				$('.dataTables_filter').appendTo('.search-input');
			},	
		});
	}	

	// Datetimepicker
	if($('.datetimepicker').length > 0 ){
		$('.datetimepicker').datetimepicker({
			format: 'DD-MM-YYYY',
			icons: {
				up: "fas fa-angle-up",
				down: "fas fa-angle-down",
				next: 'fas fa-angle-right',
				previous: 'fas fa-angle-left'
			}
		});
	}

	// Datetimepicker time

	if ($('.timepicker').length > 0) {
		$('.timepicker').datetimepicker({
			format: 'HH:mm A',
			icons: {
				up: "fas fa-angle-up",
				down: "fas fa-angle-down",
				next: 'fas fa-angle-right',
				previous: 'fas fa-angle-left'
			}
		});
	}

	// Date Range Picker
	if($('#reportrange').length > 0) {
		var start = moment().subtract(29, "days"),
			end = moment();

		function report_range(start, end) {
			$("#reportrange span").html(start.format("D MMM YY") + " - " + end.format("D MMM YY"))
		}
		$("#reportrange").daterangepicker({
			startDate: start,
			endDate: end,
			ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, "days"), moment().subtract(1, "days")],
				"Last 7 Days": [moment().subtract(6, "days"), moment()],
				"Last 30 Days": [moment().subtract(29, "days"), moment()],
				"This Month": [moment().startOf("month"), moment().endOf("month")],
				"Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
			}
		}, report_range), report_range(end, end);
	}

		// Date Range Picker
		if($('.reportrange').length > 0) {
			var start = moment().subtract(29, "days"),
				end = moment();
	
			function report_range(start, end) {
				$(".reportrange span").html(start.format("D MMM YY") + " - " + end.format("D MMM YY"))
			}
			$(".reportrange").daterangepicker({
				startDate: start,
				endDate: end,
				ranges: {
					'Today': [moment(), moment()],
					'Yesterday': [moment().subtract(1, "days"), moment().subtract(1, "days")],
					"Last 7 Days": [moment().subtract(6, "days"), moment()],
					"Last 30 Days": [moment().subtract(29, "days"), moment()],
					"This Month": [moment().startOf("month"), moment().endOf("month")],
					"Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
				}
			}, report_range), report_range(end, end);
		}

	if($('.bookingrange').length > 0) {
		var start = moment().subtract(6, 'days');
		var end = moment();
		function booking_range(start, end) {
			$('.bookingrange span').html(start.format('D MMM YY') + ' - ' + end.format('D MMM YY'));
		}

		$('.bookingrange').daterangepicker({
			startDate: start,
			endDate: end,
			ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'This Year': [moment().startOf('year'), moment().endOf('year')],
				'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
			}
		}, booking_range);
		booking_range(start, end);
	}


	if($('.daterange').length > 0) {
		$('.daterange').daterangepicker({
			autoUpdateInput: false,  // Prevents immediate update of input field
			ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'This Year': [moment().startOf('year'), moment().endOf('year')],
				'Next Year': [moment().add(1, 'year').startOf('year'), moment().add(1, 'year').endOf('year')]
			},
			locale: {
				cancelLabel: 'Clear'
			}
		});
		$('#daterange').on('input', function() {
			var textLength = $(this).val().length;
			$(this).css('width', (textLength + 10) + 'px'); // 10ch adds space for padding
		});

		// Event when the user selects a date
		$('.daterange').on('apply.daterangepicker', function(ev, picker) {
			$(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
		});

		// Event for clearing the selected date
		$('.daterange').on('cancel.daterangepicker', function(ev, picker) {
			$(this).val('');  // Resets to placeholder
		});
	}

	// Add new complaint input on '+' click
	$(document).on('click', '.add-complaint', function (e) {
		e.preventDefault();
	
		const newComplaint = `
		<div class="mb-3 complaint-list-item">
			<div class="input-group">
			<input type="text" class="form-control rounded" />
			<a href="#" class="remove-complaint ms-3 p-2 bg-light text-danger rounded d-flex align-items-center justify-content-center">
				<i class="ti ti-trash fs-16"></i>
			</a>
			</div>
		</div>
		`;
	
		// Insert before the add button row
		$(this).closest('.complaint-list-item').before(newComplaint);
	});
  
	// Remove complaint input on trash icon click
	$(document).on('click', '.remove-complaint', function (e) {
		e.preventDefault();
		$(this).closest('.complaint-list-item').remove();
	});

	// Add new Advices input on '+' click
	$(document).on('click', '.add-advices', function (e) {
		e.preventDefault();
	
		const newComplaint = `
			<div class="mb-3 advices-list-item">
				<label class="form-label mb-1 text-dark fs-14 fw-medium">Advice</label>
				<div class="input-group">
					<input type="text" class="form-control rounded" />
					<a href="#" class="remove-advices ms-3 p-2 bg-light text-danger rounded d-flex align-items-center justify-content-center"><i class="ti ti-trash fs-16"></i></a>
				</div>
			</div>
		`;
	
		// Insert before the add button row
		$(this).closest('.advices-list-item').before(newComplaint);
	});
  
	// Remove complaint input on trash icon click
	$(document).on('click', '.remove-advices', function (e) {
		e.preventDefault();
		$(this).closest('.advices-list-item').remove();
	});


	// Add new invest input on '+' click
	$(document).on('click', '.add-invest', function (e) {
		e.preventDefault();
	
		const newComplaint = `
			<div class="mb-3 invest-list-item">
				<label class="form-label mb-1 text-dark fs-14 fw-medium">Investigation & Procedure</label>
				<div class="input-group">
					<input type="text" class="form-control rounded" />
					<a href="#" class="remove-invest ms-3 p-2 bg-light text-danger rounded d-flex align-items-center justify-content-center"><i class="ti ti-trash fs-16"></i></a>
				</div>
			</div>
		`;
	
		// Insert before the add button row
		$(this).closest('.invest-list-item').before(newComplaint);
	});
  
	// Remove invest input on trash icon click
	$(document).on('click', '.remove-invest', function (e) {
		e.preventDefault();
		$(this).closest('.invest-list-item').remove();
	});


	// Add new Diagnosis input on '+' click
	$(document).ready(function () {
		// Ensure no duplicate bindings
		$(document).off('click', '.add-diagnosis').on('click', '.add-diagnosis', function (e) {
			e.preventDefault();
	
			const newDiagnosis = `
				<div class="row diagnosis-list-item">
					<div class="col-lg-6">
						<div class="mb-3">
							<select class="select form-control rounded">
								<option>Select</option>
								<option>Fever</option>
								<option>Headache</option>
								<option>Joint Pain</option>
								<option>Skin Rash</option>
								<option>Back Pain</option>
							</select>
						</div>
					</div> 
	
					<div class="col-lg-6">
						<div class="mb-3">
							<div class="input-group">
								<input type="text" class="form-control rounded" />
								<a href="#" class="remove-diagnosis ms-3 p-2 bg-light text-danger rounded d-flex align-items-center justify-content-center">
									<i class="ti ti-trash fs-16"></i>
								</a>
							</div>
						</div>
					</div> 
				</div>
			`;

			setTimeout(function () {
            $('.select');
            setTimeout(function () {
                $('.select').select2({
                    minimumResultsForSearch: -1,
                    width: '100%'
                });
            }, 100);
              }, 100);
	
			$('.diagnosis-list').append(newDiagnosis);
		});
	
		// Remove a diagnosis input row
		$(document).off('click', '.remove-diagnosis').on('click', '.remove-diagnosis', function (e) {
			e.preventDefault();
			$(this).closest('.diagnosis-list-item').remove();
		});
	});


	// Add new reminder input on '+' click
	$(document).ready(function () {
		// Ensure no duplicate bindings
		$(document).off('click', '.add-reminder').on('click', '.add-reminder', function (e) {
			e.preventDefault();
	
			const newDiagnosis = `
					<div class="row d-flex align-items-center mb-3 reminder-list-item">
						<div class="col-md-2">
							<h6 class="fs-14 fw-medium mb-0">Reminder </h6>
						</div>
						<div class="col-md-10 flex-grow-1">
							<div class="d-flex align-items-center justify-content-end">
								<div class="me-2">
									<select class="select me-2">
										<option selected>Email</option>
                                        <option>SMS</option>
									</select>
								</div>
								<div class="me-2">
									<select class="select me-2">
										<option>Select</option>
										<option>Welcome Email</option>
										<option selected>Appointment Reminder</option>
										<option>Appointment Confirmation</option>
										<option>Appointment Rescheduled</option>
										<option>Appointment Cancelled</option>
										<option>Test Result Notification</option>
									</select>
								</div>
								<div class="me-2">
									<select class="select me-2">
										<option>01</option>
										<option>02</option>
										<option>03</option>
										<option>05</option>
										<option>10</option>
									</select>
								</div>
								<span class="me-2">
									Days Before
								</span>
								<div class="d-flex align-items-center">
									<a href="javascript:void(0);" class="btn btn-white p-2 border rounded-2 me-2"><i class="ti ti-edit"></i></a>
									<a href="javascript:void(0);" class="btn btn-white p-2 border rounded-2 remove-reminder"><i class="ti ti-trash"></i></a>
								</div>
							</div>
						</div>
					</div>
			`;

			  setTimeout(function () {
            $('.select');
            setTimeout(function () {
                $('.select').select2({
                    minimumResultsForSearch: -1,
                    width: '100%'
                });
            }, 100);
              }, 100);
	
			$('.reminder-list').append(newDiagnosis);
		});
	
		// Remove a diagnosis input row
		$(document).off('click', '.remove-reminder').on('click', '.remove-reminder', function (e) {
			e.preventDefault();
			$(this).closest('.reminder-list-item').remove();
		});
	});


	// Add new invoice input on '+' click
	$(document).on('click', '.add-invoice', function (e) {
		e.preventDefault();
	
		const newComplaint = `
			<div class="row invoice-list-item">
				<div class="col-lg-8">
					<div class="mb-3">
						<select class="select form-control rounded">
							<option>Select</option>
							<option>General Consultation</option>
							<option>Dental Cleaning</option>
							<option>Eye Checkup</option>
							<option>Blood Test</option>
							<option>Skin Allergy Test</option>
						</select>
					</div>
				</div> <!-- end col -->

				<div class="col-lg-4">
					<div class="mb-3">
						<div class="input-group">
							<input type="text" class="form-control rounded" />
							<a href="#" class="remove-invoice ms-3 p-2 bg-light text-danger rounded d-flex align-items-center justify-content-center"><i class="ti ti-trash fs-16"></i></a>
						</div>
					</div>
				</div> <!-- start row -->
			</div>
			<!-- end row -->
		`;

		setTimeout(function () {
            $('.select');
            setTimeout(function () {
                $('.select').select2({
                    minimumResultsForSearch: -1,
                    width: '100%'
                });
            }, 100);
              }, 100);
	
		// Insert before the add button row
		$(this).closest('.invoice-list-item').before(newComplaint);
	});
	
	// Remove invest input on trash icon click
	$(document).on('click', '.remove-invoice', function (e) {
		e.preventDefault();
		$(this).closest('.invoice-list-item').remove();
	});


	// Add new medication input on '+' click
	$(document).on('click', '.add-medication', function (e) {
		e.preventDefault();
	
		const newComplaint = `
			<!-- start row -->
			<div class="row medication-list-item">
				<div class="col-lg-11">
					<!-- start row-->
					<div class="row">
						<div class="col-lg-2">
							<div class="mb-3">
								<label class="form-label mb-1 text-dark fs-14 fw-medium">Medicine Name</label>
								<input type="text" class="form-control">
							</div>
						</div> <!-- end col -->

						<div class="col-lg-2">
							<div class="mb-3">
								<label class="form-label mb-1 text-dark fs-14 fw-medium">Dosage</label>
								<div class="input-group">
									<input type="text" class="form-control">
									<span class="input-group-text bg-transparent text-dark fs-14" id="inputGroupPrepend">mg</span>
								</div>
							</div>
						</div> <!-- end col -->

						<div class="col-lg-2">
							<div class="mb-3">
								<label class="form-label mb-1 text-dark fs-14 fw-medium">Dosage</label>
								<div class="input-group">
									<input type="text" class="form-control">
									<span class="input-group-text bg-transparent text-dark fs-14" id="inputGroupPrepend">m</span>
								</div>
							</div>
						</div> <!-- end col -->

						<div class="col-lg-2">
							<div class="mb-3">
								<label class="form-label mb-1 text-dark fs-14 fw-medium">Frequency</label>
								<select class="select form-control rounded">
									<option>Select</option>
									<option>0-0-1</option>
									<option>1-0-0</option>
									<option>0-1-0</option>
								</select>
							</div>
						</div> <!-- end col -->

						<div class="col-lg-2">
							<div class="mb-3">
								<label class="form-label mb-1 text-dark fs-14 fw-medium">Timing</label>
								<select class="select form-control rounded">
									<option>Select</option>
									<option>Morning</option>
									<option>Afternoon</option> 
								</select>
							</div>
						</div> <!-- end col -->

						<div class="col-lg-2">
							<div class="mb-3">
								<label class="form-label mb-1 text-dark fs-14 fw-medium">Instruction</label>
								<div class="input-group">
									<input type="text" class="form-control">
								</div>
							</div>
						</div> <!-- end col -->
					</div>
				</div>
				<div class="col-lg-1 px-xxl-3">
					<label class="form-label mb-1 text-dark fs-14 fw-medium"></label>
					<a href="#" class="remove-medication ms-3 p-2 bg-light text-danger rounded d-flex align-items-center justify-content-center"><i class="ti ti-trash fs-16"></i></a>
				</div>
			</div>
			<!-- end row -->
		`;
	
		// Insert before the add button row
		$(this).closest('.medication-list-item').before(newComplaint);
	});
	
	// Remove invest input on trash icon click
	$(document).on('click', '.remove-medication', function (e) {
		e.preventDefault();
		$(this).closest('.medication-list-item').remove();
	});


	$(document).on('click', '.add-invoices', function (e) {
		e.preventDefault();
	
		const newInvoice = `
			<tr class="invoices-list-item">
				<td><input type="text" class="form-control" /></td>
				<td><input type="text" class="form-control" /></td>
				<td><input type="number" class="form-control" /></td>
				<td><input type="number" class="form-control" /></td>
				<td><input type="text" class="form-control" readonly /></td>
				<td><button class="btn remove-invoices btn-sm border shadow-sm p-2 d-flex align-items-center justify-content-center rounded fs-14">
					<i class="ti ti-trash"></i>
				</button></td>
			</tr>
		`;
	
		// Insert before the last row (the add button row)
		$('.invoices-list tr:last').before(newInvoice);
	});
	
	// Remove Invoices input on trash icon click
	$(document).on('click', '.remove-invoices', function (e) {
		e.preventDefault();
		$(this).closest('.invoices-list-item').remove();
	});
  
	//   Language Settings
	document.querySelectorAll('.toggle-star').forEach(function(button) {
		button.addEventListener('click', function() {
		this.classList.toggle('active');
		});
	});
	
	// Tooltip
	if($('[data-bs-toggle="tooltip"]').length > 0) {
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
			return new bootstrap.Tooltip(tooltipTriggerEl)
		})
	}

	/* Invoice Template */
	$('.invoice-template').on('click', function(){
		$('.invoice-template').removeClass('active');
		$(this).addClass('active');
	});


	// add break
	document.addEventListener("DOMContentLoaded", function () { 
		const section = document.getElementById("break-hours-section");
		if (!section) return;

		const addBreakBtn = section.querySelector(".add-break");
		const breakRow = section.querySelector(".break1");

		if (!addBreakBtn || !breakRow) return;

		addBreakBtn.addEventListener("click", function () {
			const newBreak = breakRow.cloneNode(true);

			newBreak.querySelectorAll("input").forEach(input => input.value = "");

			const label = newBreak.querySelector("p");
			if (label) label.textContent = "New Break";

			const trashBtn = newBreak.querySelector(".ti-trash");
			if (trashBtn) {
				trashBtn.addEventListener("click", function () {
					newBreak.remove();
				});
			}

			breakRow.parentNode.insertBefore(newBreak, null);
		});

		const originalTrashBtn = breakRow.querySelector(".ti-trash");
		if (originalTrashBtn) {
			originalTrashBtn.addEventListener("click", function () {
				breakRow.remove();
			});
		}
	});

	// Popover
	const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
	const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))

	// Choices
	function initChoices() {
		document.querySelectorAll('[data-choices]').forEach(item => {
			const config = {
				allowHTML: true  
			};
			const attrs = item.attributes;
	
			if (attrs['data-choices-groups']) {
				config.placeholderValue = 'This is a placeholder set in the config';
			}
			if (attrs['data-choices-search-false']) {
				config.searchEnabled = false;
			}
			if (attrs['data-choices-search-true']) {
				config.searchEnabled = true;
			}
			if (attrs['data-choices-removeItem']) {
				config.removeItemButton = true;
			}
			if (attrs['data-choices-sorting-false']) {
				config.shouldSort = false;
			}
			if (attrs['data-choices-sorting-true']) {
				config.shouldSort = true;
			}
			if (attrs['data-choices-multiple-remove']) {
				config.removeItemButton = true;
			}
			if (attrs['data-choices-limit']) {
				config.maxItemCount = parseInt(attrs['data-choices-limit'].value);
			}
			if (attrs['data-choices-editItem-true']) {
				config.editItems = true;
			}
			if (attrs['data-choices-editItem-false']) {
				config.editItems = false;
			}
			if (attrs['data-choices-text-unique-true']) {
				config.duplicateItemsAllowed = false;
			}
			if (attrs['data-choices-text-disabled-true']) {
				config.addItems = false;
			}
	
			const instance = new Choices(item, config);
	
			if (attrs['data-choices-text-disabled-true']) {
				instance.disable();
			}
		});
	}
		
	// Call it when the DOM is ready
	document.addEventListener('DOMContentLoaded', initChoices);

	// Initialize Flatpickr on elements with data-provider="flatpickr"
	document.querySelectorAll('[data-provider="flatpickr"]').forEach(el => {
		const config = {
			disableMobile: true
		};

		if (el.hasAttribute('data-date-format')) {
			config.dateFormat = el.getAttribute('data-date-format');
		}

		if (el.hasAttribute('data-enable-time')) {
			config.enableTime = true;
			config.dateFormat = config.dateFormat ? `${config.dateFormat} H:i` : 'Y-m-d H:i';
		}

		if (el.hasAttribute('data-altFormat')) {
			config.altInput = true;
			config.altFormat = el.getAttribute('data-altFormat');
		}

		if (el.hasAttribute('data-minDate')) {
			config.minDate = el.getAttribute('data-minDate');
		}

		if (el.hasAttribute('data-maxDate')) {
			config.maxDate = el.getAttribute('data-maxDate');
		}

		if (el.hasAttribute('data-default-date')) {
			const defaultDate = el.getAttribute('data-default-date');
			// Check if it's a valid date string
			if (!["true", "false", "", null].includes(defaultDate) && !isNaN(Date.parse(defaultDate))) {
				config.defaultDate = defaultDate;
			}
		}

		if (el.hasAttribute('data-multiple-date')) {
			config.mode = 'multiple';
		}

		if (el.hasAttribute('data-range-date')) {
			config.mode = 'range';
		}

		if (el.hasAttribute('data-inline-date')) {
			config.inline = true;
			const inlineDate = el.getAttribute('data-inline-date');
			if (!["true", "false", "", null].includes(inlineDate) && !isNaN(Date.parse(inlineDate))) {
				config.defaultDate = inlineDate;
			}
		}

		if (el.hasAttribute('data-disable-date')) {
			config.disable = el.getAttribute('data-disable-date').split(',');
		}

		if (el.hasAttribute('data-week-number')) {
			config.weekNumbers = true;
		}

		flatpickr(el, config);
	});

	// Time Picker
	document.querySelectorAll('[data-provider="timepickr"]').forEach(item => {
		const attrs = item.attributes;
		const config = {
			enableTime: true,
			noCalendar: true,
			dateFormat: "H:i"
		};

		if (attrs["data-time-hrs"]) {
			config.time_24hr = true;
		}

		if (attrs["data-min-time"]) {
			config.minTime = attrs["data-min-time"].value;
		}

		if (attrs["data-max-time"]) {
			config.maxTime = attrs["data-max-time"].value;
		}

		if (attrs["data-default-time"]) {
			config.defaultDate = attrs["data-default-time"].value;
		}

		if (attrs["data-time-inline"]) {
			config.inline = true;
			config.defaultDate = attrs["data-time-inline"].value;
		}

		flatpickr(item, config);
	});

	// Select2
	if (jQuery().select2) {
		$('[data-toggle="select2"]').each(function () {
			const $el = $(this);
			const options = {};

			// Placeholder
			if ($el.attr('data-placeholder')) {
				options.placeholder = $el.attr('data-placeholder');
			}

			// Allow clear
			if ($el.attr('data-allow-clear') === 'true') {
				options.allowClear = true;
			}

			// Tags input (user can enter new values)
			if ($el.attr('data-tags') === 'true') {
				options.tags = true;
			}

			// Maximum selection
			if ($el.attr('data-max-selections')) {
				options.maximumSelectionLength = parseInt($el.attr('data-max-selections'));
			}

			// AJAX (for dynamic search)
			if ($el.attr('data-ajax--url')) {
				options.ajax = {
					url: $el.attr('data-ajax--url'),
					dataType: 'json',
					delay: 250,
					data: function (params) {
						return {
							q: params.term, // search term
							page: params.page || 1
						};
					},
					processResults: function (data, params) {
						params.page = params.page || 1;
						return {
							results: data.items || [],
							pagination: {
								more: data.more
							}
						};
					},
					cache: true
				};
			}

			// Init Select2 with options
			$el.select2(options);
		});
	}

	// Select 2    
    if ($('.select').length > 0) {
        $('.select').select2({
            minimumResultsForSearch: -1,
            width: '100%'
        });
    }

	// Sticky Sidebar

	if ($(window).width() > 767) {
		if ($('.theiaStickySidebar').length > 0) {
			$('.theiaStickySidebar').theiaStickySidebar({
				// Settings
				additionalMarginTop: 30
			});
		}
	}

	// Date Range Picker
	if($('.daterangepick').length > 0) {
		var start = moment().subtract(29, "days"),
			end = moment();

		function report_range(start, end) {
			$(".daterangepick span").html(start.format("D MMM YY") + " - " + end.format("D MMM YY"))
		}
		$(".daterangepick").daterangepicker({
			startDate: start,
			endDate: end,
			ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, "days"), moment().subtract(1, "days")],
				"Last 7 Days": [moment().subtract(6, "days"), moment()],
				"Last 30 Days": [moment().subtract(29, "days"), moment()],
				"This Month": [moment().startOf("month"), moment().endOf("month")],
				"Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
			}
		}, report_range), report_range(end, end);
	}

	
})();

