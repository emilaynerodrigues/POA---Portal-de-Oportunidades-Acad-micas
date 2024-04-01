<?php
session_start();

// Verificando se o usuário está logado como aluno
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'aluno') {
  // Se não estiver logado como aluno, redirecione para a página de login
  header('Location: ../../index.php');
  $_SESSION['mensagem'] =
    "<!-- Modal de confirmação - Acesso não autorizado! -->
    <div class='modal modal-session'>
      <div class='modal-content'>
          <span class='icon material-symbols-outlined'> cancel </span>
          <h3>Acesso não autorizado!</h3>
          <p>Você não possui autorização para acessar essa parte do sistema. Por favor, volte a página de login e entre com seus dados.</p>
          <div class='btn-wrapper'>
              <a href='../../pages/login.php' class='btn small-btn modal-close'>Entendi</a>
          </div>
      </div>
    </div>";
  exit();
}

// Obtendo o ID do aluno da sessão --> vindo da página de login
$user_id = $_SESSION['user_id'];
$tipoUsuario = $_SESSION['user_type'];

// Incluir o arquivo de conexão com o banco de dados
include("../../php/conexao.php");
$conn = conectar();

// Consulta ao banco de dados para obter os detalhes do aluno
$query = $conn->prepare("SELECT nome, email, senha FROM aluno WHERE id = :id");
$query->bindValue(":id", $user_id);
$query->execute();
$aluno = $query->fetch(PDO::FETCH_ASSOC);

// Acessando os detalhes do aluno
$nome_aluno = $aluno['nome'];
$email_aluno = $aluno['email'];
$senha = $aluno['senha'];

// Lógica de paginação
$limite_result = 6; // Definir a quantidade de projetos por página
$pagina_atual = isset($_GET['page']) ? $_GET['page'] : 1; // Obter a página atual da URL

$inicio = ($pagina_atual - 1) * $limite_result; // Calcular o início da seleção de registros

// Consulta para obter os projetos do anunciante atualmente logado, ordenados pela data de postagem em ordem decrescente (mais recentes primeiro)
$stmt = $conn->prepare("SELECT projeto.*, anunciante.nome AS nome_anunciante FROM projeto INNER JOIN anunciante ON projeto.anunciante_id = anunciante.id ORDER BY dataPostagem DESC LIMIT $inicio, $limite_result");
// $stmt->bindValue(":anunciante_id", $user_id);
$stmt->execute();
$projetos = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home</title>

  <!-- links css -->
  <link rel="stylesheet" href="../../styles/main.css" />
  <link rel="stylesheet" href="../../styles/home.css" />

  <!-- link favicon -->
  <link rel="shortcut icon" href="../../img/favicon.png" type="image/x-icon" />

  <!-- link font symbols -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

  <!-- link swiper js -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

</head>

