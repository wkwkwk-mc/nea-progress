<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Development Progress - Dark Storm Village</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Atmosphere & Lighting Animations */
        @keyframes floatCloud {
            0% { transform: translateX(-20%); }
            100% { transform: translateX(120vw); }
        }
        @keyframes chimneySmoke {
            0% { transform: translateY(0) scale(0.5) rotate(0deg); opacity: 0.8; filter: blur(2px); }
            50% { opacity: 0.5; filter: blur(6px); }
            100% { transform: translateY(-70px) scale(2.5) rotate(25deg); opacity: 0; filter: blur(12px); }
        }
        @keyframes windowFlicker {
            0%, 100% { opacity: 0.85; filter: drop-shadow(0 0 6px rgba(245, 158, 11, 0.6)); }
            25% { opacity: 0.6; filter: drop-shadow(0 0 3px rgba(245, 158, 11, 0.3)); }
            50% { opacity: 1; filter: drop-shadow(0 0 14px rgba(245, 158, 11, 0.9)); }
            75% { opacity: 0.75; filter: drop-shadow(0 0 5px rgba(245, 158, 11, 0.5)); }
        }
        @keyframes rotateWindmill {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes floatParticle {
            0%, 100% { transform: translateY(0) translateX(0); opacity: 0.2; }
            50% { transform: translateY(-30px) translateX(15px); opacity: 0.8; }
        }

        /* Realistic Anomaly Portal Fluid Animations */
        @keyframes portalFluid {
            0% { border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%; transform: rotate(0deg) scale(1); }
            50% { border-radius: 70% 30% 50% 50% / 30% 30% 70% 70%; transform: rotate(180deg) scale(1.1); }
            100% { border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%; transform: rotate(360deg) scale(1); }
        }
        @keyframes warningPulse {
            0%, 100% { opacity: 1; filter: drop-shadow(0 0 12px rgba(239, 68, 68, 0.9)); }
            50% { opacity: 0.5; filter: drop-shadow(0 0 3px rgba(239, 68, 68, 0.3)); }
        }

        /* Utility Classes */
        .animate-cloud-slow { animation: floatCloud 65s linear infinite; }
        .animate-cloud-fast { animation: floatCloud 35s linear infinite; }
        .animate-smoke-1 { animation: chimneySmoke 4s cubic-bezier(0.4, 0, 0.2, 1) infinite; }
        .animate-smoke-2 { animation: chimneySmoke 4s cubic-bezier(0.4, 0, 0.2, 1) 2s infinite; }
        .animate-window { animation: windowFlicker 3s ease-in-out infinite; }
        .animate-windmill { transform-origin: 1010px 220px; animation: rotateWindmill 12s linear infinite; }
        .animate-particle-1 { animation: floatParticle 8s ease-in-out infinite; }
        .animate-particle-2 { animation: floatParticle 10s ease-in-out 3s infinite; }
        
        .animate-portal-fluid { animation: portalFluid 8s ease-in-out infinite; }
        .animate-portal-fluid-reverse { animation: portalFluid 5s ease-in-out infinite reverse; }
        .animate-warning { animation: warningPulse 1.8s ease-in-out infinite; }

        /* Lightning Flash Overlay Class */
        .lightning-flash {
            background-color: rgba(235, 243, 255, 0.35) !important;
            filter: brightness(1.8) contrast(1.2);
            transition: background-color 0.05s ease-out;
        }
    </style>
</head>
<body class="bg-slate-950 min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-10 font-sans antialiased text-slate-100 relative overflow-hidden select-none">

    <!-- Storm Atmosphere & Background Canvas Layer -->
    <div id="atmosphere" class="fixed inset-0 pointer-events-none z-0 transition-all duration-75">
        <!-- Storm Sky Gradient -->
        <div class="absolute inset-0 bg-gradient-to-b from-slate-950 via-slate-900 to-indigo-950/70"></div>

        <!-- Lightning Visual Canvas -->
        <canvas id="lightningCanvas" class="absolute inset-0 w-full h-full z-10"></canvas>

        <!-- Dynamic Rain Canvas Layer -->
        <canvas id="rainCanvas" class="absolute inset-0 w-full h-full z-10 opacity-60"></canvas>

        <!-- Floating Magic Spores / Rain Particles -->
        <div class="absolute top-1/3 left-1/4 w-2 h-2 rounded-full bg-amber-300/60 blur-[1px] animate-particle-1"></div>
        <div class="absolute top-1/2 left-2/3 w-1.5 h-1.5 rounded-full bg-cyan-300/60 blur-[1px] animate-particle-2"></div>

        <!-- Animated Storm Clouds Silhouettes -->
        <svg class="absolute -top-10 left-0 w-[300px] sm:w-[500px] text-slate-900/80 animate-cloud-slow blur-[1px]" fill="currentColor" viewBox="0 0 24 24">
            <path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96z"/>
        </svg>
        <svg class="absolute top-6 left-0 w-[450px] sm:w-[700px] text-slate-950/90 animate-cloud-fast blur-[2px]" fill="currentColor" viewBox="0 0 24 24">
            <path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96z"/>
        </svg>

        <!-- High Detailed Village Landscape SVG -->
        <svg class="absolute bottom-0 w-full h-[320px] sm:h-[480px] md:h-[550px] text-slate-950 z-10" preserveAspectRatio="none" viewBox="0 0 1200 400" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- Far Distant Mountain Range -->
            <path d="M0 400 L0 240 L120 190 L280 270 L450 140 L620 260 L800 130 L1020 250 L1200 180 L1200 400 Z" fill="#090d16" opacity="0.9"/>
            <!-- Mid Mountain Range & Forest Outline -->
            <path d="M0 400 L0 290 L160 210 L340 310 L550 180 L760 310 L980 200 L1200 270 L1200 400 Z" fill="#0f172a" opacity="0.95"/>

            <!-- Pine Forest Silhouettes Behind Village -->
            <path d="M 30 350 L 40 310 L 50 350 M 45 350 L 55 295 L 65 350 M 180 340 L 190 290 L 200 340 M 720 340 L 730 280 L 740 340 M 745 340 L 755 295 L 765 340 M 890 350 L 900 300 L 910 350" stroke="#090d16" stroke-width="6" stroke-linecap="round"/>

            <!-- Left Cozy House & Chimney -->
            <polygon points="70,330 135,250 200,330" fill="#1e293b"/>
            <rect x="85" y="330" width="100" height="70" fill="#0f172a"/>
            <!-- Timber Beam Detail -->
            <line x1="85" y1="330" x2="185" y2="330" stroke="#334155" stroke-width="3"/>
            <!-- Chimney Smoke -->
            <rect x="160" y="240" width="14" height="30" fill="#0f172a"/>
            <circle cx="167" cy="230" r="6" class="fill-slate-400/40 animate-smoke-1"/>
            <circle cx="170" cy="220" r="9" class="fill-slate-400/30 animate-smoke-2"/>

            <!-- Center Gothic Cathedral / Tower -->
            <polygon points="280,350 325,180 370,350" fill="#1e1b4b"/>
            <rect x="295" y="320" width="60" height="80" fill="#0f172a"/>
            <polygon points="325,180 320,130 330,130" fill="#312e81"/> <!-- Spire Tip -->

            <!-- Central Village Manor & Secondary Cottage -->
            <polygon points="480,320 550,230 620,320" fill="#1e293b"/>
            <rect x="495" y="320" width="110" height="80" fill="#0f172a"/>
            <polygon points="600,340 655,270 710,340" fill="#312e81"/>
            <rect x="615" y="340" width="80" height="60" fill="#0f172a"/>
            <!-- Central Chimney Smoke -->
            <rect x="580" y="220" width="12" height="30" fill="#0f172a"/>
            <circle cx="586" cy="210" r="7" class="fill-slate-400/40 animate-smoke-2"/>

            <!-- Detailed Windmill Silhouette with Rotating Blades -->
            <polygon points="980,360 1010,220 1040,360" fill="#0f172a"/>
            <circle cx="1010" cy="220" r="7" fill="#38bdf8"/>
            <g class="animate-windmill" stroke="#38bdf8" stroke-width="3">
                <line x1="1010" y1="220" x2="1010" y2="150"/>
                <line x1="1010" y1="220" x2="1010" y2="290"/>
                <line x1="1010" y1="220" x2="940" y2="220"/>
                <line x1="1010" y1="220" x2="1080" y2="220"/>
            </g>

            <!-- Warm Glowing Windows & Light Reflections -->
            <rect x="105" y="345" width="16" height="22" rx="3" class="fill-amber-400 animate-window"/>
            <rect x="145" y="345" width="16" height="22" rx="3" class="fill-amber-400 animate-window"/>
            <!-- Cathedral Stained Glass Arch Window -->
            <path d="M 315 250 A 10 10 0 0 1 335 250 L 335 280 L 315 280 Z" class="fill-amber-300/90 animate-window"/>
            <!-- House Windows -->
            <rect x="515" y="340" width="20" height="28" rx="3" class="fill-amber-400 animate-window"/>
            <rect x="560" y="340" width="20" height="28" rx="3" class="fill-amber-400 animate-window"/>
            <rect x="640" y="355" width="16" height="22" rx="2" class="fill-amber-300 animate-window"/>

            <!-- Lantern Lights Ambient Glow Circles -->
            <circle cx="113" cy="356" r="25" fill="url(#lanternGlow)" opacity="0.3" class="animate-window"/>
            <circle cx="525" cy="354" r="30" fill="url(#lanternGlow)" opacity="0.35" class="animate-window"/>

            <!-- Gradient Definition for Warm Ambient Lights -->
            <defs>
                <radialGradient id="lanternGlow" cx="50%" cy="50%" r="50%">
                    <stop offset="0%" stop-color="#fbbf24" stop-opacity="1"/>
                    <stop offset="100%" stop-color="#fbbf24" stop-opacity="0"/>
                </radialGradient>
            </defs>

            <!-- Ground Base Line -->
            <rect x="0" y="375" width="1200" height="25" fill="#020617"/>
        </svg>
    </div>

    <!-- Audio Control Toggle -->
    <button id="audioToggleBtn" onclick="toggleAudio()" class="fixed top-4 right-4 sm:top-6 sm:right-6 z-40 p-2.5 sm:p-3 bg-slate-900/80 border border-slate-700/60 hover:border-slate-500 rounded-xl sm:rounded-2xl backdrop-blur-md text-slate-300 hover:text-white transition-all shadow-lg flex items-center gap-2 group cursor-pointer">
        <svg id="audioIconOff" class="w-4 h-4 sm:w-5 sm:h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
        </svg>
        <svg id="audioIconOn" class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M18.364 5.636a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
        </svg>
        <span id="audioStatusText" class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider hidden sm:block">Enable Audio</span>
    </button>

    <!-- RED PORTAL ANOMALY (Interactive & Upgraded Realism) -->
    <button onclick="openAnomalyModal()" class="absolute top-4 left-4 sm:top-10 sm:left-10 flex flex-col items-center justify-center z-30 group hover:scale-105 transition-transform duration-300 cursor-pointer focus:outline-none">
        <!-- Warning Label -->
        <div class="mb-2 sm:mb-4 px-2 sm:px-3 py-1 sm:py-1.5 bg-black/80 border border-red-500/60 rounded-lg backdrop-blur-md animate-warning flex items-center gap-1.5 shadow-[0_0_15px_rgba(220,38,38,0.5)]">
            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span class="text-[8px] sm:text-[10px] md:text-xs font-black text-red-400 tracking-widest uppercase">
                Anomaly Detected
            </span>
        </div>
        
        <!-- Realistic Swirling Portal -->
        <div class="relative w-16 h-24 sm:w-20 sm:h-28 flex items-center justify-center">
            <!-- Outer Deep Glow -->
            <div class="absolute inset-[-30%] rounded-full bg-red-700/20 blur-2xl animate-pulse"></div>
            <!-- Event Horizon Swirl -->
            <div class="absolute inset-0 border-[3px] border-red-500/70 animate-portal-fluid shadow-[0_0_25px_rgba(220,38,38,0.9)] bg-gradient-to-br from-red-900/80 via-black/40 to-transparent backdrop-blur-sm mix-blend-screen"></div>
            <!-- Inner Vortex -->
            <div class="absolute inset-1 sm:inset-2 bg-black border border-red-600/80 animate-portal-fluid-reverse shadow-[inset_0_0_20px_rgba(220,38,38,1),0_0_10px_rgba(239,68,68,0.8)] flex items-center justify-center"></div>
            <!-- White Hot Core -->
            <div class="absolute inset-6 sm:inset-8 bg-white/90 rounded-full shadow-[0_0_20px_#fff,0_0_60px_#ef4444] animate-pulse blur-[1px]"></div>
            <!-- Energy Particles (Pseudo) -->
            <div class="absolute inset-2 border border-white/20 animate-portal-fluid mix-blend-overlay"></div>
        </div>
    </button>

    <!-- Anomaly Modal -->
    <div id="anomalyModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md p-4 transition-opacity duration-300">
        <div class="bg-slate-900 border border-red-500/40 shadow-[0_0_50px_rgba(220,38,38,0.25)] rounded-3xl p-6 sm:p-8 max-w-sm w-full text-center relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-red-600/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-red-900/40 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10">
                <div class="mx-auto w-16 h-16 mb-5 flex items-center justify-center rounded-2xl bg-red-500/10 border border-red-500/30 text-red-500 shadow-[0_0_15px_rgba(220,38,38,0.4)]">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                
                <h3 class="text-xl sm:text-2xl font-black text-white tracking-wide mb-2 uppercase">Anomaly Alert</h3>
                <p class="text-sm font-medium text-slate-300 mb-8 leading-relaxed">
                    There's an unidentified object landed to our world and wait for the next update.
                </p>
                
                <button onclick="closeAnomalyModal()" class="w-full py-3 px-4 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 text-white text-sm font-bold tracking-wider uppercase rounded-xl shadow-lg shadow-red-600/30 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-slate-900 cursor-pointer">
                    See Progress
                </button>
            </div>
        </div>
    </div>

    @php
        $progressData = [
            'new_features' => [
                'label' => 'New Features',
                'done' => 0,
                'total' => 9,
                'light_color' => 'bg-emerald-950/60',
                'strong_color' => 'bg-gradient-to-r from-emerald-500 to-teal-400'
            ],
            'modified_features' => [
                'label' => 'Modified Features',
                'done' => 4,
                'total' => 7,
                'light_color' => 'bg-blue-950/60',
                'strong_color' => 'bg-gradient-to-r from-blue-500 to-cyan-400'
            ],
            'fixed_bugs' => [
                'label' => 'Fixed Bugs',
                'done' => 3,
                'total' => 3,
                'light_color' => 'bg-rose-950/60',
                'strong_color' => 'bg-gradient-to-r from-rose-500 to-pink-500'
            ],
        ];

        $totalPercentageSum = 0;
        $categoryCount = count($progressData);

        foreach ($progressData as $item) {
            $itemPct = $item['total'] > 0 ? ($item['done'] / $item['total']) * 100 : 0;
            $totalPercentageSum += $itemPct;
        }

        $overallAverage = $categoryCount > 0 ? round($totalPercentageSum / $categoryCount) : 0;
    @endphp

    <!-- Glassmorphism Container UI (Responsive) -->
    <div class="relative z-20 max-w-lg w-full bg-slate-900/65 backdrop-blur-2xl rounded-3xl shadow-[0_16px_50px_rgba(0,0,0,0.8)] border border-white/10 p-5 sm:p-8 mt-28 sm:mt-0 mx-4 transition-all duration-300">
        
        <!-- Header with Dynamic Icon -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3 sm:gap-4">
                <!-- User Requested App Icon -->
                <img src="/nea-app-icon.png" alt="Nea App Icon" class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl shadow-lg drop-shadow-[0_0_10px_rgba(255,255,255,0.15)] object-cover bg-slate-800/80 border border-white/10">
                <div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-200 to-slate-400 tracking-tight leading-tight">
                        Nea: Virtual Cat Companion
                    </h1>
                    <p class="text-indigo-200/70 text-[10px] sm:text-xs md:text-sm font-medium mt-0.5 sm:mt-1">
                        Fun Features Pt. 1
                    </p>
                </div>
            </div>
            
            <div class="hidden sm:flex h-10 w-10 rounded-2xl bg-indigo-500/10 border border-indigo-400/20 items-center justify-center text-indigo-300 shadow-inner flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6M10 14h4m-7 7h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>

        <!-- Overall Average Calculation Box -->
        <div class="mb-6 sm:mb-8 p-3 sm:p-4 rounded-2xl bg-white/[0.03] border border-white/10 backdrop-blur-md hover:border-indigo-500/30 transition-all">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-indigo-300/80">
                    Total Progress Average
                </span>
                <span class="text-lg sm:text-xl font-extrabold text-white tracking-tight">
                    {{ $overallAverage }}%
                </span>
            </div>
            
            <div class="w-full h-2.5 sm:h-3 rounded-full bg-slate-950/80 overflow-hidden p-[1px] sm:p-0.5 border border-white/5">
                <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 transition-all duration-700 shadow-[0_0_12px_rgba(99,102,241,0.6)]"
                     style="width: {{ $overallAverage }}%"></div>
            </div>
        </div>

        <!-- Individual Progress Bars -->
        <div class="space-y-4 sm:space-y-6">
            @foreach($progressData as $key => $item)
                @php
                    $percentage = $item['total'] > 0 ? round(($item['done'] / $item['total']) * 100) : 0;
                @endphp

                <div class="progress-container group cursor-pointer" onmouseenter="playHoverSFX()">
                    <div class="flex justify-between items-center mb-1.5 sm:mb-2 px-1">
                        <span class="text-xs sm:text-sm font-semibold tracking-wide text-slate-200 group-hover:text-white transition-colors">
                            {{ $item['label'] }}
                        </span>
                    </div>

                    <div class="relative w-full h-6 sm:h-7 rounded-xl overflow-hidden border border-white/10 {{ $item['light_color'] }} flex items-center shadow-inner">
                        <div class="h-full transition-all duration-500 rounded-lg {{ $item['strong_color'] }}"
                             style="width: {{ $percentage }}%"></div>

                        <div class="absolute inset-0 flex items-center justify-between px-3 text-[10px] sm:text-xs font-bold text-white drop-shadow-[0_1px_2px_rgba(0,0,0,0.9)] pointer-events-none">
                            <span>
                                {{ $item['done'] }} / {{ $item['total'] }}
                            </span>
                            <span>
                                {{ $percentage }}%
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <!-- AUDIO ENGINE & ATMOSPHERIC LIGHTNING SCRIPT -->
    <script>
        /* -------------------------------------------------------------
         * 1. WEB AUDIO API SYNTHESIZER (NO EXTERNAL AUDIO FILES NEEDED)
         * ------------------------------------------------------------- */
        let audioCtx = null;
        let isAudioEnabled = false;
        let windGainNode = null;

        function initAudioContext() {
            if (!audioCtx) {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                audioCtx = new AudioContext();
                setupAmbientWind();
            }
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }
        }

        function toggleAudio() {
            initAudioContext();
            isAudioEnabled = !isAudioEnabled;

            const iconOff = document.getElementById('audioIconOff');
            const iconOn = document.getElementById('audioIconOn');
            const statusText = document.getElementById('audioStatusText');

            if (isAudioEnabled) {
                iconOff.classList.add('hidden');
                iconOn.classList.remove('hidden');
                statusText.innerText = 'Audio On';
                if (windGainNode) windGainNode.gain.setTargetAtTime(0.15, audioCtx.currentTime, 1);
                playUiClickSFX(800, 0.1);
            } else {
                iconOn.classList.add('hidden');
                iconOff.classList.remove('hidden');
                statusText.innerText = 'Enable Audio';
                if (windGainNode) windGainNode.gain.setTargetAtTime(0, audioCtx.currentTime, 0.5);
            }
        }

        /* Ambient Wind Generator */
        function setupAmbientWind() {
            const bufferSize = audioCtx.sampleRate * 2;
            const noiseBuffer = audioCtx.createBuffer(1, bufferSize, audioCtx.sampleRate);
            const output = noiseBuffer.getChannelData(0);
            for (let i = 0; i < bufferSize; i++) {
                output[i] = Math.random() * 2 - 1;
            }

            const whiteNoise = audioCtx.createBufferSource();
            whiteNoise.buffer = noiseBuffer;
            whiteNoise.loop = true;

            const filter = audioCtx.createBiquadFilter();
            filter.type = 'lowpass';
            filter.frequency.value = 350;

            windGainNode = audioCtx.createGain();
            windGainNode.gain.value = 0;

            whiteNoise.connect(filter);
            filter.connect(windGainNode);
            windGainNode.connect(audioCtx.destination);
            whiteNoise.start();
        }

        /* Procedural Thunder Sound Effect Synthesizer */
        function playThunderSFX() {
            if (!isAudioEnabled || !audioCtx) return;

            const now = audioCtx.currentTime;

            // Thunder Rumble Noise
            const bufferSize = audioCtx.sampleRate * 3;
            const buffer = audioCtx.createBuffer(1, bufferSize, audioCtx.sampleRate);
            const data = buffer.getChannelData(0);
            for (let i = 0; i < bufferSize; i++) {
                data[i] = (Math.random() * 2 - 1) * Math.exp(-i / (audioCtx.sampleRate * 0.8));
            }

            const noise = audioCtx.createBufferSource();
            noise.buffer = buffer;

            const filter = audioCtx.createBiquadFilter();
            filter.type = 'lowpass';
            filter.frequency.setValueAtTime(180, now);
            filter.frequency.exponentialRampToValueAtTime(40, now + 2.5);

            const gain = audioCtx.createGain();
            gain.gain.setValueAtTime(0.01, now);
            gain.gain.linearRampToValueAtTime(0.9, now + 0.08); // Sharp strike burst
            gain.gain.exponentialRampToValueAtTime(0.001, now + 2.8);

            noise.connect(filter);
            filter.connect(gain);
            gain.connect(audioCtx.destination);

            noise.start(now);
        }

        /* UI Sound Effects */
        function playUiClickSFX(freq = 600, duration = 0.08) {
            if (!isAudioEnabled || !audioCtx) return;
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
            gain.gain.setValueAtTime(0.1, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + duration);

            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start();
            osc.stop(audioCtx.currentTime + duration);
        }

        function playHoverSFX() {
            if (isAudioEnabled) playUiClickSFX(400, 0.04);
        }

        function openAnomalyModal() {
            playUiClickSFX(250, 0.2);
            document.getElementById('anomalyModal').classList.remove('hidden');
        }

        function closeAnomalyModal() {
            playUiClickSFX(500, 0.1);
            document.getElementById('anomalyModal').classList.add('hidden');
        }


        /* -------------------------------------------------------------
         * 2. REALISTIC LIGHTNING CANVAS & ATMOSPHERIC FLASH SYSTEM
         * ------------------------------------------------------------- */
        const canvas = document.getElementById('lightningCanvas');
        const ctx = canvas.getContext('2d');
        const atmosphere = document.getElementById('atmosphere');

        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        function drawLightningBolt(startX, startY, endX, endY, branchDepth) {
            if (branchDepth <= 0) return;

            let currentX = startX;
            let currentY = startY;

            ctx.beginPath();
            ctx.moveTo(currentX, currentY);

            const segments = 12;
            for (let i = 0; i < segments; i++) {
                const targetY = startY + ((endY - startY) / segments) * (i + 1);
                const targetX = currentX + (Math.random() - 0.5) * 45;

                ctx.lineTo(targetX, targetY);

                // Branching logic
                if (Math.random() < 0.25 && branchDepth > 1) {
                    drawLightningBolt(targetX, targetY, targetX + (Math.random() - 0.5) * 150, targetY + 100, branchDepth - 1);
                }

                currentX = targetX;
                currentY = targetY;
            }

            ctx.strokeStyle = 'rgba(240, 246, 255, 0.95)';
            ctx.lineWidth = branchDepth * 1.8;
            ctx.shadowBlur = 20;
            ctx.shadowColor = '#38bdf8';
            ctx.stroke();
        }

        function triggerLightningStrike() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            const startX = Math.random() * canvas.width;
            const endX = startX + (Math.random() - 0.5) * 300;
            const endY = canvas.height * (0.6 + Math.random() * 0.3);

            // Draw bolt
            drawLightningBolt(startX, 0, endX, endY, 3);

            // Trigger Atmospheric Sky Flash
            atmosphere.classList.add('lightning-flash');
            
            // Sync Audio Thunder (with realistic sound delay)
            setTimeout(() => {
                playThunderSFX();
            }, 120 + Math.random() * 200);

            // Clear Bolt and Flash quickly
            setTimeout(() => {
                atmosphere.classList.remove('lightning-flash');
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            }, 90);

            // Occasional Double Strike Effect
            if (Math.random() < 0.35) {
                setTimeout(() => {
                    drawLightningBolt(startX + 20, 0, endX + 30, endY, 2);
                    atmosphere.classList.add('lightning-flash');
                    setTimeout(() => {
                        atmosphere.classList.remove('lightning-flash');
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                    }, 60);
                }, 140);
            }

            // Loop Next Random Strike
            scheduleNextLightning();
        }

        function scheduleNextLightning() {
            const randomDelay = Math.random() * 7000 + 4000; // 4 to 11 seconds interval
            setTimeout(triggerLightningStrike, randomDelay);
        }

        // Start Lightning Loop after small initial delay
        setTimeout(scheduleNextLightning, 3000);


        /* -------------------------------------------------------------
         * 3. REALISTIC RAIN PARTICLES CANVAS SYSTEM
         * ------------------------------------------------------------- */
        const rainCanvas = document.getElementById('rainCanvas');
        const rainCtx = rainCanvas.getContext('2d');
        let raindrops = [];

        function resizeRainCanvas() {
            rainCanvas.width = window.innerWidth;
            rainCanvas.height = window.innerHeight;
            initRain();
        }

        function initRain() {
            raindrops = [];
            const dropCount = Math.floor((rainCanvas.width * rainCanvas.height) / 7000);
            for (let i = 0; i < dropCount; i++) {
                raindrops.push({
                    x: Math.random() * rainCanvas.width,
                    y: Math.random() * rainCanvas.height,
                    length: Math.random() * 20 + 10,
                    speed: Math.random() * 12 + 18,
                    opacity: Math.random() * 0.3 + 0.15
                });
            }
        }

        function renderRain() {
            rainCtx.clearRect(0, 0, rainCanvas.width, rainCanvas.height);
            rainCtx.strokeStyle = 'rgba(186, 230, 253, 0.6)';
            rainCtx.lineWidth = 1.2;

            for (let i = 0; i < raindrops.length; i++) {
                const drop = raindrops[i];
                rainCtx.beginPath();
                rainCtx.moveTo(drop.x, drop.y);
                rainCtx.lineTo(drop.x - drop.length * 0.2, drop.y + drop.length);
                rainCtx.stroke();

                drop.y += drop.speed;
                drop.x -= drop.speed * 0.2;

                if (drop.y > rainCanvas.height) {
                    drop.y = -drop.length;
                    drop.x = Math.random() * rainCanvas.width;
                }
            }
            requestAnimationFrame(renderRain);
        }

        window.addEventListener('resize', resizeRainCanvas);
        resizeRainCanvas();
        renderRain();
    </script>
</body>
</html>