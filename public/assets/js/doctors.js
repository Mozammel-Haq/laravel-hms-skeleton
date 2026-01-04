/*
Author       : Dreamstechnologies
Template Name: Preclinic - Bootstrap Admin Template
*/

(function () {
    "use strict";

	// Add new Scedule
    $(document).on('click', '.add-schedule-btn', function (e) {
        e.preventDefault();
    
        const newComplaint = `
            <div class="add-schedule-list row gx-3">
            <div class="col-lg-5">
                <div class="mb-3">
                    <select class="select">
                        <option>Select</option>
                        <option>Moring</option>
                        <option>Noon</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="row align-items-end gx-3">
                    <div class="col-lg-9">
                        <div class="row gx-3">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <div class="input-icon-end position-relative">  
                                        <input type="text" class="form-control timepicker" placeholder="03 : 05  AM">
                                        <span class="input-icon-addon">
                                            <i class="ti ti-clock-hour-10"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <div class="input-icon-end position-relative">  
                                        <input type="text" class="form-control timepicker" placeholder="03 : 05  AM">
                                        <span class="input-icon-addon">
                                            <i class="ti ti-clock-hour-10"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <a href="#" class="add-schedule-btn p-2 bg-light btn-icon text-dark rounded d-flex align-items-center justify-content-center me-2">
                                <i class="ti ti-plus fs-16"></i>
                            </a>
                            <a href="#" class="remove-schedule-btn p-2 bg-soft-danger btn-icon text-danger rounded d-flex align-items-center justify-content-center">
                                <i class="ti ti-trash fs-16"></i>
                            </a>
                        </div>
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

        setTimeout(function () {
            $('.timepicker');
            setTimeout(function () {
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
            }, 100);
        }, 100);

        

        // Insert before the add button row
        $(this).closest('.add-schedule-list').after(newComplaint);
    });
  
    // Remove Scedule
    $(document).on('click', '.remove-schedule-btn', function (e) {
        e.preventDefault();
        $(this).closest('.add-schedule-list').remove();
    });

    // Add Education
    $(document).on('click', '.add-education-btn', function (e) {
        e.preventDefault();
    
        const neweducation = `
            <div class="add-education-list row align-items-end">
                <div class="col-lg-11">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label">Educational Degree</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label">University</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label">From</label>
                                <div class="input-icon-end position-relative">  
                                    <input type="text" class="form-control datetimepicker" placeholder="dd/mm/yyyy">
                                    <span class="input-icon-addon">
                                        <i class="ti ti-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label">To</label>
                                <div class="input-icon-end position-relative">  
                                    <input type="text" class="form-control datetimepicker" placeholder="dd/mm/yyyy">
                                    <span class="input-icon-addon">
                                        <i class="ti ti-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-1">
                    <div class="mb-3">
                        <a href="#" class="remove-education-btn p-2 bg-soft-danger btn-icon text-danger rounded d-flex align-items-center justify-content-center">
                            <i class="ti ti-trash fs-16"></i>
                        </a>
                    </div>
                </div>
            </div>
        `;

        setTimeout(function () {
            $('.datetimepicker');
            setTimeout(function () {
                $('.datetimepicker').datetimepicker({
                    format: 'DD-MM-YYYY',
                    icons: {
                        up: "fas fa-angle-up",
                        down: "fas fa-angle-down",
                        next: 'fas fa-angle-right',
                        previous: 'fas fa-angle-left'
                    }
                });
            }, 100);
        }, 100);
        
        // Insert before the add button row
        $(this).closest('.add-education-list').before(neweducation);
    });
  
    // Remove Education
    $(document).on('click', '.remove-education-btn', function (e) {
        e.preventDefault();
        $(this).closest('.add-education-list').remove();
    });

    // Add Award
    $(document).on('click', '.add-award-btn', function (e) {
        e.preventDefault();
    
        const newaward = `
            <div class="add-award-list row align-items-end">
                <div class="col-lg-11">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">From</label>
                                <div class="input-icon-end position-relative">  
                                    <input type="text" class="form-control datetimepicker" placeholder="dd/mm/yyyy">
                                    <span class="input-icon-addon">
                                        <i class="ti ti-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-1">
                    <div class="mb-3">
                        <a href="#" class="remove-award-btn p-2 bg-soft-danger btn-icon text-danger rounded d-flex align-items-center justify-content-center">
                            <i class="ti ti-trash fs-16"></i>
                        </a>
                    </div>
                </div>
            </div>
        `;

        setTimeout(function () {
            $('.datetimepicker');
            setTimeout(function () {
                $('.datetimepicker').datetimepicker({
                    format: 'DD-MM-YYYY',
                    icons: {
                        up: "fas fa-angle-up",
                        down: "fas fa-angle-down",
                        next: 'fas fa-angle-right',
                        previous: 'fas fa-angle-left'
                    }
                });
            }, 100);
        }, 100);
    
        // Insert before the add button row
        $(this).closest('.add-award-list').before(newaward);
    });
  
    // Remove Award
    $(document).on('click', '.remove-award-btn', function (e) {
        e.preventDefault();
        $(this).closest('.add-award-list').remove();
    });

    // Add Certification
    $(document).on('click', '.add-certification-btn', function (e) {
        e.preventDefault();
    
        const newcertificate = `
            <div class="add-certification-list row align-items-end">
                <div class="col-lg-11">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">From</label>
                                <div class="input-icon-end position-relative">  
                                    <input type="text" class="form-control datetimepicker" placeholder="dd/mm/yyyy">
                                    <span class="input-icon-addon">
                                        <i class="ti ti-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-1">
                    <div class="mb-3">
                        <a href="#" class="remove-certification-btn p-2 bg-soft-danger btn-icon text-danger rounded d-flex align-items-center justify-content-center">
                            <i class="ti ti-trash fs-16"></i>
                        </a>
                    </div>
                </div>
            </div>
        `;

        setTimeout(function () {
            $('.datetimepicker');
            setTimeout(function () {
                $('.datetimepicker').datetimepicker({
                    format: 'DD-MM-YYYY',
                    icons: {
                        up: "fas fa-angle-up",
                        down: "fas fa-angle-down",
                        next: 'fas fa-angle-right',
                        previous: 'fas fa-angle-left'
                    }
                });
            }, 100);
        }, 100);
    
        // Insert before the add button row
        $(this).closest('.add-certification-list').before(newcertificate);
    });
  
    // Remove Certification
    $(document).on('click', '.remove-certification-btn', function (e) {
        e.preventDefault();
        $(this).closest('.add-certification-list').remove();
    });

})();