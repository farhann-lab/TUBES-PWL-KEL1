<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELCO — Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700;1,900&family=Cormorant+Garamond:wght@300;400;600&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- resources/views/auth/login.blade.php --}}

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'coffee-dark':   '#3D1A08',
                        'coffee-medium': '#5C2D0E',
                        'coffee-brown':  '#7B3F00',
                        'gold-deep':     '#B8860B',
                        'gold-light':    '#C9A227',
                        'cream':         '#F5ECD7',
                        'cream-light':   '#FAF6EE',
                        'cream-border':  '#D4B483',
                    },
                    fontFamily: {
                        'display': ['Playfair Display', 'serif'],
                        'serif':   ['Cormorant Garamond', 'serif'],
                        'sans':    ['Montserrat', 'sans-serif'],
                    },
                    keyframes: {
                        'slide-up': {
                            '0%':   { transform: 'translateY(40px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)',     opacity: '1' },
                        },
                        'fade-in': {
                            '0%':   { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                    },
                    animation: {
                        'slide-up': 'slide-up 0.7s cubic-bezier(0.22,1,0.36,1) both',
                        'fade-in':  'fade-in 0.6s ease-out both',
                    }
                }
            }
        }
    </script>
    <style>
        body { background: #FFFFFF; font-family: 'Montserrat', sans-serif; }

        /* ── Corner bean images ── */
        .corner-img {
            position: fixed;
            pointer-events: none;
            z-index: 0;
        }

        /* ── Floating field label ── */
        .field-wrap {
            position: relative;
            border: 2px solid #D4B483;
            border-radius: 14px;
            background: #FAF6EE;
            transition: border-color 0.25s, box-shadow 0.25s;
        }
        .field-wrap:focus-within {
            border-color: #B8860B;
            box-shadow: 0 0 0 4px rgba(184,134,11,0.13);
        }
        .field-label {
            position: absolute;
            top: -14px; left: 16px;
            background: #3D1A08;
            color: #FAF6EE;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            padding: 3px 12px;
            border-radius: 6px;
            text-transform: uppercase;
            pointer-events: none;
            user-select: none;
        }
        .field-inner {
            padding: 18px 16px 10px;
            border: 2px solid #C9A227;
            border-radius: 10px;
            margin: 8px;
            background: white;
            transition: border-color 0.2s;
        }
        .field-inner:focus-within { border-color: #B8860B; }
        .field-inner input {
            width: 100%;
            background: transparent;
            border: none;
            outline: none;
            color: #3D1A08;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.95rem;
            font-weight: 500;
        }
        .field-inner input::placeholder {
            color: #B8860B;
            opacity: 0.55;
            font-style: italic;
            font-size: 0.88rem;
        }

        /* ── Login Button ── */
        .btn-login {
            background: #3D1A08;
            color: white;
            font-family: 'Montserrat', sans-serif;
            font-weight: 800;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            font-size: 0.9rem;
            border: none;
            border-radius: 12px;
            padding: 18px 60px;
            cursor: pointer;
            transition: transform 0.2s cubic-bezier(0.22,1,0.36,1), box-shadow 0.2s, background 0.2s;
            position: relative;
            overflow: hidden;
        }
        .btn-login::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.14) 0%, transparent 60%);
            opacity: 0; transition: opacity 0.3s;
        }
        .btn-login:hover::before  { opacity: 1; }
        .btn-login:hover {
            transform: translateY(-3px) scale(1.04);
            box-shadow: 0 14px 36px rgba(61,26,8,0.35);
        }
        .btn-login:active {
            transform: translateY(0) scale(0.97);
            box-shadow: 0 4px 12px rgba(61,26,8,0.2);
        }

        /* ── Password show/hide toggle ── */
        .toggle-pw {
            cursor: pointer;
            color: #B8860B;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            user-select: none;
            transition: color 0.2s;
        }
        .toggle-pw:hover { color: #3D1A08; }

        /* Grain overlay */
        body::after {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 999;
        }

        ::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="relative min-h-screen overflow-hidden">

    <!-- ═══════════════════════════════
         CORNER BEAN IMAGES
    ═══════════════════════════════ -->

    <!-- Pojok kiri atas -->
    <img src="image/beansone.png"   alt="" class="corner-img" style="top:0;    left:0;  width:280px;" />

    <!-- Pojok kiri bawah -->
    <img src="image/beanstwo.png"   alt="" class="corner-img" style="bottom:0; left:0;  width:280px;" />

    <!-- Pojok kanan bawah -->
    <img src="image/beansthree.png" alt="" class="corner-img" style="bottom:0; right:0; width:280px;" />


    <!-- ═══════════════════════════════
         MAIN LAYOUT
    ═══════════════════════════════ -->
    <div class="relative z-10 flex min-h-screen items-center justify-center gap-8 px-8">

        <!-- LEFT: Tagline -->
        <div class="w-5/12 opacity-0 animate-slide-up" style="animation-delay:0.15s; animation-fill-mode:forwards;">
            <h1 class="font-sans text-amber-900 leading-tight"
                style="font-size:clamp(2.4rem,4.5vw,3.6rem); font-weight:900; max-width:360px;">
                <p>"{{ \Illuminate\Support\Arr::random([
            'Nice day for coffee, ain\'t it?',
            'New day, new brew! Let\'s login.',
            'Sip, savor, login. Welcome back!',
            'Bring the calm with coffee. Please login.',
            'Fuel your day with a login and a latte.',
            'Coffee and login: the perfect blend.',
            'Login to your daily dose of coffee goodness.',
            'A login a day keeps the grumpiness away.',
            'Welcome back! Time to login and perk up.',
            'Login and let the coffee do the talking.',
        ]) }}"</p>
            </h1>
        </div>

        <!-- RIGHT: Form Card -->
        <div class="w-5/12 flex flex-col items-center justify-center">

            <!-- ELCO Logo -->
            <div class="self-end mb-8 opacity-0 animate-fade-in" style="animation-delay:0.1s; animation-fill-mode:forwards;">
                <div class="flex items-center gap-3">
                    <svg width="38" height="38" viewBox="0 0 68 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M24 10 Q26 6 24 2" stroke="#3D1A08" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                        <path d="M34 10 Q36 6 34 2" stroke="#3D1A08" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                        <path d="M44 10 Q46 6 44 2" stroke="#3D1A08" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                        <rect x="14" y="16" width="40" height="36" rx="4" stroke="#3D1A08" stroke-width="3" fill="none"/>
                        <rect x="22" y="24" width="24" height="20" rx="2" stroke="#3D1A08" stroke-width="2.5" fill="none"/>
                        <path d="M54 24 Q64 24 64 34 Q64 44 54 44" stroke="#3D1A08" stroke-width="3" stroke-linecap="round" fill="none"/>
                        <path d="M8 52 Q34 62 60 52" stroke="#3D1A08" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                    </svg>
                    <span class="font-display text-coffee-dark font-bold tracking-widest"
                          style="font-size:1.8rem; letter-spacing:0.15em;">ELCO.</span>
                </div>
            </div>

            <!-- FORM — fungsi auth tetap utuh -->
            <form method="POST" action="{{ route('login') }}"
                  class="w-full max-w-sm flex flex-col gap-6">
                @csrf

                @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-5 py-3 opacity-0 animate-slide-up"
                     style="animation-delay:0.2s; animation-fill-mode:forwards;">
                    @foreach ($errors->all() as $error)
                        <p>• {{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <!-- Email Field -->
                <div class="field-wrap opacity-0 animate-slide-up"
                     style="animation-delay:0.3s; animation-fill-mode:forwards;">
                    <span class="field-label">E-Mail</span>
                    <div class="field-inner flex items-center gap-2">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="flex-shrink-0 opacity-40">
                            <rect x="2" y="4" width="20" height="16" rx="3" stroke="#B8860B" stroke-width="2"/>
                            <path d="M2 8l10 6 10-6" stroke="#B8860B" stroke-width="2" stroke-linejoin="round"/>
                        </svg>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="admin@example.com"
                            autocomplete="email"
                            required
                        />
                    </div>
                </div>

                <!-- Password Field -->
                <div class="field-wrap opacity-0 animate-slide-up"
                     style="animation-delay:0.45s; animation-fill-mode:forwards;">
                    <span class="field-label">Password</span>
                    <div class="field-inner flex items-center gap-2">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="flex-shrink-0 opacity-40">
                            <rect x="5" y="11" width="14" height="10" rx="2" stroke="#B8860B" stroke-width="2"/>
                            <path d="M8 11V7a4 4 0 1 1 8 0v4" stroke="#B8860B" stroke-width="2"/>
                        </svg>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Password123"
                            autocomplete="current-password"
                            required
                        />
                        <!-- Toggle password: menggunakan image -->
                        <img
                            src="{{ asset('image/eye-show.png') }}"
                            id="toggle-pw-img"
                            class="flex-shrink-0 cursor-pointer opacity-75 hover:opacity-100 transition-opacity"
                            onclick="togglePassword()"
                            alt="Toggle Password"
                            style="width: 28px; height: 28px; object-fit: contain;"
                        />
                    </div>

                    <!-- Forgot password — tetap di dalam .field-wrap agar layout rapi -->
                    <!-- <div class="flex justify-end px-3 pb-2 pt-1">
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="font-sans text-xs font-semibold text-gold-deep hover:text-coffee-dark transition-colors duration-200 tracking-wide">
                            Lupa Password?
                        </a>
                        @else
                        <span class="font-sans text-xs font-semibold text-gold-deep tracking-wide cursor-pointer hover:text-coffee-dark transition-colors">
                            Lupa Password?
                        </span>
                        @endif
                    </div> -->
                </div><!-- /.field-wrap password -->

                <!-- Login Button -->
                <div class="flex justify-center opacity-0 animate-slide-up"
                     style="animation-delay:0.6s; animation-fill-mode:forwards;">
                    <button type="submit" class="btn-login">LOGIN</button>
                </div>

            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const toggleImg = document.getElementById('toggle-pw-img');

            // Alamat gambar di dalam folder public/image/ Laravel
            const imgShow = "{{ asset('image/eye-show.png') }}"; // ikon mata → tampilkan password
            const imgHide = "{{ asset('image/eye-hide.png') }}"; // ikon mata → sembunyikan password

            if (input.type === 'password') {
                input.type = 'text';
                toggleImg.src = imgHide;
            } else {
                input.type = 'password';
                toggleImg.src = imgShow;
            }
        }

        ['email','password'].forEach(id => {
            document.getElementById(id).addEventListener('keydown', e => {
                if (e.key === 'Enter') document.querySelector('form').submit();
            });
        });
    </script>
</body>
</html>