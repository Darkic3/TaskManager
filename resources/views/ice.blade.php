<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IC3 to Ahora | Morphing Core</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Major+Mono+Display&family=JetBrains+Mono:wght@400&display=swap');

        :root {
            --ice-blue: #f0fdff;
            --cyber-cyan: #00f3ff;
            --deep-space: #010409;
        }

        body {
            background-color: var(--deep-space);
            margin: 0;
            height: 100vh;
            overflow: hidden;
            font-family: 'Major Mono Display', monospace;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ambient-env {
            position: fixed;
            inset: 0;
            background: radial-gradient(circle at 50% 50%, #0a223a 0%, #010409 100%);
            z-index: 1;
        }

        .code-rain {
            position: absolute;
            color: rgba(0, 243, 255, 0.1);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.6rem;
            pointer-events: none;
            z-index: 2;
        }

        @keyframes rain-fall {
            to { transform: translateY(110vh); opacity: 0; }
        }

        .canvas-wrapper {
            position: relative;
            z-index: 10;
        }

        #particle-canvas {
            /* حذف فیلتر سنگین برای افزایش سرعت */
            cursor: crosshair;
        }

        .branding {
            position: fixed;
            bottom: 30px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.65rem;
            letter-spacing: 0.5rem;
            color: rgba(0, 243, 255, 0.15);
            text-transform: uppercase;
            z-index: 5;
            pointer-events: none;
        }

        body::after {
            content: "";
            position: fixed;
            inset: 0;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.03) 50%);
            z-index: 100;
            background-size: 100% 4px;
            pointer-events: none;
            opacity: 0.3;
        }
    </style>
</head>
<body>

<div class="ambient-env"></div>
<div id="rain-zone" class="fixed inset-0 pointer-events-none"></div>

<div class="canvas-wrapper">
    <canvas id="particle-canvas"></canvas>
</div>

<div class="branding">
    Developing by Ahora-Ic3
</div>

