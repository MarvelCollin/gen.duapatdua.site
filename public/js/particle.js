const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

let particlesArray;
const mouse = {
    x: null,
    y: null,
    radius: 100,
    click: false
};

window.addEventListener('mousemove', function (event) {
    mouse.x = event.x;
    mouse.y = event.y;
});

window.addEventListener('mousedown', function (event) {
    if (event.button === 0) { // Check if the left mouse button is clicked
        mouse.click = true;
    }
});

window.addEventListener('mouseup', function () {
    mouse.click = false;
});

class Particle {
    constructor(x, y, directionX, directionY, size, color) {
        this.x = x;
        this.y = y;
        this.directionX = directionX;
        this.directionY = directionY;
        this.size = size;
        this.color = color;
        this.history = [{ x: this.x, y: this.y }];
        this.maxHistory = 30;
    }
    draw() {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2, false);
        ctx.fillStyle = this.color;
        ctx.fill();
        ctx.closePath();
    }

    drawTail() {
        for (let i = 0; i < this.history.length - 1; i++) {
            const opacity = (i + 1) / this.history.length;
            ctx.beginPath();
            ctx.moveTo(this.history[i].x, this.history[i].y);
            ctx.lineTo(this.history[i + 1].x, this.history[i + 1].y);
            ctx.strokeStyle = `rgba(255, 255, 255, ${opacity})`;
            ctx.stroke();
            ctx.closePath();
        }
    }

    update() {
        const dx = mouse.x - this.x;
        const dy = mouse.y - this.y;
        const distance = Math.sqrt(dx * dx + dy * dy);

        if (mouse.click && distance < mouse.radius) {
            const angle = Math.atan2(dy, dx);
            const speed = 1;
            this.directionX = -Math.cos(angle) * speed;
            this.directionY = -Math.sin(angle) * speed;
        } else if (distance < mouse.radius) {
            const angle = Math.atan2(dy, dx);
            const speed = 1; 
            this.directionX = Math.cos(angle) * speed;
            this.directionY = Math.sin(angle) * speed;
        } else {
            if (this.directionX === 0 && this.directionY === 0) {
                this.directionX = (Math.random() * 2) - 1;
                this.directionY = (Math.random() * 2) - 1;
            }
        }

        if (this.x > canvas.width || this.x < 0) {
            this.directionX = -this.directionX;
        }
        if (this.y > canvas.height || this.y < 0) {
            this.directionY = -this.directionY;
        }

        this.x += this.directionX;
        this.y += this.directionY;

        this.history.push({ x: this.x, y: this.y });
        if (this.history.length > this.maxHistory) {
            this.history.shift();
        }

        this.drawTail();
        this.draw();
    }
}

function init() {
    particlesArray = [];
    let numberOfParticles = (canvas.height * canvas.width) / 15000;
    for (let i = 0; i < numberOfParticles; i++) {
        let size = (Math.random() * 2) + 1;
        let x = (Math.random() * ((innerWidth - size * 2) - (size * 2)) + size * 2);
        let y = (Math.random() * ((innerHeight - size * 2) - (size * 2)) + size * 2);
        let directionX = (Math.random() * 2) - 1;
        let directionY = (Math.random() * 2) - 1;
        let color = '#fff';

        particlesArray.push(new Particle(x, y, directionX, directionY, size, color));
    }
}

function connect() {
    for (let a = 0; a < particlesArray.length; a++) {
        for (let b = a + 1; b < particlesArray.length; b++) {
            let distance = ((particlesArray[a].x - particlesArray[b].x) * (particlesArray[a].x - particlesArray[b].x))
                + ((particlesArray[a].y - particlesArray[b].y) * (particlesArray[a].y - particlesArray[b].y));
            if (distance < (canvas.width / 8) * (canvas.height / 8)) { // Reduced distance threshold
                let opacityValue = 1 - (distance / 10000); // Adjusted opacity calculation
                ctx.strokeStyle = 'rgba(255,255,255,' + opacityValue + ')';
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.moveTo(particlesArray[a].x, particlesArray[a].y);
                ctx.lineTo(particlesArray[b].x, particlesArray[b].y);
                ctx.stroke();
            }
        }
    }
}

function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = '#334D4D';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    for (let i = 0; i < particlesArray.length; i++) {
        particlesArray[i].update();
    }
    connect();
    requestAnimationFrame(animate);
}

window.addEventListener('resize', function () {
    canvas.width = innerWidth;
    canvas.height = innerHeight;
    mouse.radius = (canvas.height / 80) * (canvas.width / 80);
    init();
});

window.addEventListener('mouseout', function () {
    mouse.x = undefined;
    mouse.y = undefined;
});

init();
animate();
