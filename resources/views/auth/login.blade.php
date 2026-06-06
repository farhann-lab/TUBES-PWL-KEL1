<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELCO — Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700;1,900&family=Cormorant+Garamond:wght@300;400;600&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
        body {
            background: #FFFFFF;
            font-family: 'Montserrat', sans-serif;
            /* KRITIS: overflow harus auto/scroll, BUKAN hidden */
            overflow-x: hidden;
            overflow-y: auto;
        }

        .corner-img {
            position: absolute; /* absolute bukan fixed agar tidak menutupi About Us */
            pointer-events: none;
            z-index: 0;
        }

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

        body::after {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 999;
        }

        ::-webkit-scrollbar { display: none; }

        /* ── About Us styles ── */
        .about-slider-wrapper::-webkit-scrollbar { display: none; }
    </style>
</head>

{{-- KRITIS: overflow-hidden DIHAPUS. Body harus bisa di-scroll. --}}
<body class="relative">

    <!-- ═══════════════════════════════════════════════════════
         SECTION 1: LOGIN
         Tinggi tepat 100vh — penuh di layar pertama.
         Scroll down → ketemu About Us.
    ════════════════════════════════════════════════════════ -->
    <section class="login-section relative" style="height: 100vh; overflow: hidden;">

        <!-- Corner beans — absolute di dalam section login saja -->
        <img src="image/beansone.png"   alt="" class="corner-img" style="top:0;    left:0;  width:280px;" />
        <img src="image/beanstwo.png"   alt="" class="corner-img" style="bottom:0; left:0;  width:280px;" />
        <img src="image/beansthree.png" alt="" class="corner-img" style="bottom:0; right:0; width:280px;" />

        <!-- Login content — centered di dalam 100vh -->
        <div class="relative z-10 flex h-full items-center justify-center gap-8 px-8">

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
                <div class=" mb-8 opacity-0 animate-fade-in" style="animation-delay:0.1s; animation-fill-mode:forwards;">
                    <div class="flex items-center gap-3">
                         <img 
                        src="{{ asset('image/logo-elco.png') }}" 
                        alt="Logo ELCO"
                        class="w-[34px] h-[34px] object-contain scale-[1.2]] -mr-1"
                            />
                        <span class="font-display text-coffee-dark font-bold tracking-widest"
                              style="font-size:1.8rem; letter-spacing:0.15em;">ELCO.</span>
                    </div>
                </div>

                <!-- FORM -->
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
                            <img
                                src="{{ asset('image/eye-show.png') }}"
                                id="toggle-pw-img"
                                class="flex-shrink-0 cursor-pointer opacity-75 hover:opacity-100 transition-opacity"
                                onclick="togglePassword()"
                                alt="Toggle Password"
                                style="width: 28px; height: 28px; object-fit: contain;"
                            />
                        </div>
                    </div>

                    <!-- Login Button -->
                    <div class="flex justify-center opacity-0 animate-slide-up"
                         style="animation-delay:0.6s; animation-fill-mode:forwards;">
                        <button type="submit" class="btn-login">LOGIN</button>
                    </div>

                </form>
            </div>
        </div>

        <!-- Scroll hint arrow — subtle indicator ada konten di bawah -->
        <div 
        class="login-scroll-arrow" style="
            position: absolute;
            bottom: 28px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            animation: bounce 2s infinite;
            cursor: pointer;
            opacity: 0.5;
        " onclick="document.getElementById('about').scrollIntoView({behavior:'smooth'})">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                <path d="M6 9l6 6 6-6" stroke="#3D1A08" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

    </section><!-- /login-section -->


  {{-- ═══════════════════════════════════════════════════
     SECTION 2: ABOUT US (ala Tuku)
═══════════════════════════════════════════════════ --}}
<style>
    /* ====================================
   ABOUT HOVER EFFECT
==================================== */

#about {
    background: #C8B89A;
    transition:
        background-color 0.8s ease,
        color 0.8s ease;
}

#about.about-hover {
    background: #D98B3A;
}

/* Heading */

#about .about-title,
#about .about-hint,
#about .team-label,
#about .team-desc {
    transition:
        color 0.8s ease,
        opacity 0.8s ease;
}
.team-photo-wrap{
    position: relative;
}

.team-popup{
    position: absolute;
    bottom: 18px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 20;
    pointer-events: none;
}

.team-popup div{
    min-width: 90px;
    text-align: center;

    padding: 6px 14px;
    border-radius: 999px;

    font-size: 10px;
    letter-spacing: .15em;

    white-space: nowrap;
}

#about.about-hover .about-title {
    color: #FAF6EE;
}

