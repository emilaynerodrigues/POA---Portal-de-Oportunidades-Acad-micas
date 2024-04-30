// Obtém todos os links do menu
var menuLinks = document.querySelectorAll("aside a");

// Obtém todas as seções
var sections = document.querySelectorAll("section");

// Esconde todas as seções, exceto a primeira (Menu Inicial)
for (var i = 1; i < sections.length; i++) {
  sections[i].style.display = "none";
}

// Adiciona eventos de clique a cada link do menu
menuLinks.forEach(function (link) {
  link.addEventListener("click", function (event) {
    event.preventDefault(); // Impede o comportamento padrão do link (navegação)

    // Obtém o ID da seção correspondente ao link clicado
    var sectionId = link.getAttribute("href").slice(1);

    // Mostra a seção correspondente e oculta as demais
    showSection(sectionId);

    // Remove a classe 'active' de todos os links do menu
    menuLinks.forEach(function (menuLink) {
      menuLink.classList.remove("active");
    });

    // Adiciona a classe 'active' ao link do menu clicado
    link.classList.add("active");
  });
});

// Função para mostrar a seção especificada
function showSection(sectionId) {
  // Esconde todas as seções
  sections.forEach(function (section) {
    section.style.display = "none";
  });

  // Mostra a seção especificada
  document.getElementById(sectionId).style.display = "block";
}

// Elemento do menu toggle
var menuToggle = document.getElementById("menuToggle");

// Script para mostrar/ocultar o menu e alterar o ícone de hambúrguer para "X"
menuToggle.addEventListener("change", function () {
  // Altera o ícone de hambúrguer para "X" quando o menu é aberto, caso contrário, volta para o ícone padrão
  var menuIcon = document.getElementById("menuIcon");
  menuIcon.innerHTML = this.checked ? "&#10006;" : "&#9776;";

  // Adiciona ou remove a classe 'menu-open' no elemento aside para controlar a largura do menu lateral
  var aside = document.querySelector("aside");
  aside.style.width = this.checked ? "300px" : "74px"; // Ajusta a largura conforme o estado do menu
  aside.classList.toggle("menu-open", this.checked); // Adiciona a classe 'menu-open' se o menu estiver aberto
});

// ------------------------------------------------------
