<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ELEDJI - Evenements a Lome')</title>

    {{-- Swiper CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            padding-top: 0 !important;
            margin: 0 !important;
            font-family: 'Poppins', sans-serif;
        }

        /* Padding pour la navbar flottante - retire pour permettre au hero de commencer a top:0 */
        body > main {
            padding-top: 0;
        }

        /* Styles globaux supplementaires */
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px;
        }
    </style>

    @stack('styles')
</head>
<body class="font-poppins bg-white" style="font-family:'Poppins',sans-serif;">

    @include('components.navbar')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    {{-- Swiper JS --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    {{-- GSAP --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    @stack('scripts')

    {{-- Password Toggle Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Find all password inputs
            const passwordInputs = document.querySelectorAll('input[type="password"]');

            passwordInputs.forEach(function(input) {
                // Check if already wrapped
                if (input.parentNode.classList.contains('password-wrapper')) return;

                // Create wrapper
                const wrapper = document.createElement('div');
                wrapper.className = 'password-wrapper';
                wrapper.style.position = 'relative';
                wrapper.style.display = 'block';

                // Insert wrapper before input
                input.parentNode.insertBefore(wrapper, input);
                wrapper.appendChild(input);

                // Style input to fit in wrapper
                input.style.width = '100%';

                // Create toggle button
                const toggleBtn = document.createElement('button');
                toggleBtn.type = 'button';
                toggleBtn.className = 'password-toggle';
                toggleBtn.innerHTML = '<i class="fa fa-eye"></i>';
                toggleBtn.style.position = 'absolute';
                toggleBtn.style.right = '12px';
                toggleBtn.style.top = '50%';
                toggleBtn.style.transform = 'translateY(-50%)';
                toggleBtn.style.background = 'none';
                toggleBtn.style.border = 'none';
                toggleBtn.style.cursor = 'pointer';
                toggleBtn.style.color = '#666';
                toggleBtn.style.padding = '4px 8px';
                toggleBtn.style.fontSize = '14px';

                // Toggle click handler
                toggleBtn.addEventListener('click', function() {
                    if (input.type === 'password') {
                        input.type = 'text';
                        toggleBtn.innerHTML = '<i class="fa fa-eye-slash"></i>';
                    } else {
                        input.type = 'password';
                        toggleBtn.innerHTML = '<i class="fa fa-eye"></i>';
                    }
                });

                wrapper.appendChild(toggleBtn);
            });
        });
    </script>
</body>
</html>