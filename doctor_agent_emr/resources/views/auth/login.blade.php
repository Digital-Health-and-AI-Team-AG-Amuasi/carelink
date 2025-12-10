<!DOCTYPE html>
<html class="h-full" data-kt-theme="true" data-kt-theme-mode="light" dir="ltr" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport"/>
    <title>CareEMR - Login</title>
    {{-- Google Font: Inter for a modern, clean look --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Apply Inter font family */
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.5; /* Improved readability */
        }

        .page-bg {
            background-image: url('/images/bg.jpg'); /* Assuming this path is correct relative to public/ */
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            /* Enhanced backdrop filter for a subtle, premium blur effect */
            backdrop-filter: blur(5px); /* Increased blur for more focus on the form */
            -webkit-backdrop-filter: blur(5px); /* For Safari support */
        }
        /* No specific dark mode background image provided, using the same for now */
        .dark .page-bg {
            background-image: url('/images/bg.jpg');
        }

        .overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.55); /* Darker overlay for better contrast and form prominence */
            z-index: 1;
        }

        .login-wrapper {
            position: relative;
            z-index: 2;
            /* Advanced card shadow for depth and premium feel */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15), 0 25px 50px rgba(0, 0, 0, 0.1); /* Layered shadow for softer look */
            border-radius: 1.25rem; /* More rounded corners for a modern feel */
            border: 1px solid rgba(229, 231, 235, 0.2); /* Very subtle border blending with background */
            overflow: hidden; /* Ensures content stays within rounded corners */
            transition: all 0.3s ease; /* Smooth transition for potential hover/active states if any */
        }
        .dark .login-wrapper {
            border: 1px solid rgba(75, 85, 99, 0.4); /* Darker border for dark mode */
        }

        /* Custom styles for form elements to match the new aesthetic */
        .kt-card-content {
            background-color: white; /* Ensure card background is white */
            padding: 3rem; /* Increased padding significantly for breathing room */
            box-shadow: inset 0 0 15px rgba(0, 0, 0, 0.02); /* Very subtle inner shadow for depth */
        }
        .dark .kt-card-content {
            background-color: #1f2937; /* Dark mode background */
            box-shadow: inset 0 0 15px rgba(0, 0, 0, 0.15);
        }

        .kt-input {
            display: flex;
            align-items: center;
            border: 1px solid #e5e7eb; /* Lighter gray border */
            border-radius: 0.75rem; /* More rounded inputs */
            padding: 0.85rem 1.25rem; /* Increased vertical and horizontal padding */
            transition: all 0.25s ease-in-out; /* Smoother transitions */
            background-color: #f9fafb; /* Light background for inputs */
            font-size: 1rem; /* Standard font size for input text */
        }
        .kt-input:focus-within {
            border-color: #059669; /* Darker green border on focus */
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.3); /* More prominent, slightly wider green shadow on focus */
            background-color: white; /* White background on focus */
        }
        .dark .kt-input {
            background-color: #2d3748;
            border-color: #4a5568;
            color: #e2e8f0;
        }
        .dark .kt-input:focus-within {
            border-color: #047857; /* Darker green for dark mode focus */
            box-shadow: 0 0 0 3px rgba(4, 120, 87, 0.4);
            background-color: #1a202c;
        }

        .kt-input input {
            flex-grow: 1;
            border: none;
            outline: none;
            background: transparent;
            padding: 0;
            font-size: 1rem; /* Match parent font size */
            color: #374151; /* Darker text color */
        }
        .dark .kt-input input {
            color: #e2e8f0;
        }
        .kt-input input::placeholder {
            color: #a1a1aa; /* Slightly darker placeholder for better contrast */
            opacity: 0.8; /* Ensure placeholder is visible */
        }

        .kt-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 1rem 2rem; /* Even larger padding for buttons */
            border-radius: 0.75rem; /* Consistent rounding */
            font-weight: 700; /* Bolder text for buttons */
            transition: all 0.3s ease-in-out; /* Smoother transitions */
            cursor: pointer;
            text-decoration: none;
            transform: translateY(0); /* For hover effect */
            letter-spacing: 0.025em; /* Slight letter spacing for professionalism */
        }
        .kt-btn-primary {
            background-image: linear-gradient(to right, #059669, #10B981); /* Deeper, richer green gradient */
            color: white;
            box-shadow: 0 8px 20px rgba(5, 150, 105, 0.3); /* More pronounced initial shadow */
        }
        .kt-btn-primary:hover {
            background-image: linear-gradient(to right, #047857, #059669); /* Even darker green on hover */
            box-shadow: 0 12px 25px rgba(5, 150, 105, 0.45); /* Increased shadow on hover */
            transform: translateY(-3px); /* More noticeable lift on hover */
        }
        .kt-btn-primary:active {
            transform: translateY(0); /* Returns to original position */
            box-shadow: 0 4px 10px rgba(5, 150, 105, 0.2); /* Reduced shadow on click */
            background-image: linear-gradient(to right, #059669, #065F46); /* Slightly compressed gradient on active */
        }
        .kt-btn-primary:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.5); /* Stronger focus ring */
        }


        .kt-alert {
            padding: 1rem 1.25rem;
            border-radius: 0.65rem; /* Slightly more rounded alert */
            margin-bottom: 2rem; /* More space below alert for emphasis */
            font-size: 0.95rem;
            line-height: 1.5;
            color: #B91C1C; /* Darker red text */
            background-color: #FEF2F2; /* Light red background */
            border: 1px solid #F87171; /* Red border */
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.1); /* Subtle shadow for alerts */
        }
        .dark .kt-alert {
            color: #FCA5A5;
            background-color: #450A0A;
            border-color: #7F1D1D;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .kt-alert-title ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .kt-alert-title li {
            margin-bottom: 0.25rem;
        }

        .kt-form-label {
            font-size: 0.925rem; /* Slightly larger, more legible label */
            font-weight: 600; /* Semi-bold for clarity */
            color: #4b5563; /* Darker gray for labels */
            margin-bottom: 0.25rem; /* Small space below label */
        }
        .dark .kt-form-label {
            color: #d1d5db;
        }

        .kt-link {
            font-weight: 600; /* Semi-bold for links */
            color: #059669; /* Deeper green link color */
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .kt-link:hover {
            text-decoration: underline;
            color: #047857; /* Darker green on hover */
        }
        .dark .kt-link {
            color: #34D399;
        }
        .dark .kt-link:hover {
            color: #10B981;
        }

        /* Checkbox styling needs to be carefully handled with Tailwind or a custom component library */
        .kt-checkbox-label {
            font-size: 0.925rem; /* Consistent with labels */
            color: #4b5563; /* Consistent with labels */
        }
        .dark .kt-checkbox-label {
            color: #d1d5db;
        }

        .text-muted-foreground { /* For eye icon */
            color: #6b7280;
        }
        .dark .text-muted-foreground {
            color: #9ca3af;
        }

        .kt-btn-ghost {
            background-color: transparent !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0.6rem; /* Slightly more padding for the icon button */
            line-height: 1;
            color: #9ca3af; /* Softer icon color */
            transition: all 0.2s ease;
        }
        .kt-btn-ghost:hover {
            color: #6b7280; /* Darker on hover */
        }
        .dark .kt-btn-ghost {
            color: #6b7280;
        }
        .dark .kt-btn-ghost:hover {
            color: #a1a1aa;
        }

        /* Loader Overlay */
        .loader-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.85); /* Slightly less transparent white background */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10; /* Ensure it's above the form content */
            border-radius: 1.25rem; /* Match parent border-radius */
            transition: opacity 0.3s ease; /* Smooth fade in/out */
            opacity: 1; /* Default to visible if no hidden class */
        }
        .dark .loader-overlay {
            background-color: rgba(31, 41, 55, 0.85); /* Slightly less transparent dark background */
        }

        /* Loader Spinner (e.g., a simple rotating circle) */
        .loader-spinner {
            border: 5px solid rgba(229, 231, 235, 0.5); /* Thicker, more subtle light grey */
            border-top: 5px solid #059669; /* Your primary green */
            border-radius: 50%;
            width: 50px; /* Slightly larger spinner */
            height: 50px;
            animation: spin 0.9s linear infinite; /* Faster and smoother animation */
        }
        .dark .loader-spinner {
            border: 5px solid rgba(75, 85, 99, 0.5);
            border-top: 5px solid #10B981; /* Slightly brighter green for dark mode contrast */
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Utility class to hide/show */
        .hidden {
            display: none !important;
            opacity: 0; /* Ensures fade out effect */
            pointer-events: none; /* Prevents interaction when hidden */
        }

    </style>
</head>

<body class="antialiased flex h-full text-base text-gray-800 bg-gray-50 dark:bg-gray-900 dark:text-gray-200">

<div class="flex items-center justify-center flex-col grow bg-center bg-no-repeat page-bg">
    <div class="overlay"></div>

    <div class="login-wrapper kt-card max-w-[420px] w-full mx-4 sm:mx-auto"> 
        <div id="form-loader" class="loader-overlay hidden">
            <div class="loader-spinner"></div>
        </div>

        <form action="{{ route('login') }}" class="kt-card-content flex flex-col gap-8" id="sign_in_form" method="post"> {{-- Increased gap for more breathing room --}}

            @if ($errors->any())
                <div class="kt-alert kt-alert-destructive" id="alert_5" role="alert" aria-live="assertive"> {{-- Added ARIA roles for accessibility --}}
                    <div class="kt-alert-title">
                        <ul class="list-disc pl-5">
                            @foreach( $errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @csrf

            <div class="text-center mb-6"> {{-- Increased margin-bottom --}}
                <h3 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 leading-tight mb-3 tracking-tight"> {{-- Larger, bolder, tighter tracking heading --}}
                    Welcome Back 
                </h3>
                <p class="text-md text-gray-500 dark:text-gray-400">Your trusted partner in healthcare management.</p> {{-- Refined subheading --}}
            </div>

            <div class="flex flex-col gap-6 items-center w-full"> {{-- Increased gap --}}
                <a class="shrink-0 mb-4" href="/" aria-label="CareEMR Home"> {{-- Added margin-bottom, aria-label --}}
                    <img class="max-h-[60px] w-auto drop-shadow-md" src="{{ asset('assets/media/avatars/care_logo_png.png') }}" alt="CareEMR Logo"/> {{-- Larger logo, subtle drop shadow --}}
                </a>
                <div class="w-full flex flex-col gap-2"> {{-- Increased gap --}}
                    <label class="kt-form-label" for="email">
                        Email Address
                    </label>
                    <div class="kt-input">
                        <input class="w-full" placeholder="you@example.com" type="email" value="{{ old('email') }}" name="email" id="email" required autocomplete="username" aria-describedby="email-error"/> {{-- Added autocomplete, aria-describedby for error linking --}}
                    </div>
                    {{-- Consider adding a hidden span for email-specific errors linked via aria-describedby --}}
                </div>
            </div>
            <div class="flex flex-col gap-2 w-full"> {{-- Increased gap --}}
                <div class="flex items-center justify-between">
                    <label class="kt-form-label" for="password">
                        Password
                    </label>
                    <a class="text-sm kt-link shrink-0" href="{{ route('password.request') }}">
                        Forgot Password?
                    </a>
                </div>
                <div class="kt-input" data-kt-toggle-password="true">
                    <input name="password" placeholder="Enter your password" type="password" id="password" required autocomplete="current-password" aria-describedby="password-error"/> {{-- Added autocomplete, aria-describedby --}}
                    <button class="kt-btn kt-btn-sm kt-btn-ghost kt-btn-icon bg-transparent! -me-1.5" data-kt-toggle-password-trigger="true" type="button" aria-label="Toggle password visibility">
                        <span class="kt-toggle-password-active:hidden">
                            <i class="ki-filled ki-eye text-muted-foreground"></i>
                        </span>
                        <span class="hidden kt-toggle-password-active:block">
                            <i class="ki-filled ki-eye-slash text-muted-foreground"></i>
                        </span>
                    </button>
                </div>
                {{-- Consider adding a hidden span for password-specific errors linked via aria-describedby --}}
            </div>
            <label class="kt-label flex items-center gap-2 mt-2 cursor-pointer"> {{-- Added cursor-pointer --}}
                <input class="kt-checkbox kt-checkbox-sm" name="remember" type="checkbox" />
                <span class="kt-checkbox-label select-none">Remember me</span> {{-- Added select-none --}}
            </label>
            <button class="kt-btn kt-btn-primary py-6 flex justify-center grow" type="submit">
                Sign In
            </button>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('sign_in_form');
        const formLoader = document.getElementById('form-loader');
        const submitButton = loginForm ? loginForm.querySelector('button[type="submit"]') : null;

        if (loginForm && formLoader && submitButton) {
            loginForm.addEventListener('submit', function() {
                formLoader.classList.remove('hidden');
                submitButton.disabled = true; // Disable button to prevent multiple submissions
                submitButton.textContent = 'Signing In...'; // Provide feedback on button itself
            });

            // Check for validation errors on page load (Laravel often re-renders form with errors)
            const errorAlert = document.getElementById('alert_5');
            // Check if the alert exists and contains actual list items (errors)
            if (errorAlert && errorAlert.querySelector('ul li')) {
                formLoader.classList.add('hidden'); // Hide loader if it was active
                submitButton.disabled = false; // Re-enable button
                submitButton.textContent = 'Sign In'; // Reset button text
            }
        }
    });
</script>
</body>
</html>