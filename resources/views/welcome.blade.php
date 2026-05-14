<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELCO — Premium Coffee</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=Cormorant+Garamond:wght@300;400;600;700&family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'coffee-dark':    '#3D1A08',
                        'coffee-medium':  '#5C2D0E',
                        'coffee-brown':   '#7B3F00',
                        'gold-deep':      '#B8860B',
                        'gold-light':     '#C9A227',
                        'gold-pale':      '#D4A843',
                        'cream':          '#F5ECD7',
                        'cream-light':    '#FAF6EE',
                    },
                    fontFamily: {
                        'display':  ['Playfair Display', 'serif'],
                        'serif':    ['Cormorant Garamond', 'serif'],
                        'sans':     ['Montserrat', 'sans-serif'],
                    },
                    keyframes: {
                        'float-bean': {
                            '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
                            '33%':      { transform: 'translateY(-18px) rotate(8deg)' },
                            '66%':      { transform: 'translateY(-8px) rotate(-5deg)' },
                        },
                        'float-bean-2': {
                            '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
                            '50%':      { transform: 'translateY(-22px) rotate(-12deg)' },
                        },
                        'float-bean-3': {
                            '0%, 100%': { transform: 'translateY(0px) rotate(10deg)' },
                            '40%':      { transform: 'translateY(-14px) rotate(25deg)' },
                            '80%':      { transform: 'translateY(-6px) rotate(5deg)' },
                        },
                        'splash-pulse': {
                            '0%, 100%': { transform: 'scale(1)' },
                            '50%':      { transform: 'scale(1.03)' },
                        },
                        'slide-in-left': {
                            '0%':   { transform: 'translateX(-100px)', opacity: '0' },
                            '100%': { transform: 'translateX(0)',       opacity: '1' },
                        },
                        'slide-in-right': {
                            '0%':   { transform: 'translateX(100px)', opacity: '0' },
                            '100%': { transform: 'translateX(0)',      opacity: '1' },
                        },
                        'fade-up': {
                            '0%':   { transform: 'translateY(30px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)',     opacity: '1' },
                        },
                        'btn-glow': {
                            '0%, 100%': { 'box-shadow': '0 0 0 0 rgba(61,26,8,0)' },
                            '50%':      { 'box-shadow': '0 0 24px 6px rgba(61,26,8,0.22)' },
                        },
                    },
                    animation: {
                        'float-bean':   'float-bean 5s ease-in-out infinite',
                        'float-bean-2': 'float-bean-2 6.5s ease-in-out infinite',
                        'float-bean-3': 'float-bean-3 4.8s ease-in-out infinite',
                        'splash-pulse': 'splash-pulse 4s ease-in-out infinite',
                        'slide-left':   'slide-in-left 0.85s cubic-bezier(0.22,1,0.36,1) both',
                        'slide-right':  'slide-in-right 0.85s cubic-bezier(0.22,1,0.36,1) 0.2s both',
                        'fade-up':      'fade-up 0.7s ease-out 0.5s both',
                        'btn-glow':     'btn-glow 2.4s ease-in-out infinite',
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #FFFFFF; overflow: hidden; }

        /* Gold pill shape behind the coffee image */
        .hero-pill {
            background: linear-gradient(155deg, #C9A227 0%, #B8860B 45%, #8B6914 100%);
            border-radius: 60% 60% 55% 55% / 50% 50% 50% 50%;
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 82%;
            height: 105%;
            z-index: 0;
        }

        /* Subtle grain texture overlay */
        .grain::after {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 999;
            opacity: 0.5;
        }

        /* Custom scrollbar hidden */
        ::-webkit-scrollbar { display: none; }

        .btn-start {
            position: relative;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-start::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.12) 0%, transparent 60%);
            opacity: 0; transition: opacity 0.3s;
        }
        .btn-start:hover::before { opacity: 1; }
        .btn-start:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 12px 32px rgba(61,26,8,0.35);
        }
        .btn-start:active {
            transform: translateY(0) scale(0.98);
            box-shadow: 0 4px 12px rgba(61,26,8,0.25);
        }

        /* ELCO logo icon SVG */
        .logo-icon rect, .logo-icon path { fill: #3D1A08; }
    </style>
</head>
<body class="grain font-sans w-screen h-screen select-none">

    <div class="relative flex h-screen w-screen overflow-hidden bg-white">

        <!-- ═══ LEFT HALF — Hero Visual ═══ -->
        <div class="relative w-1/2 h-full flex items-center justify-center animate-slide-left">

            <!-- Gold pill backdrop -->
            <div class="hero-pill shadow-2xl"></div>

            <!-- Coffee splash image wrapper -->
            <div class="relative z-10 animate-splash-pulse" style="width:72%; max-width:480px;">
                <img
                    src="{{ asset('images/coffee-splash.png') }}"
                    alt="ELCO Iced Coffee"
                    class="w-full h-auto object-contain drop-shadow-2xl"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                />
                <!-- Fallback placeholder when image is missing (dev mode) -->
                <div style="display:none;" class="w-full aspect-square rounded-full bg-gradient-to-br from-yellow-200 to-yellow-600 flex items-center justify-center">
                    <span class="text-white/70 text-6xl">☕</span>
                </div>

                <!-- Floating beans (CSS-only, positioned around splash) -->
                <div class="absolute animate-float-bean"   style="top:-12%; left:18%;  width:9%">
                    <svg viewBox="0 0 60 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="30" cy="20" rx="28" ry="17" fill="#5C2D0E"/>
                        <path d="M30 4 Q38 20 30 36 Q22 20 30 4Z" fill="#3D1A08" opacity="0.55"/>
                    </svg>
                </div>
                <div class="absolute animate-float-bean-2" style="top:-18%; left:56%; width:8%">
                    <svg viewBox="0 0 60 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="30" cy="20" rx="28" ry="17" fill="#4A2008"/>
                        <path d="M30 4 Q38 20 30 36 Q22 20 30 4Z" fill="#2E1005" opacity="0.55"/>
                    </svg>
                </div>
                <div class="absolute animate-float-bean-3" style="top:14%;  left:-8%;  width:10%">
                    <svg viewBox="0 0 60 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="30" cy="20" rx="28" ry="17" fill="#6B3410"/>
                        <path d="M30 4 Q38 20 30 36 Q22 20 30 4Z" fill="#3D1A08" opacity="0.5"/>
                    </svg>
                </div>
                <div class="absolute animate-float-bean"   style="top:42%;  left:-12%; width:8%;  animation-delay:1.2s">
                    <svg viewBox="0 0 60 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="30" cy="20" rx="28" ry="17" fill="#7B3F00"/>
                        <path d="M30 4 Q38 20 30 36 Q22 20 30 4Z" fill="#3D1A08" opacity="0.5"/>
                    </svg>
                </div>
                <div class="absolute animate-float-bean-2" style="bottom:2%; left:8%;  width:9%;  animation-delay:0.8s">
                    <svg viewBox="0 0 60 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="30" cy="20" rx="28" ry="17" fill="#5C2D0E"/>
                        <path d="M30 4 Q38 20 30 36 Q22 20 30 4Z" fill="#2E1005" opacity="0.55"/>
                    </svg>
                </div>
                <div class="absolute animate-float-bean-3" style="bottom:-8%; left:48%; width:8%;  animation-delay:1.8s">
                    <svg viewBox="0 0 60 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="30" cy="20" rx="28" ry="17" fill="#4A2008"/>
                        <path d="M30 4 Q38 20 30 36 Q22 20 30 4Z" fill="#2E1005" opacity="0.5"/>
                    </svg>
                </div>
                <div class="absolute animate-float-bean"   style="bottom:10%; right:-10%; width:11%; animation-delay:2.2s">
                    <svg viewBox="0 0 60 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="30" cy="20" rx="28" ry="17" fill="#6B3410"/>
                        <path d="M30 4 Q38 20 30 36 Q22 20 30 4Z" fill="#3D1A08" opacity="0.55"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- ═══ RIGHT HALF — Brand & CTA ═══ -->
        <div class="w-1/2 h-full flex flex-col items-center justify-center gap-10 animate-slide-right">

            <!-- ELCO Logo -->
            <div class="flex flex-col items-center gap-3 animate-fade-up">
                <!-- Icon + wordmark -->
                <div class="flex items-center gap-4">
                    <!-- Coffee cup icon (SVG) -->
                    <svg class="logo-icon" width="68" height="68" viewBox="0 0 68 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- steam lines -->
                        <path d="M24 10 Q26 6 24 2" stroke="#3D1A08" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                        <path d="M34 10 Q36 6 34 2" stroke="#3D1A08" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                        <path d="M44 10 Q46 6 44 2" stroke="#3D1A08" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                        <!-- cup body -->
                        <rect x="14" y="16" width="40" height="36" rx="4" stroke="#3D1A08" stroke-width="3" fill="none"/>
                        <!-- cup inner rectangle -->
                        <rect x="22" y="24" width="24" height="20" rx="2" stroke="#3D1A08" stroke-width="2.5" fill="none"/>
                        <!-- handle -->
                        <path d="M54 24 Q64 24 64 34 Q64 44 54 44" stroke="#3D1A08" stroke-width="3" stroke-linecap="round" fill="none"/>
                        <!-- saucer -->
                        <path d="M8 52 Q34 62 60 52" stroke="#3D1A08" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                    </svg>
                    <!-- Wordmark -->
                    <span class="font-display text-coffee-dark tracking-widest select-none"
                          style="font-size:clamp(2.8rem,5vw,4.2rem); font-weight:700; letter-spacing:0.18em; line-height:1;">
                        ELCO.
                    </span>
                </div>
                <!-- Tagline (subtle) -->
                <p class="font-serif text-coffee-medium/60 tracking-[0.4em] text-sm uppercase mt-1">
                    Premium Coffee Experience
                </p>
            </div>

            <!-- CTA Button -->
            <a href="{{ route('login') }}"
               class="btn-start animate-btn-glow font-sans font-bold tracking-[0.22em] text-white
                      bg-coffee-dark px-16 py-4 rounded-xl text-sm uppercase
                      shadow-lg cursor-pointer">
                START NOW
            </a>
        </div>

    </div>
</body>
</html>