<div id="kt_app_header" class="app-header">
    <!--begin::Header container-->
    <div class="app-container container-fluid d-flex align-items-stretch flex-stack" id="kt_app_header_container">
        <!--begin::Sidebar toggle-->
        <div class="d-flex align-items-center d-block d-lg-none ms-n3" title="Show sidebar menu">
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px me-2" id="kt_app_sidebar_mobile_toggle">
                <i class="ki-outline ki-abstract-14 fs-2"></i>
            </div>
            <!--begin::Logo image-->
            <a href="index.html">
                <img alt="Logo" src="{{ asset('assets/media/logos/demo38-small.svg') }}" class="h-30px" />
            </a>
            <!--end::Logo image-->
        </div>
        <!--end::Sidebar toggle-->
        <div class="d-flex align-items-center me-5">
            <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal"
                data-bs-target="#kt_modal_update_email">
                <i class="ki-outline ki-message-text-2 fs-5 me-1"></i>
                <span>{{ auth()->user()->email }}</span>
            </button>
        </div>
        <!--begin::Navbar-->
        <div class="app-navbar flex-lg-grow-1" id="kt_app_header_navbar">
            <div class="app-navbar-item d-flex align-items-stretch flex-lg-grow-1">
                <!--begin::Search-->
                <div id="kt_header_search" class="header-search d-flex align-items-center w-lg-200px"
                    data-kt-search-keypress="true" data-kt-search-min-length="2" data-kt-search-enter="enter"
                    data-kt-search-layout="menu" data-kt-search-responsive="true" data-kt-menu-trigger="auto"
                    data-kt-menu-permanent="true" data-kt-menu-placement="bottom-start">
                    <!--begin::Tablet and mobile search toggle-->
                    <div data-kt-search-element="toggle"
                        class="search-toggle-mobile d-flex d-lg-none align-items-center">
                        <div class="d-flex">
                            <i class="ki-outline ki-magnifier fs-1"></i>
                        </div>
                    </div>
                    <!--end::Tablet and mobile search toggle-->

                    <div data-kt-search-element="content"></div>
                    <div data-kt-search-element="form"></div>
                    <div data-kt-search-element="input"></div>
                    <div data-kt-search-element="spinner"></div>
                    <div data-kt-search-element="clear"></div>
                    <div data-kt-search-element="toggle"></div>
                    <div data-kt-search-element="submit"></div>
                    <div data-kt-search-element="toolbar"></div>
                    <div data-kt-search-element="results"></div>
                    <div data-kt-search-element="suggestion"></div>
                </div>
                <!--end::Search-->
            </div>

            <!--begin::User menu-->
            <div class="app-navbar-item ms-1 ms-md-3" id="kt_header_user_menu_toggle">
                <!--begin::Menu wrapper-->
                <div class="cursor-pointer symbol symbol-circle symbol-35px symbol-md-45px"
                    data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
                    data-kt-menu-placement="bottom-end">
                    <img src="{{ asset('/assets/media/avatars/300-2.jpg') }}" alt="user" />
                </div>
                <div class="fw-bold d-flex align-items-center fs-5" style="margin-left: 1rem">{{ auth()->user()->name }}
                </div>
                <!--begin::User account menu-->
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                    data-kt-menu="true">
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <div class="menu-content d-flex align-items-center px-3">
                            <!--begin::Avatar-->
                            <div class="symbol symbol-50px me-5">
                                <img alt="Logo" src="{{ asset('/assets/media/avatars/300-2.jpg') }}" />
                            </div>
                            <!--end::Avatar-->
                            <!--begin::Username-->
                            <div class="d-flex flex-column">
                                <div class="fw-bold d-flex align-items-center fs-5">{{ auth()->user()->name }}
                                    <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">{{
                                        auth()->user()->role->name }}</span>
                                </div>
                                <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{
                                    auth()->user()->email }}</a>
                            </div>
                            <!--end::Username-->
                        </div>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu separator-->
                    <div class="separator my-2"></div>
                    <!--end::Menu separator-->

                    <!--begin::Menu separator-->
                    <div class="separator my-2"></div>
                    <!--end::Menu separator-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                        data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                        {{-- <a href="#" class="menu-link px-5">
                            <span class="menu-title position-relative">Mode
                                <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                                    <i class="ki-outline ki-night-day theme-light-show fs-2"></i>
                                    <i class="ki-outline ki-moon theme-dark-show fs-2"></i>
                                </span></span>
                        </a> --}}
                        <!--begin::Menu-->
                        {{-- <div
                            class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px"
                            data-kt-menu="true" data-kt-element="theme-mode-menu">
                            <!--begin::Menu item-->
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-outline ki-night-day fs-2"></i>
                                    </span>
                                    <span class="menu-title">Light</span>
                                </a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-outline ki-moon fs-2"></i>
                                    </span>
                                    <span class="menu-title">Dark</span>
                                </a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-outline ki-screen fs-2"></i>
                                    </span>
                                    <span class="menu-title">System</span>
                                </a>
                            </div>
                            <!--end::Menu item-->
                        </div> --}}
                        <!--end::Menu-->
                    </div>
                    <!--end::Menu item-->

                    <!--begin::Menu item-->
                    <div class="menu-item px-5">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form" class="">
                            @csrf
                        </form>
                        <a a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="menu-link px-5">Sign Out</a>
                    </div>
                    <!--end::Menu item-->
                </div>
                <!--end::User account menu-->
                <!--end::Menu wrapper-->
            </div>
            <!--end::User menu-->
        </div>
        <!--end::Navbar-->
        <!--begin::Separator-->
        <div class="app-navbar-separator separator d-none d-lg-flex"></div>
        <!--end::Separator-->
    </div>
    <!--end::Header container-->
</div>
<div class="modal fade" tabindex="-1" id="kt_modal_update_email">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Email</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i class="ki-outline ki-cross fs-2"></i>
                </div>
            </div>
            <form action="{{ route('user.update-email') }}" method="POST" id="update_email_form">
                @csrf
                <div class="modal-body">
                    <div class="mb-5">
                        <label class="form-label">Current Email</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->email }}" disabled />
                    </div>
                    <div class="mb-5">
                        <label for="new_email" class="required form-label">New Email</label>
                        <input type="email" id="new_email" name="email" class="form-control"
                            placeholder="Enter new email address" required />
                        <div class="text-muted fs-7 mt-2">Please enter valid email address</div>
                    </div>
                    <div class="mb-5">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="email" id="new_password" name="email" class="form-control"
                            placeholder="Enter new password" required />
                        <div class="text-muted fs-7 mt-2">Enter new password when you want to change.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="update_email_submit">
                        <span class="indicator-label">Update</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
    $('#update_email_form').on('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const submitButton = document.getElementById('update_email_submit');
        
        // Disable button and show loading indicator
        submitButton.setAttribute('data-kt-indicator', 'on');
        submitButton.disabled = true;
        
        // Submit form
        $.ajax({
            url: $(form).attr('action'),
            method: 'POST',
            data: $(form).serialize(),
            success: function(response) {
                // Show success message
                toastr.success('Email updated successfully!');
                
                // Reload page after short delay
                setTimeout(function() {
                    location.reload();
                }, 1500);
            },
            error: function(xhr) {
                // Show error message
                toastr.error(xhr.responseJSON?.message || 'Failed to update email. Please try again.');
                
                // Re-enable button
                submitButton.removeAttribute('data-kt-indicator');
                submitButton.disabled = false;
            }
        });
    });
});
</script>