<body>

  <!-- mostrando mensagem de projeto excluido-->
  <?php
  //verificando se existe a sessão
  if (isset($_SESSION['mensagem'])) {
    echo $_SESSION['mensagem'];
  }

  //destruindo sessão
  unset($_SESSION['mensagem']);
  ?>

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
        <a href="#menu-inicial" id="menu-inicial-link"><span class="tooltip">Menu Inicial</span>
          <span class="material-symbols-outlined"> home </span>
          <span class="menu-item-label">Menu Inicial</span>
        </a>
      </li>

      <!-- minhas contratações -->
      <li>
        <a href="#minhas-contratacoes">
          <span class="tooltip">Minhas contratações</span>
          <span class="material-symbols-outlined"> work </span>
          <span class="menu-item-label">Minhas contratações</span>
        </a>
      </li>

      <!-- dados do pessoais -->
      <li>
        <a href="#dados-pessoais">
          <span class="tooltip">Dados do pessoais</span>
          <span class="material-symbols-outlined"> frame_person </span>
          <span class="menu-item-label">Dados do pessoais</span>
        </a>
      </li>

      <!-- portfolio -->
      <li>
        <a href="#portfolio">
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

      <!-- Opção de logout -->
      <li class="logout">
        <a href="../../php/script_logout.php">
          <span class="tooltip">Sair</span>
          <span class="material-symbols-outlined">logout</span>
          <span class="menu-item-label">Sair</span>
        </a>
      </li>
    </ul>


  </aside>

  <div class="container">
    <header class="header">
      <div class="logo">
        <img src="../../img/logo-escura.png" alt="" srcset="" style="height: 40px" />
      </div>

      <div class="profile">
        <!-- icon padrão de usuario -->
        <div class="profile-icon">
          <span class="material-symbols-outlined">person</span>
        </div>
        <!-- tipo + nome do usuario -->
        <div class="username">
          <span style="text-transform: uppercase;"> <?php echo $tipoUsuario . '.' ?></span>
          <span style="text-transform: capitalize;"><?php echo $nome_aluno ?></span>
        </div>
      </div>
    </header>

    <main class="content-wrapper">
      <!-- seção do menu inicial -->
      <section class="content" id="menu-inicial">

        <!-- swiper de categorias -->
        <div class="swiper mySwiper categorias-wrapper">
          <h4>Categorias</h4>
          <!-- categorias -->
          <div class="swiper-wrapper">
            <!-- categoria - arte e design -->
            <div id="categoria1" class="swiper-slide categoria-slide">
              <!-- icon da categoria -->
              <div class="categoria-icon">
                <span class="material-symbols-outlined"> palette </span>
              </div>
              <!-- titulo da categoria -->
              <h3>Arte & Design</h3>
              <!-- texto sobre subareas da categoria -->
              <p>
                Arte digital, desenho, design gráfico, design de produto, design UX/UI,...
              </p>

            </div>

            <!-- categoria - beleza e estetica -->
            <div id="categoria2" class="swiper-slide categoria-slide">
              <!-- icon da categoria -->
              <div class="categoria-icon">
                <span class="material-symbols-outlined"> health_and_beauty </span>
              </div>
              <!-- titulo da categoria -->
              <h3>Beleza e Estética</h3>
              <!-- texto sobre subareas da categoria -->
              <p>
                Personal, manicure e pedicure, maquiador(a), cabelereiro(a),...
              </p>

            </div>

            <!-- categoria - gestão e finanças -->
            <div id="categoria3" class="swiper-slide categoria-slide">
              <!-- icon da categoria -->
              <div class="categoria-icon">
                <span class="material-symbols-outlined"> attach_money </span>
              </div>
              <!-- titulo da categoria -->
              <h3>Gestão e Finanças</h3>
              <!-- texto sobre subareas da categoria -->
              <p>
                Analista financeiro, contador, gestor f...
              </p>

            </div>

            <!-- categoria - Manutenção de Computadores -->
            <div id="categoria4" class="swiper-slide categoria-slide">
              <!-- icon da categoria -->
              <div class="categoria-icon">
                <span class="material-symbols-outlined"> computer </span>
              </div>
              <!-- titulo da categoria -->
              <h3>Manutenção de Computadores</h3>
              <!-- texto sobre subareas da categoria -->
              <p>
                Técnico de TI, Admin de redes e sistemas,...
              </p>
            </div>

            <!-- categoria - Marketing e Vendas -->
            <div id="categoria5" class="swiper-slide categoria-slide">
              <!-- icon da categoria -->
              <div class="categoria-icon">
                <span class="material-symbols-outlined"> campaign </span>
              </div>
              <!-- titulo da categoria -->
              <h3>Marketing e Vendas</h3>
              <!-- texto sobre subareas da categoria -->
              <p>
                Gerente de produto, trader, promotor,...
              </p>

            </div>

            <!-- categoria - Projetos sociais -->
            <div id="categoria6" class="swiper-slide categoria-slide">
              <!-- icon da categoria -->
              <div class="categoria-icon">
                <span class="material-symbols-outlined"> handshake </span>
              </div>
              <!-- titulo da categoria -->
              <h3>Projetos sociais</h3>
              <!-- texto sobre subareas da categoria -->
              <p>
                Coordenador, assistente social, educador social, voluntário, psicólogo,...
              </p>

            </div>

            <!-- categoria - Suporte Administrativo -->
            <div id="categoria7" class="swiper-slide categoria-slide">
              <!-- icon da categoria -->
              <div class="categoria-icon">
                <span class="material-symbols-outlined"> business_center </span>
              </div>
              <!-- titulo da categoria -->
              <h3>Suporte Administrativo</h3>
              <!-- texto sobre subareas da categoria -->
              <p>
                Assistente de RH, logística e jurídico,...
              </p>

            </div>


            <!-- categoria - TI e Programação -->
            <div id="categoria8" class="swiper-slide categoria-slide">
              <!-- icon da categoria -->
              <div class="categoria-icon">
                <span class="material-symbols-outlined"> code </span>
              </div>
              <!-- titulo da categoria -->
              <h3>TI e Programação</h3>
              <!-- texto sobre subareas da categoria -->
              <p>
                Dev web, dev mobile, analista de sistemas,...
              </p>

            </div>

            <!-- categoria - Tradução e Conteúdos -->
            <div id="categoria9" class="swiper-slide categoria-slide">
              <!-- icon da categoria -->
              <div class="categoria-icon">
                <span class="material-symbols-outlined"> g_translate </span>
              </div>
              <!-- titulo da categoria -->
              <h3>Tradução e Conteúdos</h3>
              <!-- texto sobre subareas da categoria -->
              <p>
                Tradutor, redator, copywriter, editor,...
              </p>

            </div>

          </div>

          <div id="button-next" class="swiper-btn swiper-button-next"></div>
          <div id="button-prev" class="swiper-btn swiper-button-prev"></div>

        </div>

        <h4 style="margin-top: 20px;">Projetos publicados</h4>
        <!-- area dos projetos -->
        <div class="projetos-wrapper">
          <!-- vazendo varredura de cada projeto no banco -->
          <?php foreach ($projetos as $projeto) : ?>
            <!-- fazendo chamada do elemento projeto-anunciante.php -->
            <?php include("../../components/aluno-projeto.php"); ?>
          <?php endforeach; ?>
        </div>

        <!-- paginação -->
        <div class="pagination">
          <?php
          // Consulta para contar o total de projetos
          $total_projetos = $conn->query("SELECT COUNT(*) AS total FROM projeto")->fetch(PDO::FETCH_ASSOC);
          $total_paginas = ceil($total_projetos['total'] / $limite_result);

          // Botão Voltar
          if ($pagina_atual > 1) {
            echo "<a href='home.php?page=" . ($pagina_atual - 1) . "' class='pagination-btn'>Voltar
              <span class='icon material-symbols-outlined'>chevron_left</span></a>";
          }

          echo "<p>-</p>";

          // Botão Avançar
          if ($pagina_atual < $total_paginas) {
            echo "<a href='home.php?page=" . ($pagina_atual + 1) . "' class='pagination-btn'>
              <span class='icon material-symbols-outlined'> navigate_next </span>Próxima</a>";
          }
          ?>
        </div>
      </section>

      <!-- seção dados-pessoais -->
      <section class="content" id="dados-anuciante"> <?php echo $senha ?> </section>

      <!-- seção configuracoes -->
      <section class="content" id="configuracoes">
        <h4>Configuração</h4>

        <!-- div principal -->
        <div class="content-section">
          <!-- menu de opções -->
          <div class="section-options">
            <a href="#" class="section-link active" data-target="dadosAcesso-section">Dados de Acesso</a>
            <a href="#" class="section-link" data-target="excluirConta-section">Excluir Conta</a>
          </div>

          <!-- div - dados de acesso -->
          <div id="dadosAcesso-section" class="dados-acesso-section content-sections" style="display: flex;">
            <!-- formulario de dados de acesso -->
            <!-- fazendo chamada do elemento -->
            <?php include("../../components/aluno-dadosAcesso.php"); ?>
          </div>

          <!-- div - excluir conta -->
          <div id="excluirConta-section" class="content-sections">
            <!-- primeira coluna -->
            <div class="col">
              <!-- pergunta -->
              <form action="../../php/aluno/script_excluirConta.php" method="post">
                <h3>Você tem certeza que deseja excluir sua conta no POA?</h3>
                <!-- confirmação com checkbox' -->
                <label class="checkbox-input">
                  <input type="checkbox" name="confirmCheckbox" id="confirmCheckbox" required>
                  <span>Sim, tenho certeza que quero excluir minha conta</span>
                </label>

                <!-- button para excluir conta -->
                <button type="submit" class="excluirConta btn">Excluir Conta</button>
              </form>
            </div>

            <!-- segunda coluna -->
            <div class="col col-explicacao">
              <h2>Excluir conta</h2>
              <p>Ao excluir sua conta, todas as informações associadas a ela serão permanentemente removidas do nosso sistema. Isso inclui o seguinte:</p>
              <ul>
                <li>Todos os seus dados pessoais, como nome, endereço de e-mail e qualquer informação de perfil.</li>
                <li>Qualquer conteúdo que você tenha criado, como projetos, uploads de arquivos ou outras contribuições como anunciante.</li>
                <li>Seus registros de atividades, como histórico de login e interações recentes.</li>
              </ul>
              <p>
                Por favor, esteja ciente de que esta ação é irreversível. Uma vez que a conta for excluída, não será possível recuperar os dados ou restaurar o acesso à sua conta. Certifique-se de fazer o backup de qualquer informação importante antes de prosseguir com a exclusão da conta.
                <br><br>
                Além disso, os dois perfis, tanto de aluno quanto de anunciante, serão excluídos do sistema do Portal de Oportunidades Acadêmicas.
              </p>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>

  <!-- scripts -->
  <script src="../../js/linksAcesso.js"></script>
  <script src="../../js/aside.js"></script>
  <script src="../../js/modalConfirm.js"></script>
  <script src="../../js/projetoPorCategoria.js"></script>


  <!-- script swiper js -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <!-- script de configuração do swiper -->
  <script>
    var swiper = new Swiper(".mySwiper", {
      slidesPerView: 1,
      spaceBetween: 20,
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      breakpoints: {
        640: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        768: {
          slidesPerView: 4,
          spaceBetween: 20,
        },
        1024: {
          slidesPerView: 7,
          spaceBetween: 20,
        },
      },
    });
  </script>

</body>

</html>