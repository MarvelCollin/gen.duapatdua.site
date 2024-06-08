<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body, html {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            background-color: #334D4D;
        }

        .container {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        canvas {
            width: 100%;
            height: 100%;
            position: absolute;
            z-index: 1;
        }

        .login-container {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
        }

        .login-container input[type="password"] {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: rgba(0, 0, 0, 0.2);
            color: white;
        }

        .login-container input[type="password"]::placeholder {
            color: white;
        }

        .login-container input[type="password"]:focus {
            outline: none;
            border-color: #85AD99;
        }

        .login-container button {
            padding: 10px 20px;
            background-color: #85AD99;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .login-container button:hover {
            background-color: #6C957B;
        }

        .error-message {
            color: red;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <canvas id="canvas"></canvas>
        <div class="login-container">
            <h2>Enter the pass key</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password">
                </div>
                @if ($errors->has('password'))
                    <div class="error-message mb-4">{{ $errors->first('password') }}</div>
                @endif
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        class Particle {
            constructor(x, y, radius) {
                this.x = x;
                this.y = y;
                this.radius = radius;
                this.color = '#85AD99';
                this.velocity = {
                    x: (Math.random() - 0.5) * 2,
                    y: (Math.random() - 0.5) * 2
                };
            }

            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2, false);
                ctx.fillStyle = this.color;
                ctx.fill();
            }

            update(particles, mouseX, mouseY) {
                this.draw();

                for (let i = 0; i < particles.length; i++) {
                    if (this === particles[i]) continue;
                    let dx = this.x - particles[i].x;
                    let dy = this.y - particles[i].y;
                    let distance = Math.sqrt(dx * dx + dy * dy);

                    if (distance < 100) {
                        ctx.beginPath();
                        ctx.moveTo(this.x, this.y);
                        ctx.lineTo(particles[i].x, particles[i].y);
                        ctx.strokeStyle = '#85AD99';
                        ctx.stroke();
                        animateConnectingLine(this.x, this.y, particles[i].x, particles[i].y);
                    }
                }

                let dx = this.x - mouseX;
                let dy = this.y - mouseY;
                let distance = Math.sqrt(dx * dx + dy * dy);

                if (distance < 100) {
                    ctx.beginPath();
                    ctx.moveTo(this.x, this.y);
                    ctx.lineTo(mouseX, mouseY);
                    ctx.strokeStyle = '#85AD99';
                    ctx.stroke();
                    animateConnectingLine(this.x, this.y, mouseX, mouseY);
                }

                this.x += this.velocity.x;
                this.y += this.velocity.y;

                if (Math.random() < 0.01) {
                    this.velocity.x = (Math.random() - 0.5) * 2;
                    this.velocity.y = (Math.random() - 0.5) * 2;
                }
            }
        }

        function animateConnectingLine(startX, startY, endX, endY) {
            let alpha = 1;
            const animationSpeed = 0.05;

            function animate() {
                ctx.save();
                ctx.globalAlpha = alpha;
                ctx.beginPath();
                ctx.moveTo(startX, startY);
                ctx.lineTo(endX, endY);
                ctx.strokeStyle = '#85AD99';
                ctx.stroke();
                ctx.restore();

                alpha -= animationSpeed;
                if (alpha <= 0) return;

                requestAnimationFrame(animate);

            }

            animate();
        }

        const particles = [];

        function init() {
            for (let i = 0; i < 50; i++) {
                const radius = 2;
                let x = Math.random() * (canvas.width - 2 * radius) + radius;
                let y = Math.random() * (canvas.height - 2 * radius) + radius;
                particles.push(new Particle(x, y, radius));
            }
        }

        let mouseX = 0;
        let mouseY = 0;

        canvas.addEventListener('mousemove', (event) => {
            mouseX = event.clientX;
            mouseY = event.clientY;
        });

        function animate() {
            requestAnimationFrame(animate);
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            particles.forEach(particle => {
                particle.update(particles, mouseX, mouseY);
            });
        }

        init();
        animate();
    </script>
</body>
</html>
