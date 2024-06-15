const canvas = document.getElementById("canvas"); // Ambil elemen canvas
const ctx = canvas.getContext("2d"); // Dapetin context 2D
canvas.width = window.innerWidth; // Set lebar canvas
canvas.height = window.innerHeight; // Set tinggi canvas

let particlesArray; // Array buat partikel
const mouse = {
    x: null,
    y: null,
    radius: 70,
    click: false,
}; // Objek buat posisi dan klik mouse

window.addEventListener("mousemove", (event) => {
    mouse.x = event.x; 
    mouse.y = event.y; 
});

window.addEventListener("mousedown", (event) => {
    if (event.button === 0) {
        // Kalo tombol kiri diklik
        mouse.click = true; // Set klik jadi true
    }
});

// Dengerin lepas klik mouse
window.addEventListener("mouseup", () => {
    mouse.click = false; // Set klik jadi false
});

// Kelas buat partikel
class Particle {
    constructor(x, y, directionX, directionY, size, color) {
        this.x = x;
        this.y = y;
        this.directionX = directionX;
        this.directionY = directionY;
        this.size = size;
        this.color = color;
        this.history = [{ x: this.x, y: this.y }];
        this.maxHistory = 30; // Maks panjang jejak
    }

    // Gambar partikel
    draw() {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2, false);
        ctx.fillStyle = this.color;
        ctx.fill();
        ctx.closePath();
    }

    // Gambar jejak partikel
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

    // Update posisi partikel
    update() {
        const dx = mouse.x - this.x;
        const dy = mouse.y - this.y;
        const distance = Math.sqrt(dx * dx + dy * dy);

        // Kalo mouse diklik dan deket, gerakin partikel
        // Kasi efek mantulin 
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
                this.directionX = Math.random() * 2 - 1;
                this.directionY = Math.random() * 2 - 1;
            }
        }

        // Pantulin partikel kalo keluar batas
        if (this.x > canvas.width || this.x < 0) {
            this.directionX = -this.directionX;
        }
        if (this.y > canvas.height || this.y < 0) {
            this.directionY = -this.directionY;
        }

        this.x += this.directionX; // Update posisi x
        this.y += this.directionY; // Update posisi y

        this.history.push({ x: this.x, y: this.y }); // Tambahin posisi ke jejak
        if (this.history.length > this.maxHistory) {
            this.history.shift(); // Buang jejak pertama kalo udah kepanjangan
        }

        this.drawTail(); // Gambar efek roket
        this.draw(); // Gambar partikel
    }
}

// Inisialisasi partikel
function init() {
    particlesArray = []; // Kosongin array partikel
    let numberOfParticles = (canvas.height * canvas.width) / 15000; // Hitung jumlah partikel
    for (let i = 0; i < numberOfParticles; i++) {
        let size = Math.random() * 2 + 1; // Ukuran random
        let x = Math.random() * (innerWidth - size * 2 - size * 2) + size * 2; // Posisi x random
        let y = Math.random() * (innerHeight - size * 2 - size * 2) + size * 2; // Posisi y random
        let directionX = Math.random() * 2 - 1; // Arah x random
        let directionY = Math.random() * 2 - 1; // Arah y random
        let color = "#fff"; // Warna putih

        particlesArray.push(
            new Particle(x, y, directionX, directionY, size, color)
        ); // Tambahin partikel ke array
    }
}

// Fungsi buat hubungin partikel dengan garis
function connect() {
    for (let a = 0; a < particlesArray.length; a++) {
        for (let b = a + 1; b < particlesArray.length; b++) {
            // holy shit aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa bruteforce
            let distance =
                (particlesArray[a].x - particlesArray[b].x) *
                    (particlesArray[a].x - particlesArray[b].x) +
                (particlesArray[a].y - particlesArray[b].y) *
                    (particlesArray[a].y - particlesArray[b].y);
            if (distance < (canvas.width / 8) * (canvas.height / 8)) {
                // Cek jarak partikel
                let opacityValue = 1 - distance / 10000; // Hitung opacity
                ctx.strokeStyle = "rgba(255,255,255," + opacityValue + ")"; // Set warna garis
                ctx.lineWidth = 1; // Set lebar garis
                ctx.beginPath();
                ctx.moveTo(particlesArray[a].x, particlesArray[a].y);
                ctx.lineTo(particlesArray[b].x, particlesArray[b].y);
                ctx.stroke();
            }
        }
    }
}

// Fungsi buat animasi
function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height); // Bersihin canvas
    ctx.fillStyle = "#334D4D"; // Set warna background
    ctx.fillRect(0, 0, canvas.width, canvas.height); // Gambar background
    for (let i = 0; i < particlesArray.length; i++) {
        particlesArray[i].update(); // Update posisi partikel
    }
    connect(); // Hubungin partikel
    requestAnimationFrame(animate); // Loop animasi
}

// Dengerin perubahan ukuran jendela
window.addEventListener("resize", () => {
    canvas.width = innerWidth;
    canvas.height = innerHeight;
    mouse.radius = (canvas.height / 80) * (canvas.width / 80);
    init(); // Inisialisasi ulang partikel
});

// Dengerin mouse keluar dari jendela
window.addEventListener("mouseout", () => {
    mouse.x = undefined;
    mouse.y = undefined;
});

init(); 
animate(); 
