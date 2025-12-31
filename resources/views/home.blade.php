<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ahora IC3 | Personal Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Major+Mono+Display&family=JetBrains+Mono:wght@400&family=Vazirmatn:wght@300;700&display=swap');

        :root {
            --ice-blue: #f0fdff;
            --cyber-cyan: #00f3ff;
            --deep-space: #010409;
        }

        body {
            background-color: var(--deep-space);
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
            font-family: 'Vazirmatn', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .ambient-env {
            position: fixed;
            inset: 0;
            background: radial-gradient(circle at 50% 50%, #0a223a 0%, #010409 100%);
            z-index: 1;
        }

        .code-rain {
            position: absolute;
            color: rgba(0, 243, 255, 0.08);
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
            margin-bottom: -20px;
        }

        #particle-canvas {
            cursor: crosshair;
            max-width: 100%;
        }

        .social-grid {
            position: relative;
            z-index: 20;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            width: 90%;
            max-width: 800px;
            margin-top: 2rem;
        }

        .social-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 243, 255, 0.1);
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }

        .social-card:hover {
            background: rgba(0, 243, 255, 0.05);
            border-color: var(--cyber-cyan);
            transform: translateY(-10px) scale(1.05);
            box-shadow: 0 10px 30px rgba(0, 243, 255, 0.1);
        }

        .social-card svg {
            width: 32px;
            height: 32px;
            fill: var(--ice-blue);
            transition: fill 0.3s;
        }

        .social-card:hover svg {
            fill: var(--cyber-cyan);
        }

        .social-card span {
            font-family: 'JetBrains Mono', monospace;
            color: var(--ice-blue);
            font-size: 0.9rem;
            letter-spacing: 1px;
        }

        /* استایل جدید و بهبود یافته برای بخش امضا */
        .branding {
            position: relative;
            margin-top: 5rem;
            z-index: 20;
            display: flex;
            flex-direction: column;
            align-items: center;
            pointer-events: none;
        }

        .branding .line {
            width: 40px;
            height: 1px;
            background: var(--cyber-cyan);
            margin-bottom: 1rem;
            box-shadow: 0 0 10px var(--cyber-cyan);
            animation: line-pulse 2s infinite ease-in-out;
        }

        .branding .name {
            font-family: 'Major Mono Display', monospace;
            font-size: 1.2rem;
            letter-spacing: 0.8rem;
            color: var(--ice-blue);
            text-transform: lowercase;
            text-shadow: 0 0 10px rgba(0, 243, 255, 0.3);
            margin-bottom: 0.5rem;
        }

        .branding .tagline {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.5rem;
            letter-spacing: 0.3rem;
            color: rgba(0, 243, 255, 0.4);
            text-transform: uppercase;
        }

        @keyframes line-pulse {
            0%, 100% { width: 40px; opacity: 0.5; }
            50% { width: 80px; opacity: 1; }
        }

        body::after {
            content: "";
            position: fixed;
            inset: 0;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.02) 50%);
            z-index: 100;
            background-size: 100% 4px;
            pointer-events: none;
            opacity: 0.2;
        }

        @media (max-width: 640px) {
            .social-grid {
                grid-template-columns: 1fr;
            }
            .canvas-wrapper {
                transform: scale(0.6);
            }
            .branding .name {
                font-size: 1rem;
                letter-spacing: 0.5rem;
            }
        }
    </style>
</head>
<body>

<div class="ambient-env"></div>
<div id="rain-zone" class="fixed inset-0 pointer-events-none"></div>

<div class="canvas-wrapper">
    <canvas id="particle-canvas"></canvas>
</div>

<div class="social-grid">
    <a href="https://t.me/iamahora" target="_blank" class="social-card">
        <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/></svg>
        <span>TELEGRAM</span>
    </a>
    <a href="https://instagram.com/ahoraic3" target="_blank" class="social-card">
        <svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
        <span>INSTAGRAM</span>
    </a>
    <a href="https://discord.com/users/778678325253898280" target="_blank" class="social-card">
        <svg viewBox="0 0 24 24"><path d="M20.317 4.37a19.791 19.791 0 00-4.885-1.515.074.074 0 00-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 00-5.487 0 12.64 12.64 0 00-.617-1.25.077.077 0 00-.079-.037A19.736 19.736 0 003.677 4.37a.07.07 0 00-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 00.031.057 19.9 19.9 0 005.993 3.03.078.078 0 00.084-.028c.462-.63.874-1.295 1.226-1.994.021-.041.001-.09-.041-.106a13.107 13.107 0 01-1.872-.892.077.077 0 01-.008-.128 10.2 10.2 0 00.372-.292.074.074 0 01.077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 01.078.01c.12.098.246.198.373.292a.077.077 0 01-.006.127 12.299 12.299 0 01-1.873.892.077.077 0 01-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 00.084.028 19.839 19.839 0 006.002-3.03.077.077 0 00.032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 00-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.419 0 1.334-.956 2.419-2.157 2.419zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.419 0 1.334-.946 2.419-2.157 2.419z"/></svg>
        <span>DISCORD</span>
    </a>
