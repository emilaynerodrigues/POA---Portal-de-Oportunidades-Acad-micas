// Adiciona evento de clique para a categoria de Arte e Design
document.getElementById("categoria1").addEventListener("click", function () {
  mostrarProjetosPorCategoria("Arte & Design");
});

// Adiciona evento de clique para a categoria de Beleza e Estética
document.getElementById("categoria2").addEventListener("click", function () {
  mostrarProjetosPorCategoria("Beleza e Estética");
});

// Adiciona evento de clique para a categoria de Gestão e Finanças
document.getElementById("categoria3").addEventListener("click", function () {
  mostrarProjetosPorCategoria("Gestão e Finanças");
});

// Adiciona evento de clique para a categoria de Manutenção de Computadores
document.getElementById("categoria4").addEventListener("click", function () {
  mostrarProjetosPorCategoria("Manutenção de Computadores");
});

// Adiciona evento de clique para a categoria de Marketing e Vendas
document.getElementById("categoria5").addEventListener("click", function () {
  mostrarProjetosPorCategoria("Marketing e Vendas");
});

// Adiciona evento de clique para a categoria de Projetos sociais
document.getElementById("categoria6").addEventListener("click", function () {
  mostrarProjetosPorCategoria("Projetos Sociais");
});

// Adiciona evento de clique para a categoria de Suporte Administrativo
document.getElementById("categoria7").addEventListener("click", function () {
  mostrarProjetosPorCategoria("Suporte Administrativo");
});

// Adiciona evento de clique para a categoria de TI e Programação
document.getElementById("categoria8").addEventListener("click", function () {
  mostrarProjetosPorCategoria("TI e Programação");
});

// Adiciona evento de clique para a categoria de Tradução e Conteúdos
document.getElementById("categoria9").addEventListener("click", function () {
  mostrarProjetosPorCategoria("Tradução e Conteúdos");
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
