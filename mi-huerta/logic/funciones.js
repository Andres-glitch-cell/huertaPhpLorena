function procesar() {
  window.location.href = "procesar.php";
}
function llevarAlSoporte() {
  window.location.href = "soporte.php";
}
// ! Reloj en formato 24h
function updateClock() {
  const now = new Date();
  // ? Formateo con ceros a la izquierda, usando padStart
  let hours = now.getHours().toString().padStart(2, "0");
  let minutes = now.getMinutes().toString().padStart(2, "0");
  // ! Actualización del DOM, evitando manipulación directa del innerHTML
  document.getElementById("hours").textContent = hours;
  document.getElementById("minutes").textContent = minutes;

  const options = {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  };
  // ? Formateo de la fecha en español, con la primera letra en mayúscula
  const fecha = now.toLocaleDateString("es-ES", options);
  document.getElementById("date").textContent =
    // # Capitalizar la primera letra de la fecha
    fecha.charAt(0).toUpperCase() + fecha.slice(1);
}
updateClock();
setInterval(updateClock, 1000);

// ! Mensaje de éxito, que desaparece tras 2 segundos
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
