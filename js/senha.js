//mostrar e esconder senha
document.getElementById("toggle-senha").addEventListener("click", function () {
  var senhaInput = document.getElementById("senha-input");
  if (senhaInput.type === "password") {
    senhaInput.type = "text";
    this.textContent = "visibility_off";
  } else {
    senhaInput.type = "password";
    this.textContent = "visibility";
  }
});

//mostrar matricula
function mostrarMatricula(select) {
  const matriculaContainer = document.getElementById("matriculaContainer");
  matriculaContainer.style.display =
    select.value === "aluno" ? "block" : "none";
}
