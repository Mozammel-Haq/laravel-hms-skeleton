

! function() {
	var t = sessionStorage.getItem("__THEME_CONFIG__"),
		e = document.getElementsByTagName("html")[0],
		i = {
			theme: "light",
			nav: "vertical",
			color: {
				color: "primary"
			},
			layout: {
				mode: "fluid"
			},
			topbar: {
				color: "white"
			},
			menu: {
				color: "light"
			},
			sidenav: {
				size: "default",
				user: !1
			}
		};
var config;
    var html = document.getElementsByTagName("html")[0];

    config = Object.assign({}, i);

    var attrTheme = html.getAttribute("data-bs-theme");
    config.theme = attrTheme !== null ? attrTheme : i.theme;

    var attrLayoutMode = html.getAttribute("data-layout");
    config.layout.mode = attrLayoutMode !== null ? attrLayoutMode : i.layout.mode;

    var attrColor = html.getAttribute("data-color");
    config.color.color = attrColor !== null ? attrColor : i.color.color;

    var attrTopbar = html.getAttribute("data-topbar");
    config.topbar.color = attrTopbar !== null ? attrTopbar : i.topbar.color;

    var attrSidenavSize = html.getAttribute("data-layout");
    config.sidenav.size = attrSidenavSize !== null ? attrSidenavSize : i.sidenav.size;

    var attrSidenavUser = html.getAttribute("data-sidenav-user");
    config.sidenav.user = attrSidenavUser !== null ? attrSidenavUser : i.sidenav.user;

    var attrMenuColor = html.getAttribute("data-sidebar");
    config.menu.color = attrMenuColor !== null ? attrMenuColor : i.menu.color;

    window.defaultConfig = JSON.parse(JSON.stringify(config));

    if (null !== t) {
        config = JSON.parse(t);
    }

    window.config = config;
		if ("vertical" == config.nav) {
			let t = config.sidenav.size;
			window.innerWidth <= 767 ? t = "full-width" : 767 <= window.innerWidth && window.innerWidth <= 1140 && "full-width" !== self.config.sidenav.size && "hidden" !== self.config.sidenav.size && (t = "condensed"), e.setAttribute("data-layout", t), config.sidenav.user && "true" === config.sidenav.user.toString() ? e.setAttribute("data-sidenav-user", !0) : e.removeAttribute("data-sidenav-user")
		}
		e.setAttribute("data-bs-theme", config.theme), e.setAttribute("data-sidebar", config.menu.color), e.setAttribute("data-topbar", config.topbar.color), e.setAttribute("data-color", config.color.color);
}();
class ThemeCustomizer {
	constructor() {
		this.html = document.getElementsByTagName("html")[0], this.config = {}, this.defaultConfig = window.config
	}
	initConfig() {
		this.defaultConfig = JSON.parse(JSON.stringify(window.defaultConfig)), this.config = JSON.parse(JSON.stringify(window.config)), this.setSwitchFromConfig()
	}
	changeMenuColor(e) {
		this.config.menu.color = e, this.html.setAttribute("data-sidebar", e), this.setSwitchFromConfig()
	}
	changeLeftbarSize(e, t = !0) {
		this.html.setAttribute("data-layout", e);
		
		if (document.body) {
			if (e === "mini") {
			document.body.classList.add("mini-sidebar");
			} else {
			document.body.classList.remove("mini-sidebar");
			}
		}

		t && (this.config.sidenav.size = e, this.setSwitchFromConfig());
	}	
	changeThemeColor(e) {
		this.config.color.color = e, this.html.setAttribute("data-color", e), this.setSwitchFromConfig()
	}
	changeLayoutColor(e) {
		this.config.theme = e, this.html.setAttribute("data-bs-theme", e), this.setSwitchFromConfig()
	}
	changeTopbarColor(e) {
		this.config.topbar.color = e, this.html.setAttribute("data-topbar", e), this.setSwitchFromConfig()
	}
	resetTheme() {
		this.config = JSON.parse(JSON.stringify(window.defaultConfig)), this.changeMenuColor(this.config.menu.color), this.changeLeftbarSize(this.config.sidenav.size), this.changeLayoutColor(this.config.theme), this.changeTopbarColor(this.config.topbar.color), this.changeThemeColor(this.config.color.color), this._adjustLayout()
	}
	initSwitchListener() {
		var a = this,
			e = (document.querySelectorAll("input[name=data-sidebar]").forEach(function(t) {
				t.addEventListener("change", function(e) {
					a.changeMenuColor(t.value)
				})
			}), document.querySelectorAll("input[name=data-color]").forEach(function(t) {
				t.addEventListener("change", function(e) {
					a.changeThemeColor(t.value)
				})
			}), document.querySelectorAll("input[name=data-layout]").forEach(function(t) {
				t.addEventListener("change", function(e) {
					a.changeLeftbarSize(t.value)
				})
			}), document.querySelectorAll("input[name=data-bs-theme]").forEach(function(t) {
				t.addEventListener("change", function(e) {
					a.changeLayoutColor(t.value)
				})
			}), document.querySelectorAll("input[name=data-topbar]").forEach(function(t) {
				t.addEventListener("change", function(e) {
					a.changeTopbarColor(t.value)
				})
			}), document.getElementById("light-dark-mode")),
			e = (e && e.addEventListener("click", function(e) {
				"light" === a.config.theme ? a.changeLayoutColor("dark") : a.changeLayoutColor("light")
			}), document.querySelector("#reset-layout")),
			e = (e && e.addEventListener("click", function(e) {
				a.resetTheme()
			}), document.querySelector(".sidenav-toggle-button")),
			e = (e && e.addEventListener("click", function() {
				var e = a.config.sidenav.size,
					t = a.html.getAttribute("data-layout", e);
				"full-width" === t ? a.showBackdrop() : "hidden" == e ? "hidden" === t ? a.changeLeftbarSize("hidden" == e ? "default" : e, !1) : a.changeLeftbarSize("hidden", !1) : "condensed" === t ? a.changeLeftbarSize("condensed" == e ? "default" : e, !1) : a.changeLeftbarSize("condensed", !1), a.html.classList.toggle("sidebar-enable")
			}), document.querySelector(".button-close-fullsidebar"));
		e && e.addEventListener("click", function() {
			a.html.classList.remove("sidebar-enable"), a.hideBackdrop()
		}), document.querySelectorAll(".button-sm-hover").forEach(function(e) {
			e.addEventListener("click", function() {
				var e = a.config.sidenav.size;
				"sm-hover-active" === a.html.getAttribute("data-layout", e) ? a.changeLeftbarSize("hover-view", !1) : a.changeLeftbarSize("sm-hover-active", !1)
			})
		})
	}
	showBackdrop() {
		const e = document.createElement("div"),
			t = (e.id = "custom-backdrop", e.classList = "offcanvas-backdrop fade show", document.body.appendChild(e), document.body.style.overflow = "hidden", 767 < window.innerWidth && (document.body.style.paddingRight = "15px"), this);
		e.addEventListener("click", function(e) {
			t.html.classList.remove("sidebar-enable"), t.hideBackdrop()
		})
	}
	hideBackdrop() {
		var e = document.getElementById("custom-backdrop");
		e && (document.body.removeChild(e), document.body.style.overflow = null, document.body.style.paddingRight = null)
	}
	initWindowSize() {
		var t = this;
		window.addEventListener("resize", function(e) {
			t._adjustLayout()
		})
	}
	_adjustLayout() {
		var e = this;
		window.innerWidth <= 767.98 ? e.changeLeftbarSize("full-width", !1) : 767 <= window.innerWidth && window.innerWidth <= 1140 ? "full-width" !== e.config.sidenav.size && "hidden" !== e.config.sidenav.size && ("hover-view" === e.config.sidenav.size ? e.changeLeftbarSize("condensed") : e.changeLeftbarSize("condensed", !1)) : (e.changeLeftbarSize(e.config.sidenav.size))
	}
	setSwitchFromConfig() {
		sessionStorage.setItem("__THEME_CONFIG__", JSON.stringify(this.config)), document.querySelectorAll(".right-bar input[type=checkbox]").forEach(function(e) {
			e.checked = !1
		});
		var e, t, a, n, i, o = this.config;
		o && (e = document.querySelector("input[type=radio][name=data-layout][value=" + o.nav + "]"), t = document.querySelector("input[type=radio][name=data-bs-theme][value=" + o.theme + "]"), a = document.querySelector("input[type=radio][name=data-color][value=" + o.color.color + "]"), n = document.querySelector("input[type=radio][name=data-topbar][value=" + o.topbar.color + "]"), i = document.querySelector("input[type=radio][name=data-sidebar][value=" + o.menu.color + "]"), o = document.querySelector("input[type=radio][name=data-layout][value=" + o.sidenav.size + "]"), e && (e.checked = !0), t && (t.checked = !0), a && (a.checked = !0), n && (n.checked = !0), i && (i.checked = !0), o && (o.checked = !0))
	}
	init() {
		this.initConfig(), this.initSwitchListener(), this.initWindowSize(), this._adjustLayout(), this.setSwitchFromConfig()
	}
}
document.addEventListener("DOMContentLoaded", function(e) {
	let themesetting = `
	<div class="sidebar-contact">
    	<div class="toggle-theme"  data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas"><i class="ti ti-settings"></i></div>
    </div>
	<div class="sidebar-themesettings offcanvas offcanvas-end" tabindex="-1" id="theme-settings-offcanvas">
        <div class="d-flex align-items-center gap-2 px-3 py-3 offcanvas-header border-bottom bg-primary">
            <h5 class="flex-grow-1 mb-0">Theme Customizer</h5>

            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body h-100" data-simplebar>
			
            <div class="accordion accordion-bordered">  

				<div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold fs-16" type="button" data-bs-toggle="collapse" data-bs-target="#modesetting" aria-expanded="true">
                            Color Mode
                        </button>
                    </h2>
                    <div id="modesetting" class="accordion-collapse collapse show">
						<div class="accordion-body">
							<div class="row g-3">
								<div class="col-6">
									<div class="form-check card-radio">
										<input class="form-check-input" type="radio" name="data-bs-theme" id="layout-color-light" value="light">
										<label class="form-check-label p-2 w-100 d-flex justify-content-center align-items-center" for="layout-color-light">
											<i class="ti ti-sun me-1"></i>Light
										</label>
									</div>
								</div>
								<div class="col-6">
									<div class="form-check card-radio">
										<input class="form-check-input" type="radio" name="data-bs-theme" id="layout-color-dark" value="dark">
										<label class="form-check-label p-2 w-100 d-flex justify-content-center align-items-center" for="layout-color-dark">
											<i class="ti ti-moon me-1"></i>Dark
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold fs-16" type="button" data-bs-toggle="collapse" data-bs-target="#layoutsetting" aria-expanded="true" aria-controls="collapsecustomicon1One">
                            Select Layouts
                        </button>
                    </h2>
                    <div id="layoutsetting" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <div class="theme-content">
                                <div class="row g-3">
                                    <div class="col-4">
                                        <div class="theme-layout">
                                            <input type="radio" name="data-layout" id="defaultLayout" value="default" checked>
                                            <label for="defaultLayout">
                                                <span class="d-block mb-2 layout-img">
                                                    <span class="theme-check rounded-circle"><i class="ti ti-check"></i></span>
                                                    <img src="assets/img/theme/default.svg" alt="img">
                                                </span>                                     
                                                <span class="layout-type">Default</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="theme-layout">
                                            <input type="radio" name="data-layout" id="miniLayout" value="mini">
                                            <label for="miniLayout">
                                                <span class="d-block mb-2 layout-img">
                                                <span class="theme-check rounded-circle"><i class="ti ti-check"></i></span>
                                                    <img src="assets/img/theme/mini.svg" alt="img">
                                                </span>                                    
                                                <span class="layout-type">Mini</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="theme-layout">
                                            <input type="radio" name="data-layout" id="hoverviewLayout" value="hoverview">
                                            <label for="hoverviewLayout">
                                                <span class="d-block mb-2 layout-img">
                                                <span class="theme-check rounded-circle"><i class="ti ti-check"></i></span>
                                                    <img src="assets/img/theme/mini.svg" alt="img">
                                                </span>                                    
                                                <span class="layout-type">Hover View</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="theme-layout">
                                            <input type="radio" name="data-layout" id="hiddenLayout" value="hidden">
                                            <label for="hiddenLayout">
                                                <span class="d-block mb-2 layout-img">
                                                <span class="theme-check rounded-circle"><i class="ti ti-check"></i></span>
                                                    <img src="assets/img/theme/full-width.svg" alt="img">
                                                </span>                                    
                                                <span class="layout-type">Hidden</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="theme-layout">
                                            <input type="radio" name="data-layout" id="full-widthLayout" value="full-width">
                                            <label for="full-widthLayout">
                                                <span class="d-block mb-2 layout-img">
                                                <span class="theme-check rounded-circle"><i class="ti ti-check"></i></span>
                                                    <img src="assets/img/theme/full-width.svg" alt="img">
                                                </span>                                    
                                                <span class="layout-type">Full Width</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold fs-16" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarcolorsetting" aria-expanded="true">
                            Sidebar Color
                        </button>
                    </h2>
                    <div id="sidebarcolorsetting" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                        	<div class="theme-content">
								<h6 class="fs-14 fw-medium mb-2">Solid Colors</h6>
								<div class="d-flex align-items-center flex-wrap mb-1">
									<div class="theme-colorselect m-1 me-2">
										<input type="radio" name="data-sidebar" id="lightSidebar" value="light" checked>
										<label for="lightSidebar" class="d-block rounded bg-white mb-2">
											<span class="theme-check rounded-circle"><i class="ti ti-check"></i></span>
										</label>
									</div>
									<div class="theme-colorselect m-1 me-2">
										<input type="radio" name="data-sidebar" id="sidebar2Sidebar" value="sidebar2">
										<label for="sidebar2Sidebar" class="d-block rounded bg-light mb-2">
											<span class="theme-check rounded-circle"><i class="ti ti-check"></i></span>
										</label>
									</div>
									<div class="theme-colorselect m-1 me-2">
										<input type="radio" name="data-sidebar" id="sidebar3Sidebar" value="sidebar3">
										<label for="sidebar3Sidebar" class="d-block rounded bg-dark mb-2">
											<span class="theme-check rounded-circle"><i class="ti ti-check"></i></span>
										</label>
									</div>
									<div class="theme-colorselect m-1 me-2">
										<input type="radio" name="data-sidebar" id="sidebar4Sidebar" value="sidebar4">
										<label for="sidebar4Sidebar" class="d-block rounded bg-primary mb-2">
											<span class="theme-check rounded-circle"><i class="ti ti-check"></i></span>
										</label>
									</div>
									<div class="theme-colorselect m-1 me-2">
										<input type="radio" name="data-sidebar" id="sidebar5Sidebar" value="sidebar5">
										<label for="sidebar5Sidebar" class="d-block rounded bg-secondary mb-2">
											<span class="theme-check rounded-circle"><i class="ti ti-check"></i></span>
										</label>
									</div>
									<div class="theme-colorselect m-1 me-2">
										<input type="radio" name="data-sidebar" id="sidebar6Sidebar" value="sidebar6">
										<label for="sidebar6Sidebar" class="d-block rounded bg-info mb-2">
											<span class="theme-check rounded-circle"><i class="ti ti-check"></i></span>
										</label>
									</div>    
									<div class="theme-colorselect m-1 me-2">
										<input type="radio" name="data-sidebar" id="sidebar7Sidebar" value="sidebar7">
										<label for="sidebar7Sidebar" class="d-block rounded bg-indigo mb-2">
											<span class="theme-check rounded-circle"><i class="ti ti-check"></i></span>
										</label>
									</div>      
								</div>
								<h6 class="fs-14 fw-medium mb-2">Gradient Colors</h6>
								<div class="d-flex align-items-center flex-wrap">
									<div class="theme-colorselect m-1 me-2">
										<input type="radio" name="data-sidebar" id="gradientsidebar1Sidebar" value="gradientsidebar1">
										<label for="gradientsidebar1Sidebar" class="d-block rounded bg-indigo-gradient">
											<span class="theme-check rounded-circle"><i class="ti ti-check"></i></span>
										</label>
									</div>
									<div class="theme-colorselect m-1 me-2">
										<input type="radio" name="data-sidebar" id="gradientsidebar2Sidebar" value="gradientsidebar2">
										<label for="gradientsidebar2Sidebar" class="d-block rounded bg-primary-gradient">
											<span class="theme-check rounded-circle"><i class="ti ti-check"></i></span>
										</label>
									</div>
									<div class="theme-colorselect m-1 me-2">
										<input type="radio" name="data-sidebar" id="gradientsidebar3Sidebar" value="gradientsidebar3">
										<label for="gradientsidebar3Sidebar" class="d-block rounded bg-secondary-gradient">
											<span class="theme-check rounded-circle"><i class="ti ti-check"></i></span>
										</label>
									</div>
									<div class="theme-colorselect m-1 me-2">
										<input type="radio" name="data-sidebar" id="gradientsidebar4Sidebar" value="gradientsidebar4">
										<label for="gradientsidebar4Sidebar" class="d-block rounded bg-dark-gradient">
											<span class="theme-check rounded-circle"><i class="ti ti-check"></i></span>
										</label>
									</div>
									<div class="theme-colorselect m-1 me-2">
										<input type="radio" name="data-sidebar" id="gradientsidebar5Sidebar" value="gradientsidebar5">
										<label for="gradientsidebar5Sidebar" class="d-block rounded bg-purple-gradient">
											<span class="theme-check rounded-circle"><i class="ti ti-check"></i></span>
										</label>
									</div>
									<div class="theme-colorselect m-1 me-2">
										<input type="radio" name="data-sidebar" id="gradientsidebar6Sidebar" value="gradientsidebar6">
										<label for="gradientsidebar6Sidebar" class="d-block rounded bg-orange-gradient">
											<span class="theme-check rounded-circle"><i class="ti ti-check"></i></span>
										</label>
									</div>    
									<div class="theme-colorselect m-1">
										<input type="radio" name="data-sidebar" id="gradientsidebar7Sidebar" value="gradientsidebar7">
										<label for="gradientsidebar7Sidebar" class="d-block rounded bg-info-gradient">
											<span class="theme-check rounded-circle"><i class="ti ti-check"></i></span>
										</label>
									</div>    
								</div>
							</div>
                        </div>
                    </div>
                </div>   

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold fs-16" type="button" data-bs-toggle="collapse" data-bs-target="#colorsetting" aria-expanded="true">
                            Top Bar Color
                        </button>
                    </h2>
                    <div id="colorsetting" class="accordion-collapse collapse show">
                        <div class="accordion-body pb-1">
                            <div class="theme-content">
                                <h6 class="fs-14 fw-medium mb-2">Solid Colors</h6>
                                <div class="d-flex align-items-center flex-wrap topbar-background mb-1">
                                    <div class="theme-colorselect mb-3 me-3">
                                        <input type="radio" name="data-topbar" id="whiteTopbar" value="white" checked>
                                        <label for="whiteTopbar" class="bg-white">
                                            <span class="theme-check rounded-circle"><i class="ti ti-check"></i></span>
                                        </label>
                                    </div>
                                    <div class="theme-colorselect mb-3 me-3">
                                        <input type="radio" name="data-topbar" id="topbar1Topbar" value="topbar1">
                                        <label for="topbar1Topbar" class="bg-light"><span class="theme-check rounded-circle"><i class="ti ti-check"></i></span></label>
                                    </div>
                                    <div class="theme-colorselect mb-3 me-3">
                                        <input type="radio" name="data-topbar" id="topbar2Topbar" value="topbar2">
                                        <label for="topbar2Topbar" class="bg-dark"><span class="theme-check rounded-circle"><i class="ti ti-check"></i></span></label>
                                    </div>
                                    <div class="theme-colorselect mb-3 me-3">
                                        <input type="radio" name="data-topbar" id="topbar3Topbar" value="topbar3">
                                        <label for="topbar3Topbar" class="bg-primary"><span class="theme-check rounded-circle"><i class="ti ti-check"></i></span></label>
                                    </div>
                                    <div class="theme-colorselect mb-3 me-3">
                                        <input type="radio" name="data-topbar" id="topbar4Topbar" value="topbar4">
                                        <label for="topbar4Topbar" class="bg-secondary"><span class="theme-check rounded-circle"><i class="ti ti-check"></i></span></label>
                                    </div>                   
                                    <div class="theme-colorselect mb-3 me-3">
                                        <input type="radio" name="data-topbar" id="topbar5Topbar" value="topbar5">
                                        <label for="topbar5Topbar" class="bg-info"><span class="theme-check rounded-circle"><i class="ti ti-check"></i></span></label>
                                    </div>                   
                                    <div class="theme-colorselect mb-3">
                                        <input type="radio" name="data-topbar" id="topbar6Topbar" value="topbar6">
                                        <label for="topbar6Topbar" class="bg-indigo"><span class="theme-check rounded-circle"><i class="ti ti-check"></i></span></label>
                                    </div> 
                                </div>
                                <h6 class="fs-14 fw-medium mb-2">Gradient Colors</h6>
                                <div class="d-flex align-items-center flex-wrap topbar-background">
                                    <div class="theme-colorselect mb-3 me-3">
                                        <input type="radio" name="data-topbar" id="gradienttopbar1Topbar" value="gradienttopbar1">
                                        <label for="gradienttopbar1Topbar" class="bg-indigo-gradient">
                                            <span class="theme-check rounded-circle"><i class="ti ti-check"></i></span>
                                        </label>
                                    </div>
                                    <div class="theme-colorselect mb-3 me-3">
                                        <input type="radio" name="data-topbar" id="gradienttopbar2Topbar" value="gradienttopbar2">
                                        <label for="gradienttopbar2Topbar" class="bg-primary-gradient"><span class="theme-check rounded-circle"><i class="ti ti-check"></i></span></label>
                                    </div>
                                    <div class="theme-colorselect mb-3 me-3">
                                        <input type="radio" name="data-topbar" id="gradienttopbar3Topbar" value="gradienttopbar3">
                                        <label for="gradienttopbar3Topbar" class="bg-secondary-gradient"><span class="theme-check rounded-circle"><i class="ti ti-check"></i></span></label>
                                    </div>
                                    <div class="theme-colorselect mb-3 me-3">
                                        <input type="radio" name="data-topbar" id="gradienttopbar4Topbar" value="gradienttopbar4">
                                        <label for="gradienttopbar4Topbar" class="bg-dark-gradient"><span class="theme-check rounded-circle"><i class="ti ti-check"></i></span></label>
                                    </div>
                                    <div class="theme-colorselect mb-3 me-3">
                                        <input type="radio" name="data-topbar" id="gradienttopbar5Topbar" value="gradienttopbar5">
                                        <label for="gradienttopbar5Topbar" class="bg-purple-gradient"><span class="theme-check rounded-circle"><i class="ti ti-check"></i></span></label>
                                    </div>                   
                                    <div class="theme-colorselect mb-3 me-3">
                                        <input type="radio" name="data-topbar" id="gradienttopbar6Topbar" value="gradienttopbar6">
                                        <label for="gradienttopbar6Topbar" class="bg-orange-gradient"><span class="theme-check rounded-circle"><i class="ti ti-check"></i></span></label>
                                    </div>                    
                                    <div class="theme-colorselect mb-3 me-0">
                                        <input type="radio" name="data-topbar" id="gradienttopbar7Topbar" value="gradienttopbar7">
                                        <label for="gradienttopbar7Topbar" class="bg-info-gradient"><span class="theme-check rounded-circle"><i class="ti ti-check"></i></span></label>
                                    </div>                 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold fs-16" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarcolor" aria-expanded="true">
                            Theme Colors
                        </button>
                    </h2>
                    <div id="sidebarcolor" class="accordion-collapse collapse show">
                        <div class="accordion-body pb-2">
                            <div class="theme-content">
                                <div class="d-flex align-items-center flex-wrap">
                                    <div class="theme-colorsset me-2 mb-2">
                                        <input type="radio" name="data-color" id="primaryColor" value="primary" checked>
                                        <label for="primaryColor" class="primary-clr"></label>
                                    </div>
                                    <div class="theme-colorsset me-2 mb-2">
                                        <input type="radio" name="data-color" id="secondaryColor" value="secondary">
                                        <label for="secondaryColor" class="secondary-clr"></label>
                                    </div>  
                                    <div class="theme-colorsset me-2 mb-2">
                                        <input type="radio" name="data-color" id="orangeColor" value="orange">
                                        <label for="orangeColor" class="orange-clr"></label>
                                    </div>
                                    <div class="theme-colorsset me-2 mb-2">
                                        <input type="radio" name="data-color" id="tealColor" value="teal">
                                        <label for="tealColor" class="teal-clr"></label>
                                    </div>  
                                    <div class="theme-colorsset me-2 mb-2">
                                        <input type="radio" name="data-color" id="purpleColor" value="purple">
                                        <label for="purpleColor" class="purple-clr"></label>
                                    </div>
                                    <div class="theme-colorsset me-2 mb-2">
                                        <input type="radio" name="data-color" id="indigoColor" value="indigo">
                                        <label for="indigoColor" class="indigo-clr"></label>
                                    </div>
                                    <div class="theme-colorsset mb-2">
                                        <input type="radio" name="data-color" id="infoColor" value="info">
                                        <label for="infoColor" class="info-clr"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>

        </div>

        <div class="d-flex align-items-center gap-2 px-3 py-2 offcanvas-header border-top">
            <button type="button" class="btn w-50 btn-light" id="reset-layout"><i class="ti ti-restore me-1"></i>Reset</button>
            <button type="button" class="btn w-50 btn-primary">Buy Product</button>
        </div>

    </div>`
	let wrapper = document.createElement("div");
	wrapper.innerHTML = themesetting;

	// Append ALL child nodes
	while (wrapper.firstChild) {
		document.body.appendChild(wrapper.firstChild);
	}
	(new ThemeCustomizer).init()
});

