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
                        'coffee-dark':   '#3D1A08',
                        'coffee-medium': '#5C2D0E',
                        'coffee-brown':  '#7B3F00',
                        'gold-deep':     '#B8860B',
                        'gold-light':    '#C9A227',
                        'gold-pale':     '#D4A843',
                        'cream':         '#F5ECD7',
                        'cream-light':   '#FAF6EE',
                    },
                    fontFamily: {
                        'display': ['Playfair Display', 'serif'],
                        'serif':   ['Cormorant Garamond', 'serif'],
                        'sans':    ['Montserrat', 'sans-serif'],
                    },
                    keyframes: {
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

        .grain::after {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 999;
            opacity: 0.5;
        }

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
    </style>
</head>
<body class="grain font-sans w-screen h-screen select-none">

    <div class="relative flex h-screen w-screen overflow-hidden bg-white">

        <!-- ═══ LEFT HALF — bg.png + kopi.png overlay ═══ -->
        <div class="relative w-1/2 h-full flex items-start justify-start animate-slide-left overflow-hidden">

            <!-- Layer 1: bg.png (gold pill shape) — mengisi penuh area kiri -->
            <img
                src="image/bg.png"
                alt=""
                class="absolute inset-0 w-full h-fit object-cover"
            />

            <!-- Layer 2: kopi.png — ditimpa di atas bg.png -->
            <img
                src="image/kopi.png"
                alt="ELCO Iced Coffee"
                class="relative z-10 w-full h-full object-contain animate-splash-pulse"
            />

        </div>

        <!-- ═══ RIGHT HALF — Brand & CTA ═══ -->
        <div class="w-1/2 h-full flex flex-col items-center justify-center gap-10 animate-slide-right">

            <!-- ELCO Logo -->
            <div class="flex flex-col items-center gap-3 animate-fade-up">
                <div class="flex items-center gap-4">
                    <!-- Coffee cup icon -->
                    <svg width="68" height="68" viewBox="0 0 68 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M24 10 Q26 6 24 2" stroke="#3D1A08" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                        <path d="M34 10 Q36 6 34 2" stroke="#3D1A08" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                        <path d="M44 10 Q46 6 44 2" stroke="#3D1A08" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                        <rect x="14" y="16" width="40" height="36" rx="4" stroke="#3D1A08" stroke-width="3" fill="none"/>
                        <rect x="22" y="24" width="24" height="20" rx="2" stroke="#3D1A08" stroke-width="2.5" fill="none"/>
                        <path d="M54 24 Q64 24 64 34 Q64 44 54 44" stroke="#3D1A08" stroke-width="3" stroke-linecap="round" fill="none"/>
                        <path d="M8 52 Q34 62 60 52" stroke="#3D1A08" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                    </svg>
                    <!-- Wordmark -->
                    <span class="font-display text-coffee-dark tracking-widest select-none"
                          style="font-size:clamp(2.8rem,5vw,4.2rem); font-weight:700; letter-spacing:0.18em; line-height:1;">
                        ELCO.
                    </span>
                </div>
                <!-- Tagline -->
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