const canvas = document.getElementById("fireworkCanvas");
const ctx = canvas.getContext("2d");

canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

const w = canvas.width;
const h = canvas.height;

const chars = "HAPPYNEWYEAR";
const duration = 3000;

function rocket(x, y, id, t) {
  ctx.fillStyle = `hsl(${Math.random() * 360}, 100%, 50%)`;
  ctx.beginPath();
  ctx.arc(x, y, 5, 0, 2 * Math.PI);
  ctx.fill();
}

function explosion(pts, x, y, id, t) {
  for (let i = 0; i < pts; i++) {
    const angle = (i / pts) * Math.PI * 2;
    const radius = t * 100;
    const px = x + Math.cos(angle) * radius;
    const py = y + Math.sin(angle) * radius;

    ctx.fillStyle = `hsl(${id * 30}, 100%, 50%)`;
    ctx.beginPath();
    ctx.arc(px, py, 3, 0, 2 * Math.PI);
    ctx.fill();
  }
}

function firework(t, i, pts) {
  t -= 1 * 200;

  const id = (chars.length * parseInt((t * t) % duration));
  const tt = (t % duration) / duration;

  let dx = (i + 1) * w / (1 + chars.length);
  let dy = h - Math.min(0.33, t) * 100 * Math.sin(id);

  dy += Math.sin(id * 4547.411) * h * 0.1;

  if (t < 0.33) {
    rocket(dx, dy, id, t * 3);
  } else {
    explosion(pts, dx, dy, id, Math.min(1, Math.max(0, t - 0.33) * 2));
  }
}

let startTime = null;

function animate(time) {
  if (!startTime) startTime = time;

  const elapsed = time - startTime;

  ctx.clearRect(0, 0, w, h);

  for (let i = 0; i < chars.length; i++) {
    firework(elapsed / 1000, i, 50);
  }

  requestAnimationFrame(animate);
}

animate();