<script>
    const canvas = document.getElementById('particle-canvas');
    const ctx = canvas.getContext('2d', { alpha: true });
    const rainZone = document.getElementById('rain-zone');

    let width, height;
    let particles = [];
    let isHovered = false;
    let hitMask = null;

    const symbols = [
        '0xAF', 'NULL', '&&', '||', '=>', 'func', 'init', 'true', 'false', '01', 'root', 'sync',
        'Δ', 'Ω', 'Ξ', 'Ψ', 'Σ', 'λ', 'θ', 'π', '¿', '⚡', '▒', '█',
        'ice', 'core', 'head', 'frost', 'cold', 'zero', 'sub', 'ic3', 'glitch', 'error',
        'x', '!', '?', '#', '*', '+', '-', '[ ]', '{ }', 'hash'
    ];

    // تعداد ذرات بهینه شده برای روانی کامل انیمیشن
    const particleCount = 1000;

    function resize() {
        width = canvas.width = 1000; // ابعاد ثابت برای پایداری پرفورمنس
        height = canvas.height = 400;
        initParticles();
    }

    function getPositionsFromText(text, fontSize) {
        const tempCanvas = document.createElement('canvas');
        const tempCtx = tempCanvas.getContext('2d');
        tempCanvas.width = width;
        tempCanvas.height = height;

        tempCtx.fillStyle = "white";
        tempCtx.font = `bold ${fontSize}px 'Major Mono Display'`;
        tempCtx.textAlign = "center";
        tempCtx.textBaseline = "middle";
        tempCtx.fillText(text, width / 2, height / 2);

        const imageData = tempCtx.getImageData(0, 0, width, height);
        const data = imageData.data;
        const positions = [];

        // گام ۳ پیکسل برای سرعت بالاتر در لود اولیه
        for (let y = 0; y < height; y += 3) {
            for (let x = 0; x < width; x += 3) {
                const index = (y * width + x) * 4;
                if (data[index + 3] > 128) {
                    positions.push({ x, y });
                }
            }
        }
        return { positions, rawData: data };
    }

    class Particle {
        constructor(pos1, pos2) {
            this.ic3X = pos1.x;
            this.ic3Y = pos1.y;
            this.ahoraX = pos2.x;
            this.ahoraY = pos2.y;

            this.x = this.ic3X + (Math.random() - 0.5) * 40;
            this.y = this.ic3Y + (Math.random() - 0.5) * 40;

            this.vx = (Math.random() - 0.5) * 12;
            this.vy = (Math.random() - 0.5) * 12;

            this.size = Math.random() * 6 + 3;
            this.char = symbols[Math.floor(Math.random() * symbols.length)];
            this.angle = 0;
            this.spin = (Math.random() - 0.5) * 0.1;

            this.friction = 0.95;
            this.ease = 0.06;

            this.offset = Math.random() * 1000;
            this.floatSpeed = 0.0015;
            this.floatRange = 2;

            this.color = Math.random() > 0.8 ? '#00f3ff' : (Math.random() > 0.5 ? '#f0fdff' : '#a5f3fc');
        }

        update(time) {
            const targetX = isHovered ? this.ahoraX : this.ic3X;
            const targetY = isHovered ? this.ahoraY : this.ic3Y;

            if (isHovered) {
                this.x += this.vx;
                this.y += this.vy;
                this.vx *= this.friction;
                this.vy *= this.friction;

                this.x += (targetX - this.x) * (this.ease * 0.4);
                this.y += (targetY - this.y) * (this.ease * 0.4);
            } else {
                const driftX = Math.sin(time * this.floatSpeed + this.offset) * this.floatRange;
                const driftY = Math.cos(time * this.floatSpeed + this.offset) * this.floatRange;

                this.x += (targetX + driftX - this.x) * this.ease;
                this.y += (targetY + driftY - this.y) * this.ease;

                if(Math.abs(this.x - targetX) < 1) {
                    this.vx = (Math.random() - 0.5) * 15;
                    this.vy = (Math.random() - 0.5) * 15;
                }
            }
        }

        draw() {
            ctx.fillStyle = isHovered ? '#00f3ff' : this.color;
            ctx.fillText(this.char, this.x, this.y);
        }
    }

    function initParticles() {
        const ic3Data = getPositionsFromText("ic3", 300);
        const ahoraData = getPositionsFromText("Ahora", 260);

        hitMask = ic3Data.rawData;

        particles = [];
        for (let i = 0; i < particleCount; i++) {
            const p1 = ic3Data.positions[Math.floor(Math.random() * ic3Data.positions.length)];
            const p2 = ahoraData.positions[Math.floor(Math.random() * ahoraData.positions.length)];

            if (p1 && p2) {
                particles.push(new Particle(p1, p2));
            }
        }
        ctx.font = "400 14px 'JetBrains Mono'"; // تنظیم فونت ثابت برای کل ذرات برای سرعت بیشتر
    }

    function animate(time) {
        ctx.clearRect(0, 0, width, height);
        for (let i = 0; i < particles.length; i++) {
            particles[i].update(time);
            particles[i].draw();
        }
        requestAnimationFrame(animate);
    }

    canvas.addEventListener('mousemove', (e) => {
        const rect = canvas.getBoundingClientRect();
        // محاسبه مقیاس در صورتی که کانواس ریسپانسیو باشد
        const scaleX = width / rect.width;
        const scaleY = height / rect.height;
        const mouseX = Math.floor((e.clientX - rect.left) * scaleX);
        const mouseY = Math.floor((e.clientY - rect.top) * scaleY);

        if (mouseX >= 0 && mouseX < width && mouseY >= 0 && mouseY < height) {
            const index = (mouseY * width + mouseX) * 4;
            isHovered = hitMask && hitMask[index + 3] > 10;
        } else {
            isHovered = false;
        }
    });

    canvas.addEventListener('mouseleave', () => isHovered = false);

    function initRain() {
        rainZone.innerHTML = '';
        for (let i = 0; i < 30; i++) {
            const drop = document.createElement('div');
            drop.className = 'code-rain';
            drop.innerText = symbols[Math.floor(Math.random() * symbols.length)];
            drop.style.left = Math.random() * 100 + 'vw';
            drop.style.animation = `rain-fall ${Math.random() * 3 + 3}s linear infinite`;
            drop.style.animationDelay = Math.random() * 5 + 's';
            rainZone.appendChild(drop);
        }
    }

    resize();
    requestAnimationFrame(animate);
    initRain();

    document.addEventListener('mousemove', (e) => {
        const x = (window.innerWidth / 2 - e.pageX) / 100;
        const y = (window.innerHeight / 2 - e.pageY) / 100;
        canvas.style.transform = `rotateY(${x}deg) rotateX(${-y}deg)`;
    });
</script>
</body>
</html>
