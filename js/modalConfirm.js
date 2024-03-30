// Abre o modal de confirmação quando o usuário clica na logo ou no botão de cancelar
const logo = document.querySelector(".logo img");
const cancelBtn = document.getElementById("cancelBtn");

logo.addEventListener("click", function () {
  openConfirmModal();
});

cancelBtn.addEventListener("click", function () {
  openConfirmModal();
});

// Exibe o modal de confirmação
function openConfirmModal() {
  const confirmModal = document.getElementById("confirmModal");
  confirmModal.style.display = "flex";

  // Adiciona um evento de clique aos botões de cancelamento (por classe)
  const cancelBtns = document.querySelectorAll(".modal-close");
  cancelBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      // Oculta o modal de confirmação após o usuário cancelar
      confirmModal.style.display = "none";
    });
  });

  // Adiciona um evento de clique ao botão de confirmação
  const confirmBtn = document.getElementById("confirmBtn");
  confirmBtn.addEventListener("click", function () {
    // Redireciona para home.php após a confirmação
    window.location.href = "home.php";
  });
}

// Adiciona um evento de clique ao botão de cancelar (cancelBtn)
cancelBtn.addEventListener("click", function () {
  openConfirmModal();
});

// ------------------------------------------------------------------------------------------------------------
// fazendo a contagem de caracteres do textarea das paginas de crud de projeto
const textarea = document.getElementById("descricao-input");
const contador = document.getElementById("contador");

textarea.addEventListener("input", function () {
  const caracteresRestantes = 1000 - textarea.value.length;
  contador.textContent = "* " + caracteresRestantes + " caracteres restantes";
});

