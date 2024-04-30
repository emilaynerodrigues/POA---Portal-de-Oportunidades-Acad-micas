// função para fechar modal de mensagem sem recarregar a pagina
function fecharModal() {
    var modal = document.getElementById("modalMensagem");
    modal.style.display = "none"; //fechando modal de candidatura
  }
  
  // atribuindo evento de clique ao ícone de fechamento
  var closeIcon = document.querySelectorAll(".closeIcon");
  closeIcon.forEach(function (closeIcon) {
    // Adicionando um event listener para o evento de clique em cada closeIcon
    closeIcon.addEventListener("click", fecharModal);
  });
  