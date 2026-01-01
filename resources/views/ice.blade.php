@extends('layouts.site.main')

@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@900&family=JetBrains+Mono:wght@300;400&display=swap');

        body {
            margin: 0;
            padding: 0;
            background-color: #010409;
            overflow: hidden;
            width: 100vw;
            height: 100vh;
        }

        .canvas-container {
            position: relative;
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #010409;
        }

        #particle-canvas {
            position: absolute;
            inset: 0;
            z-index: 10;
        }

        /* اصلاح پوزیشن Overlay برای نمایش بهتر در موبایل و دسکتاپ */
        .ui-overlay {
            position: absolute;
            bottom: 10%; /* تغییر از margin-top ثابت به فاصله از پایین */
            z-index: 40;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
            pointer-events: none; /* کلیک‌ها از لایه عبور کنند مگر روی دکمه‌ها */
        }

        .social-links {
            display: flex;
            gap: 1rem;
            pointer-events: auto; /* فعال کردن کلیک روی دکمه‌ها */
        }

        .social-btn {
            padding: 0.6rem 1.2rem;
            border: 1px solid rgba(0, 243, 255, 0.2);
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(8px);
            color: #00f3ff;
            text-decoration: none;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            letter-spacing: 0.2em;
            transition: all 0.3s ease;
        }

        .social-btn:hover {
            background: rgba(0, 243, 255, 0.15);
            color: #fff;
            border-color: #00f3ff;
            box-shadow: 0 0 15px rgba(0, 243, 255, 0.3);
        }

        .branding-footer {
            opacity: 0.4;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .brand-line {
            width: 30px;
            height: 1px;
            background: #00f3ff;
            margin-bottom: 0.5rem;
        }

        .brand-text {
            color: #fff;
            font-size: 9px;
            letter-spacing: 0.8em;
            text-transform: uppercase;
            font-family: 'JetBrains Mono', monospace;
        }

        .vfx-overlay {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 30;
            opacity: 0.05;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.4) 50%);
            background-size: 100% 4px;
        }

        .vignette {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 31;
            background: radial-gradient(circle, transparent 20%, rgba(0,0,0,0.8) 100%);
        }
    </style>
@endpush