#about.about-hover .about-title span {
    color: #FFF4E2;
}

#about.about-hover .about-hint {
    color: #FFF4E2;
}

/* Role */

#about.about-hover .team-label {
    color: #FAF6EE;
}

#about.about-hover .team-desc {
    color: rgba(255,255,255,.85);
}

/* Popup */

.team-popup div {
    transition:
        background-color .5s ease,
        color .5s ease;
}

#about.about-hover .team-popup div {
    background: #3D1A08;
    color: #FAF6EE;
}
#bottomFloatingBtn.show{
    opacity:1;
    pointer-events:auto;
}

#bottomFloatingBtn{
    transform:
        translateX(-50%)
        translateY(10px);
}

#bottomFloatingBtn.show{
    transform:
        translateX(-50%)
        translateY(0);
}
.team-photo-wrap img{
    width:280px;
    height:400px;
    object-fit:contain;
}
.team-card{
    transition: transform .45s cubic-bezier(.22,1,.36,1);
}

.team-photo-wrap img{
    transition:
        transform .45s cubic-bezier(.22,1,.36,1),
        filter .45s ease;
}

.team-card:hover{
    transform: translateY(-6px);
}

.team-card:hover img{
    transform: scale(1.05);
}
.login-scroll-arrow svg path{
    transition: stroke .35s ease;
}

.login-scroll-arrow:hover svg path{
    stroke: #D98B3A;
}
.about-scroll-up{
    position: fixed;
    top: 28px;
    left: 50%;

    transform:
        translateX(-50%)
        translateY(-10px);

    opacity: 0;

    transition:
        opacity .35s ease,
        transform .35s ease;

    cursor: pointer;
    z-index: 1000;
}

.about-scroll-up.show{
    opacity: .6;

    transform:
        translateX(-50%)
        translateY(0);
}

.about-scroll-up svg path{
    transition: stroke .35s ease;
}

.about-scroll-up:hover svg path{
    stroke: #C8B89A;
}
</style>

<section
    id="about"
    class="min-h-screen relative overflow-hidden transition-all duration-700 ease-in-out bg-[#C8B89A]"
>
    <div class="relative z-10 px-8 lg:px-16 py-20">
        <div class="mb-4 reveal" id="about-heading">
            <h2 class="about-title font-sans font-black text-4xl lg:text-6xl xl:text-7xl text-coffee-700 leading-none uppercase">
                BERAWAL DARI TEMAN,<br>
                <span class="text-coffee-600">MENJADI CEO</span>
            </h2>
        </div>

        <div class="mb-8 reveal reveal-delay-1" id="about-hint">
            <p class="about-hint text-coffee-600 text-sm tracking-[0.3em] uppercase font-medium opacity-70 flex items-center gap-2">
                <span>←</span>
                <span>GESER UNTUK LIHAT LAINNYA</span>
                <span>→</span>
            </p>
        </div>

        <div
    class="group reveal reveal-delay-2"
    id="about-slider"
>
            <div class="flex items-end overflow-x-auto hide-scrollbar pb-0 snap-x snap-mandatory">

                <!-- ROLE GROUP 1: MANAGER -->
                <div class="flex items-end gap-4 flex-shrink-0 mr-4 md:mr-6 snap-start">
                    <div class="team-card relative flex flex-col items-center flex-shrink-0 w-[260px]">
                        <div class="team-photo-wrap relative">

    <img
        src="{{ Vite::asset('resources/images/team/rafa-Photoroom.png') }}"
        alt="Manager"
        class="w-[300px] h-[430px] object-contain select-none pointer-events-none"
        draggable="false"
    >

    <div class="team-popup">
        <div class="rounded-full bg-[#3D1A08]/95 px-3 py-1.5 text-[10px] font-bold tracking-[0.15em] uppercase text-[#FAF6EE] shadow-lg">
            RAFA
        </div>
    </div>

