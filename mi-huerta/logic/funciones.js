function procesar() {
  window.location.href = "procesar.php";
}
function llevarAlSoporte() {
  window.location.href = "soporte.php";
}
// Reloj en formato 24h
function updateClock() {
  const now = new Date();
  let hours = now.getHours().toString().padStart(2, "0");
  let minutes = now.getMinutes().toString().padStart(2, "0");
  document.getElementById("hours").textContent = hours;
  document.getElementById("minutes").textContent = minutes;

  const options = {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  };
  const fecha = now.toLocaleDateString("es-ES", options);
  document.getElementById("date").textContent =
    fecha.charAt(0).toUpperCase() + fecha.slice(1);
}
updateClock();
setInterval(updateClock, 1000);

// Mensaje de éxito
window.addEventListener("load", () => {
  const msg = document.getElementById("conexionMsg");
  if (msg) {
    msg.style.opacity = "1";
    setTimeout(() => (msg.style.opacity = "0"), 2000);
  }
});

function llevarAGitHub() {
  window.location.href =
    "https://github.com/Andres-glitch-cell/huertaPhpLorena";
}
function llamaFuncionCodigo() {
  window.location.href = "code.php";
}