</div>

<!-- بخش امضای بازطراحی شده -->
<div class="branding">
    <div class="line"></div>
    <span class="name">A h o r a</span>
    <span class="tagline">System Architect</span>
</div>

<script>
    const canvas = document.getElementById('particle-canvas');
    const ctx = canvas.getContext('2d', { alpha: true });
    const rainZone = document.getElementById('rain-zone');

    let width, height;
    let particles = [];
    let isHovered = false;
    let hitMask = null;
    let morphFactor = 0;

    const symbols = [
        '0xAF', 'NULL', '&&', '||', '=>', 'func', 'init', 'true', 'false', '01', 'root', 'sync',
        'Δ', 'Ω', 'Ξ', 'Ψ', 'Σ', 'λ', 'θ', 'π', '¿', '⚡', '▒', '█',
        'ice', 'core', 'head', 'frost', 'cold', 'zero', 'sub', 'ic3', 'glitch', 'error',
        'x', '!', '?', '#', '*', '+', '-', '[ ]', '{ }', 'hash'
    ];

    const particleCount = 800;

    function resize() {
        width = canvas.width = 1000;
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

        for (let y = 0; y < height; y += 4) {
            for (let x = 0; x < width; x += 4) {
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

            this.x = this.ic3X;
            this.y = this.ic3Y;

            this.vx = (Math.random() - 0.5) * 10;
            this.vy = (Math.random() - 0.5) * 10;

            this.size = Math.random() * 5 + 2;
            this.char = symbols[Math.floor(Math.random() * symbols.length)];

            this.friction = 0.94;
            this.ease = 0.05;

            this.offset = Math.random() * 1000;
            this.floatSpeed = 0.002;
            this.floatRange = 1.5;

            this.color = Math.random() > 0.7 ? '#00f3ff' : '#f0fdff';
        }

        update(time, morph) {
            const targetX = this.ic3X + (this.ahoraX - this.ic3X) * morph;
            const targetY = this.ic3Y + (this.ahoraY - this.ic3Y) * morph;

            if (isHovered) {
                this.x += this.vx;
                this.y += this.vy;
                this.vx *= this.friction;
                this.vy *= this.friction;

                this.x += (targetX - this.x) * (this.ease * 0.2);
                this.y += (targetY - this.y) * (this.ease * 0.2);
            } else {
                const driftX = Math.sin(time * this.floatSpeed + this.offset) * this.floatRange;
                const driftY = Math.cos(time * this.floatSpeed + this.offset) * this.floatRange;

                this.x += (targetX + driftX - this.x) * this.ease;
                this.y += (targetY + driftY - this.y) * this.ease;

                if(Math.abs(this.x - targetX) < 2) {
                    this.vx = (Math.random() - 0.5) * 12;
                    this.vy = (Math.random() - 0.5) * 12;
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
            if (p1 && p2) particles.push(new Particle(p1, p2));
        }
        ctx.font = "12px 'JetBrains Mono'";
    }

    function animate(time) {
        ctx.clearRect(0, 0, width, height);
        morphFactor = (Math.sin(time * 0.0006) + 1) / 2;

        for (let i = 0; i < particles.length; i++) {
            particles[i].update(time, morphFactor);
            particles[i].draw();
        }
        requestAnimationFrame(animate);
    }

    canvas.addEventListener('mousemove', (e) => {
        const rect = canvas.getBoundingClientRect();
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
        for (let i = 0; i < 20; i++) {
            const drop = document.createElement('div');
            drop.className = 'code-rain';
            drop.innerText = symbols[Math.floor(Math.random() * symbols.length)];
            drop.style.left = Math.random() * 100 + 'vw';
            drop.style.animation = `rain-fall ${Math.random() * 4 + 4}s linear infinite`;
            rainZone.appendChild(drop);
        }
    }

    window.addEventListener('resize', resize);
    resize();
    requestAnimationFrame(animate);
    initRain();

    document.addEventListener('mousemove', (e) => {
        const x = (window.innerWidth / 2 - e.pageX) / 120;
        const y = (window.innerHeight / 2 - e.pageY) / 120;
        canvas.style.transform = `rotateY(${x}deg) rotateX(${-y}deg)`;
    });
</script>
</body>
</html>