</div>

                        <p class="team-label font-sans font-bold text-coffee-700 text-lg tracking-wide uppercase">
                            CEO MENYAMAR
                        </p>
                        <p class="team-desc font-sans text-coffee-500 text-xs mt-1 font-light">
                            Tukang Ngatur
                        </p>
                    </div>
                </div>

                <!-- ROLE GROUP 2: ADMIN -->
                <div class="flex items-end gap-4 flex-shrink-0 mr-4 md:mr-6 snap-start">
                    <div class="team-card relative flex flex-col items-center flex-shrink-0 w-[260px]">
                        <div class="team-photo-wrap">
                            <img
        src="{{ Vite::asset('resources/images/team/jo-Photoroom.png') }}"
        alt="Manager"
        class="w-[300px] h-[430px] object-contain select-none pointer-events-none"
        draggable="false"
    >

    <div class="team-popup">
        <div class="rounded-full bg-[#3D1A08]/95 px-3 py-1.5 text-[10px] font-bold tracking-[0.15em] uppercase text-[#FAF6EE] shadow-lg">
            JONATHAN
        </div>
    </div>
                        </div>

                        <p class="team-label font-sans font-bold text-coffee-700 text-lg tracking-wide uppercase">
                            Admin Padang Bulan
                        </p>
                        <p class="team-desc font-sans text-coffee-500 text-xs mt-1 font-light">
                           Si Kurang Tidur
                        </p>
                    </div>

                    <div class="team-card relative flex flex-col items-center flex-shrink-0 w-[260px]">
                        <div class="team-photo-wrap">
                            <img
        src="{{ Vite::asset('resources/images/team/farhan-Photoroom.png') }}"
        alt="Manager"
        class="w-[300px] h-[430px] object-contain select-none pointer-events-none"
        draggable="false"
    >

    <div class="team-popup">
        <div class="rounded-full bg-[#3D1A08]/95 px-3 py-1.5 text-[10px] font-bold tracking-[0.15em] uppercase text-[#FAF6EE] shadow-lg">
            FARHAN
        </div>
    </div>
                        </div>

                        <p class="team-label font-sans font-bold text-coffee-700 text-lg tracking-wide uppercase">
                            Admin Pancing
                        </p>
                        <p class="team-desc font-sans text-coffee-500 text-xs mt-1 font-light">
                            ATM Berjalan
                        </p>
                    </div>

                    <div class="team-card relative flex flex-col items-center flex-shrink-0 w-[260px]">
                        <div class="team-photo-wrap">
                            <img
        src="{{ Vite::asset('resources/images/team/syamil-Photoroom.png') }}"
        alt="Manager"
        class="w-[300px] h-[430px] object-contain select-none pointer-events-none"
        draggable="false"
    >

    <div class="team-popup">
        <div class="rounded-full bg-[#3D1A08]/95 px-3 py-1.5 text-[10px] font-bold tracking-[0.15em] uppercase text-[#FAF6EE] shadow-lg">
            SYAMIL
        </div>
    </div>
                        </div>

                        <p class="team-label font-sans font-bold text-coffee-700 text-lg tracking-wide uppercase">
                            KASIR
                        </p>
                        <p class="team-desc font-sans text-coffee-500 text-xs mt-1 font-light">
                            Tukang Ngambek
                        </p>
                    </div>
                </div>

                <!-- ROLE GROUP 3: KASIR -->
                <div class="flex items-end gap-4 flex-shrink-0 mr-0 snap-start">
                    <div class="team-card relative flex flex-col items-center flex-shrink-0 w-[260px]">
                        <div class="team-photo-wrap">
                            <img
        src="{{ Vite::asset('resources/images/team/kevin-Photoroom.png') }}"
        alt="Manager"
        class="w-[300px] h-[430px] object-contain select-none pointer-events-none"
        draggable="false"
    >

    <div class="team-popup">
        <div class="rounded-full bg-[#3D1A08]/95 px-3 py-1.5 text-[10px] font-bold tracking-[0.15em] uppercase text-[#FAF6EE] shadow-lg">
            KEVIN
        </div>
    </div>
                        </div>

                        <p class="team-label font-sans font-bold text-coffee-700 text-lg tracking-wide uppercase">
                            BARISTA
                        </p>
                        <p class="team-desc font-sans text-coffee-500 text-xs mt-1 font-light">
                            Si Paling Kopi
                        </p>
                    </div>

                    <div class="team-card relative flex flex-col items-center flex-shrink-0 w-[260px]">
                        <div class="team-photo-wrap">
                            <img
        src="{{ Vite::asset('resources/images/team/morris-Photoroom.png') }}"
        alt="Manager"
        class="w-[300px] h-[430px] object-contain select-none pointer-events-none"
        draggable="false"
    >

    <div class="team-popup">
        <div class="rounded-full bg-[#3D1A08]/95 px-3 py-1.5 text-[10px] font-bold tracking-[0.15em] uppercase text-[#FAF6EE] shadow-lg">
            MORRIS
        </div>
    </div>
                        </div>

                        <p class="team-label font-sans font-bold text-coffee-700 text-lg tracking-wide uppercase">
                            WAITERS
                        </p>
                        <p class="team-desc font-sans text-coffee-500 text-xs mt-1 font-light">
                            Pelayan Plenger
                        </p>
                    </div>

                    <div class="team-card relative flex flex-col items-center flex-shrink-0 w-[260px]">
                        <div class="team-photo-wrap relative">

    <img
        src="{{ Vite::asset('resources/images/team/diaz-Photoroom.png') }}"
        alt="Manager"
        class="w-[300px] h-[430px] object-contain select-none pointer-events-none"
        draggable="false"
    >

    <div class="team-popup">
        <div class="rounded-full bg-[#3D1A08]/95 px-3 py-1.5 text-[10px] font-bold tracking-[0.15em] uppercase text-[#FAF6EE] shadow-lg">
            DIAZ
        </div>
    </div>

