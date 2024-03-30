document.addEventListener("DOMContentLoaded", function () {
  // Obtém todos os elementos de link e seções
  var configLinks = document.querySelectorAll(".section-link");
  var configSections = document.querySelectorAll(".content-sections");

  // Adiciona eventos de clique a todos os links
  configLinks.forEach(function (link) {
    link.addEventListener("click", function (event) {
      event.preventDefault();
      var targetId = link.getAttribute("data-target");

      // Oculta todas as seções
      configSections.forEach(function (section) {
        section.style.display = "none";
      });

      // Mostra apenas a seção correspondente ao link clicado
      document.getElementById(targetId).style.display = "flex";

      // Remove a classe 'active' de todos os links
      configLinks.forEach(function (link) {
        link.classList.remove("active");
      });

      // Adiciona a classe 'active' ao link clicado
      link.classList.add("active");
    });
  });
});
