<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full" data-kt-theme="true" data-kt-theme-mode="light" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>@yield("title")</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased h-full text-base montserrat text-foreground bg-background demo1 kt-header-fixed">

<!-- Main -->
    <!-- Remove unnecessary flex containers -->
    <!-- Wrapper -->
    <div class="kt-wrapper flex flex-col min-h-screen">
        <!-- Header -->
        <header class="kt- bg-white py-[6px] fixed top-0 z-10 start-0 end-0 flex items-stretch shrink-0 bg-background-blue" data-kt-sticky="true" data-kt-sticky-class="border-b border-border" data-kt-sticky-name="header" id="header">
            <!-- Container -->
            <div class="kt-container-fixed flex justify-between items-stretch lg:gap-4" id="headerContainer">
                <!-- Mobile Logo -->
                <div class="flex gap-2.5 items-center -ms-1">
                    <a class="shrink-0" href="/">
                        <img class="max-h-[40px] w-auto" src="{{ asset('assets/media/avatars/care_logo.png') }}" alt=""/>
                    </a>
                </div>
                <!-- End of Mobile Logo -->

                {{-- <x-breadcrumb /> --}}
                <div class="flex-1"></div>

                <!-- Topbar -->
                <div class="flex items-center gap-2.5">
                    <div class="shrink-0" data-kt-dropdown="true" data-kt-dropdown-offset="10px, 10px" data-kt-dropdown-offset-rtl="-20px, 10px" data-kt-dropdown-placement="bottom-end" data-kt-dropdown-placement-rtl="bottom-start" data-kt-dropdown-trigger="click">
                        <div class="cursor-pointer shrink-0" data-kt-dropdown-toggle="true">
                            <img alt="" class="size-9 rounded-full border-2 border-green-500 shrink-0" src="{{ asset('assets/media/avatars/img.png') }}"/>
                        </div>
                        <div class="kt-dropdown-menu w-[250px]" data-kt-dropdown-menu="true">
                            <div class="flex items-center justify-between px-2.5 py-1.5 gap-1.5">
                                <div class="flex items-center gap-2">
                                    <img alt="" class="size-9 shrink-0 rounded-full border-2 border-green-500" src="{{ asset('assets/media/avatars/img.png') }}"/>
                                    <div class="flex flex-col gap-1.5">
                                        <span class="text-sm text-foreground font-semibold leading-none">
                                            {{ session()->get('first_name') }} {{ session()->get('last_name') }}
                                        </span>
                                        <a class="text-xs text-secondary-foreground hover:text-primary font-medium leading-none" href="html/demo1/account/home/get-started.html">
                                            {{ session()->get('email') }}
                                        </a>
                                    </div>
                                </div>
                                <span class="kt-badge kt-badge-sm kt-badge-primary kt-badge-outline">
           Pro
          </span>
                            </div>
                            <ul class="kt-dropdown-menu-sub">
                                <li>
                                    <div class="kt-dropdown-menu-separator">
                                    </div>
                                </li>
                                <li>
                                    <a class="kt-dropdown-menu-link" href="html/demo1/public-profile/profiles/default.html">
                                        <i class="ki-filled ki-badge">
                                        </i>
                                        Public Profile
                                    </a>
                                </li>
                                <li data-kt-dropdown="true" data-kt-dropdown-placement="right-start" data-kt-dropdown-trigger="hover">
                                    <button class="kt-dropdown-menu-toggle" data-kt-dropdown-toggle="true">
                                        <i class="ki-filled ki-setting-2">
                                        </i>
                                        My Account
                                        <span class="kt-dropdown-menu-indicator">
             <i class="ki-filled ki-right text-xs">
             </i>
            </span>
                                    </button>
                                    <div class="kt-dropdown-menu w-[220px]" data-kt-dropdown-menu="true">
                                        <ul class="kt-dropdown-menu-sub">
                                            <li>
                                                <a class="kt-dropdown-menu-link" href="#">
               <span class="flex items-center gap-2">
                <i class="ki-filled ki-icon">
                </i>
                Billing
               </span>
                                                    <span class="ms-auto inline-flex items-center" data-kt-tooltip="true" data-kt-tooltip-placement="top">
                <i class="ki-filled ki-information-2 text-base text-muted-foreground">
                </i>
                <span class="kt-tooltip" data-kt-tooltip-content="true">
                 Payment and subscription info
                </span>
               </span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="kt-dropdown-menu-link" href="html/demo1/account/security/overview.html">
                                                    <i class="ki-filled ki-medal-star">
                                                    </i>
                                                    Security
                                                </a>
                                            </li>
                                            <li>
                                                <div class="kt-dropdown-menu-separator">
                                                </div>
                                            </li>
                                            <li>
                                                <a class="kt-dropdown-menu-link" href="html/demo1/account/security/overview.html">
               <span class="flex items-center gap-2">
                <i class="ki-filled ki-shield-tick">
                </i>
                Notifications
               </span>
                                                    <input checked="" class="ms-auto kt-switch" name="check" type="checkbox" value="1"/>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li>
                                    <div class="kt-dropdown-menu-separator">
                                    </div>
                                </li>
                            </ul>
                            <div class="px-2.5 pt-1.5 mb-2.5 flex flex-col gap-3.5">
                                <div class="flex items-center gap-2 justify-between">
           <span class="flex items-center gap-2">
            <i class="ki-filled ki-moon text-base text-muted-foreground">
            </i>
            <span class="font-medium text-2sm">
             Dark Mode
            </span>
           </span>
                                    <input class="kt-switch" data-kt-theme-switch-state="dark" data-kt-theme-switch-toggle="true" name="check" type="checkbox" value="1"/>
                                </div>
                                <form action="{{ route('logout') }}" method="post">
                                    @csrf

                                    <input type="submit" value="Log out" class="kt-btn kt-btn-outline justify-center w-full">
                                </form>

                            </div>
                        </div>
                    </div>
                    <!-- End of User -->
                </div>
                    <!-- End of Topbar -->
            </div>
                <!-- End of Container -->
        </header>
            <!-- End of Header -->

            @yield('content')

            <x-footer />
        </div>

        <!-- End of Wrapper -->
    </div>
    <!-- End of Main -->