</div>

                        <p class="team-label font-sans font-bold text-coffee-700 text-lg tracking-wide uppercase">
                            BARISTA
                        </p>
                        <p class="team-desc font-sans text-coffee-500 text-xs mt-1 font-light">
                            Barista Seram
                        </p>
                    </div>
                </div>

                <div class="flex-shrink-0 w-6 md:w-10"></div>
            </div>
        </div>
    </div>
    <button
    id="topFloatingBtn"
    type="button"
    class="fixed top-4 left-1/2 -translate-x-1/2 z-50
           opacity-0 pointer-events-none
           transition-all duration-500
           bg-white/80 backdrop-blur-sm
           border border-coffee-200
           rounded-full w-12 h-12
           flex items-center justify-center
           text-coffee-600 hover:text-coffee-800">

    <i class="ph ph-arrow-up text-xl"></i>

</button>
<button
    id="bottomFloatingBtn"
    type="button"
    class="fixed bottom-4 left-1/2 -translate-x-1/2 z-50
           opacity-0 pointer-events-none
           transition-all duration-500
           bg-white/80 backdrop-blur-sm
           border border-coffee-200
           rounded-full w-12 h-12
           flex items-center justify-center
           text-coffee-600 hover:text-coffee-800">

    <i class="ph ph-arrow-down text-xl bounce-arrow"></i>

</button>
<div
    id="aboutArrowUp"
    class="about-scroll-up"
    onclick="window.scrollTo({top:0,behavior:'smooth'})"
>
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
        <path
            d="M18 15l-6-6-6 6"
            stroke="#3D1A08"
            stroke-width="2.5"
            stroke-linecap="round"
            stroke-linejoin="round"
        />
    </svg>
</div>
</section>

@stack('scripts')

<script>
// ── IntersectionObserver for reveal animations ──────────
document.addEventListener('DOMContentLoaded', () => {
    const revealEls = document.querySelectorAll('.reveal, .reveal-left');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });
    revealEls.forEach(el => observer.observe(el));

    // Trigger elements already in viewport on load
    revealEls.forEach(el => {
        const rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight) {
            el.classList.add('visible');
        }
    });
});
document.addEventListener('DOMContentLoaded', () => {

    const about = document.getElementById('about');
    const slider = document.getElementById('about-slider');

    slider.addEventListener('mouseenter', () => {
        about.classList.add('about-hover');
    });

    slider.addEventListener('mouseleave', () => {
        about.classList.remove('about-hover');
    });

});
document.addEventListener('DOMContentLoaded', () => {

    const loginSection = document.getElementById('login');
    const aboutSection = document.getElementById('about');

const topBtn = document.getElementById('topFloatingBtn');
const aboutArrowUp =
    document.getElementById('aboutArrowUp');
const bottomBtn = document.getElementById('bottomFloatingBtn');

    const floatingBtn = document.getElementById('topFloatingBtn');

    let inAboutSection = false;

    function updateState() {

    const aboutTop =
        aboutSection.getBoundingClientRect().top;

    inAboutSection =
        aboutTop <= window.innerHeight * 0.35;

    if (!inAboutSection) {
        aboutArrowUp.classList.remove('show');
    }

    if (inAboutSection) {
        bottomBtn.classList.remove('show');
    }
}

    bottomBtn.addEventListener('click', () => {

    aboutSection.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });

});

topBtn.addEventListener('click', () => {

    loginSection.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });

});

    window.addEventListener('scroll', updateState);

    updateState();

    document.addEventListener('mousemove', (e) => {

    if (inAboutSection) {

        if (e.clientY < 100) {
    aboutArrowUp.classList.add('show');
} else {
            aboutArrowUp.classList.remove('show');
        }

    } else {

        if (e.clientY > window.innerHeight - 100) {
            bottomBtn.classList.add('show');
        } else {
            bottomBtn.classList.remove('show');
        }

    }

});

});
</script>
</body>
</html>