// Adiciona evento de clique para a categoria de Desenvolvimento
document.getElementById("categoria1").addEventListener("click", function () {
  mostrarProjetosPorCategoria("Desenvolvimento");
});

// Adiciona evento de clique para a categoria de Design
document.getElementById("categoria2").addEventListener("click", function () {
  mostrarProjetosPorCategoria("Design");
});

// Adiciona evento de clique para a categoria de Suporte em TI
document.getElementById("categoria3").addEventListener("click", function () {
  mostrarProjetosPorCategoria("Suporte em TI");
});


// Função para mostrar os projetos da categoria selecionada
function mostrarProjetosPorCategoria(categoria) {
  // Oculta todos os projetos
  document.querySelectorAll(".projeto").forEach((projeto) => {
    projeto.style.display = "none";
  });

  // Mostra apenas os projetos da categoria selecionada
  document.querySelectorAll(".projeto").forEach((projeto) => {
    const categoriaProjeto = projeto.getAttribute("data-categoria");
    if (categoriaProjeto === categoria) {
      projeto.style.display = "flex";
    }
  });
}
