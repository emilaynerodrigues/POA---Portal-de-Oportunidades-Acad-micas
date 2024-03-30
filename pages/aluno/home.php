<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>

    <!-- links css -->
    <link rel="stylesheet" href="../styles/main.css" />
    <link rel="stylesheet" href="../styles/home.css" />

    <!-- link favicon -->
    <link
      rel="shortcut icon"
      href="../img/favicon.png"
      type="image/x-icon"
    />

    <!-- link font symbols -->
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
    />
  </head>
  <body>
    <!-- menu lateral -->
    <aside class="sidebar">
      <!-- Ícone de hambúrguer -->
      <div class="toggle-container">
        <label for="menuToggle" id="menuIcon">&#9776;</label>
        <input type="checkbox" id="menuToggle" />
      </div>

      <!-- Opções do menu -->
      <ul id="menuOptions">
        <!-- menu inicial -->
        <li>
          <a href="#menu-inicial" id="menu-inicial-link"
            ><span class="tooltip">Menu Inicial</span>
            <span class="material-symbols-outlined"> home </span>
            <span class="menu-item-label">Menu Inicial</span>
          </a>
        </li>

        <!-- minhas contratações -->
        <li>
          <a href="#contratacoes" id="contratacoes-link">
            <span class="tooltip">Minhas Contratações</span>
            <span class="material-symbols-outlined"> work </span>
            <span class="menu-item-label">Minhas Contratações</span>
          </a>
        </li>

        <!-- dados pessoais -->
        <li>
          <a href="#dados-pessoais" id="dados-pessoais-link">
            <span class="tooltip">Dados Pessoais</span>
            <span class="material-symbols-outlined"> frame_person </span>
            <span class="menu-item-label">Dados Pessoais</span>
          </a>
        </li>

        <!-- portfolio -->
        <li>
          <a href="#portfolio" id="portfolio-link">
            <span class="tooltip">Portfólio</span>

            <span class="material-symbols-outlined"> badge </span>
            <span class="menu-item-label">Portfólio</span>
          </a>
        </li>

        <!-- configurações -->
        <li>
          <a href="#configuracoes" id="configuracoes-link">
            <span class="tooltip">Configurações</span>
            <span class="material-symbols-outlined"> settings </span>
            <span class="menu-item-label">Configurações</span>
          </a>
        </li>
      </ul>
    </aside>

    <div class="container">
      <header class="header">
        <div class="logo">
          <img
            src="../img/logo-escura.png"
            alt=""
            srcset=""
            style="height: 50px"
          />
        </div>
      </header>

      <main class="content-wrapper">
        <!-- seção do menu inicial -->
        <section id="menu-inicial">menu inicial</section>

        <!-- seção contratacoes -->
        <section id="contratacoes">contratações</section>

        <!-- seção dados-pessoais -->
        <section id="dados-pessoais">dados pessoais</section>

        <!-- seção portfolio -->
        <section id="portfolio">portfolio</section>

        <!-- seção configuracoes -->
        <section id="configuracoes">configurações</section>
      </main>
    </div>

    <script src="../js/main.js"></script>
  </body>
</html>