@section('content')
    <div class="canvas-container">
        <canvas id="particle-canvas"></canvas>

        <div class="ui-overlay">
            <div class="social-links">
                <a href="#" class="social-btn">TELEGRAM</a>
                <a href="#" class="social-btn">INSTAGRAM</a>
                <a href="#" class="social-btn">DISCORD</a>
            </div>

            <div class="branding-footer">
                <div class="brand-line"></div>
                <span class="brand-text">Digital Architect</span>
            </div>
        </div>

        <div class="vfx-overlay"></div>
        <div class="vignette"></div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('particle-canvas');
            const ctx = canvas.getContext('2d');
            let animationFrameId;

            const tiltRotation = -0.45;
            const cosTilt = Math.cos(tiltRotation);
            const sinTilt = Math.sin(tiltRotation);

            // اصلاح شد: تبدیل کاراکترهای HTML به کاراکترهای واقعی جاوااسکریپت
            const codeSymbols = [
                '0xAF', 'NULL', '&&', '||', '=>', 'func', 'init', '01', 'sync', 'Δ', 'Ω', 'Ξ', 'Ψ', 'Σ', 'λ',
                '¿', '⚡', '▒', '█', 'ice', 'core', 'head', 'frost', 'cold', 'zero', 'ic3', 'glitch', 'error',
                'ptr', 'void', 'main', 'root', 'sudo', 'kernel', 'db', 'async', 'wait', '< >', '#', '{-}', 'static', 'const'
            ];

            const resize = () => {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            };

            window.addEventListener('resize', resize);
            resize();

            class Star {
                constructor() {
                    this.reset();
                }
                reset() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.size = Math.random() * 1.5;
                    this.blink = Math.random() * Math.PI;
                }
                draw(time) {
                    const opacity = 0.1 + Math.abs(Math.sin(time * 0.001 + this.blink)) * 0.6;
                    ctx.fillStyle = `rgba(255, 255, 255, ${opacity})`;
                    ctx.fillRect(this.x, this.y, this.size, this.size);
                }
            }

            class RingParticle {
                constructor() {
                    this.init();
                }
                init() {
                    const rand = Math.random();
                    let r;
                    if (rand < 0.3) r = 220 + Math.random() * 40;
                    else if (rand < 0.7) r = 280 + Math.random() * 60;
                    else r = 360 + Math.random() * 50;

                    this.radius = r;
                    this.angle = Math.random() * Math.PI * 2;
                    this.speed = (0.0015 + Math.random() * 0.0025) * (300 / this.radius);
                    this.char = codeSymbols[Math.floor(Math.random() * codeSymbols.length)];
                    this.opacity = 0.2 + Math.random() * 0.7;
                    this.fontSize = 8 + Math.random() * 8;
                    this.isSpecial = Math.random() > 0.92;
                }
                update() {
                    this.angle += this.speed;
                }
                isBehind() {
                    return Math.sin(this.angle) < 0;
                }
                draw(centerX, centerY) {
                    const rawX = Math.cos(this.angle) * this.radius;
                    const rawY = Math.sin(this.angle) * this.radius * 0.35;

                    const rotatedX = rawX * cosTilt - rawY * sinTilt;
                    const rotatedY = rawX * sinTilt + rawY * cosTilt;

                    const x = centerX + rotatedX;
                    const y = centerY + rotatedY;
                    const sinA = Math.sin(this.angle);
                    const zScale = 0.7 + (sinA + 1) * 0.3;

                    const behind = sinA < 0;
                    ctx.globalAlpha = behind ? this.opacity * 0.2 : this.opacity;
                    ctx.fillStyle = behind ? '#1a3a5a' : (this.isSpecial ? '#ffffff' : '#4fc3f7');
                    ctx.font = `${Math.floor(this.fontSize * zScale)}px 'JetBrains Mono', monospace`;

                    ctx.fillText(this.char, x, y);
                }
            }

            const stars = Array.from({ length: 150 }, () => new Star());
            const ringParticles = Array.from({ length: 600 }, () => new RingParticle());

            let lastLogoSize = 0;
            let ic3Grad, glowGrad;

            const drawLogo = (centerX, centerY) => {
                const ic3Size = window.innerWidth < 768 ? 90 : 150;

                if (lastLogoSize !== ic3Size) {
                    ic3Grad = ctx.createLinearGradient(centerX, centerY - 60, centerX, centerY + 20);
                    ic3Grad.addColorStop(0, '#ffffff');
                    ic3Grad.addColorStop(1, '#42a5f5');

                    glowGrad = ctx.createRadialGradient(centerX, centerY, 0, centerX, centerY, ic3Size * 1.5);
                    glowGrad.addColorStop(0, 'rgba(0, 243, 255, 0.1)');
                    glowGrad.addColorStop(1, 'transparent');
                    lastLogoSize = ic3Size;
                }

                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';

                // افکت هاله نور دور متن
                ctx.globalAlpha = 1;
                ctx.fillStyle = glowGrad;
                ctx.beginPath();
                ctx.arc(centerX, centerY, ic3Size * 1.5, 0, Math.PI * 2);
                ctx.fill();

                // متن اصلی Ic3
                ctx.font = `900 ${ic3Size}px 'Inter', sans-serif`;
                ctx.shadowBlur = 25;
                ctx.shadowColor = 'rgba(0, 243, 255, 0.4)';
                ctx.fillStyle = ic3Grad;
                ctx.fillText("Ic3", centerX, centerY - 10);

                // متن ثانویه HEAD
                ctx.shadowBlur = 0;
                const headSize = Math.floor(ic3Size * 0.22);
                ctx.font = `300 ${headSize}px 'JetBrains Mono', monospace`;
                ctx.fillStyle = 'rgba(0, 243, 255, 0.8)';
                ctx.fillText("HEAD", centerX, centerY + (ic3Size * 0.45));
            };

            const animate = (time) => {
                // پاک کردن صفحه
                ctx.globalAlpha = 1;
                ctx.fillStyle = '#010409';
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                const centerX = canvas.width / 2;
                const centerY = canvas.height / 2;

                // ۱. رسم ستاره‌های پس‌زمینه
                stars.forEach(s => s.draw(time));

                // ۲. رسم ذراتی که پشت لوگو هستند
                ringParticles.forEach(p => {
                    if (p.isBehind()) {
                        p.update();
                        p.draw(centerX, centerY);
                    }
                });

                // ۳. رسم لوگوی مرکزی
                drawLogo(centerX, centerY);

                // ۴. رسم ذراتی که جلوی لوگو هستند
                ringParticles.forEach(p => {
                    if (!p.isBehind()) {
                        p.update();
                        p.draw(centerX, centerY);
                    }
                });

                animationFrameId = requestAnimationFrame(animate);
            };

            animate(0);
        });
    </script>
@endpush