<!-- JavaScript functions for patient actions -->
<script>
    function viewPatient(patientId) {
        window.location.href = `/patients/${patientId}`;
    }

    function editPatient(patientId) {
        window.location.href = `/patients/${patientId}/edit`;
    }

    function viewVisits(patientId) {
        window.location.href = `/patients/${patientId}/visits`;
    }

    function viewConditions(patientId) {
        window.location.href = `/patients/${patientId}/conditions`;
    }

    // Search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[type="text"]');
        const tableRows = document.querySelectorAll('tbody tr');

        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();

                tableRows.forEach(row => {
                    const patientName = row.querySelector('td:first-child .font-semibold')?.textContent.toLowerCase() || '';
                    const lhimsNumber = row.querySelector('code')?.textContent.toLowerCase() || '';
                    const phone = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';

                    const isMatch = patientName.includes(searchTerm) ||
                                  lhimsNumber.includes(searchTerm) ||
                                  phone.includes(searchTerm);

                    row.style.display = isMatch ? '' : 'none';
                });
            });
        }
    });

</script>

<!-- Additional styles to match the modern UI -->
<style>
    /* Custom styles to enhance the modern look */
    .kt-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .hover\:bg-gray-50:hover {
        background-color: #f9fafb;
    }

    .bg-primary\/10 {
        background-color: rgba(59, 130, 246, 0.1);
    }

    .text-primary {
        color: #3b82f6;
    }

    .tracking-tight {
        letter-spacing: -0.025em;
    }

    .text-muted-foreground {
        color: #6b7280;
    }

    /* Responsive table improvements */
    @media (max-width: 768px) {
        .min-w-full {
            font-size: 0.875rem;
        }

        .px-6 {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .py-4 {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }
    }

    /* Button hover effects */
    .kt-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Card hover effects */
    .bg-white:hover {
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.05);
    }

    /* Smooth transitions */
    .bg-white,
    .kt-btn,
    tbody tr {
        transition: all 0.2s ease-in-out;
    }
</style>

@push('scripts')
<script>

    document.addEventListener('DOMContentLoaded', function () {
// put jquery code here
        $('.kt-btn-primary').click(function() {
            // You can add modal opening logic here or redirect
            console.log('Add patient button clicked');
        });

        // Table row click handler for better UX
        $('tbody tr').click(function(e) {
            // Don't trigger if clicking on action buttons
            if (!$(e.target).closest('.kt-menu').length) {
                const patientId = $(this).data('patient-id');
                if (patientId) {
                    viewPatient(patientId);
                }
            }
        });

    },false);
    // Additional JavaScript for enhanced functionality
    // $(document).ready(function() {
    //     // Initialize any additional components if needed
    //
    //     // Add patient button click handler
    //
    // });
</script>
@endpush
@stack('scripts')
</body>
</html>
