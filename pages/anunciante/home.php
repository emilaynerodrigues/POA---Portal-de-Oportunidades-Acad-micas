<?php
session_start();

// Verificando se o usuário está logado como anunciante
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'anunciante') {
  // Se não estiver logado como anunciante, redirecione para a página de login
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

// Obtendo o ID do anunciante da sessão --> vindo da página de login
$user_id = $_SESSION['user_id'];
$tipoUsuario = $_SESSION['user_type'];

// Incluir o arquivo de conexão com o banco de dados
include("../../php/conexao.php");
$conn = conectar();

// Consulta ao banco de dados para obter os detalhes do anunciante
$query = $conn->prepare("SELECT nome, email, senha FROM anunciante WHERE id = :id");
$query->bindValue(":id", $user_id);
$query->execute();
$anunciante = $query->fetch(PDO::FETCH_ASSOC);

// Acessando os detalhes do anunciante
$nome_anunciante = $anunciante['nome'];
$email_anunciante = $anunciante['email'];
$senha = $anunciante['senha'];
// Lógica de paginação
$limite_result = 6; // Definir a quantidade de projetos por página
$pagina_atual = isset($_GET['page']) ? $_GET['page'] : 1; // Obter a página atual da URL

$inicio = ($pagina_atual - 1) * $limite_result; // Calcular o início da seleção de registros

// Consulta para obter os projetos do anunciante atualmente logado, ordenados pela data de postagem em ordem decrescente (mais recentes primeiro)
$stmt_projeto = $conn->prepare("SELECT * FROM projeto WHERE anunciante_id = :anunciante_id ORDER BY dataPostagem DESC LIMIT $inicio, $limite_result");
$stmt_projeto->bindValue(":anunciante_id", $user_id);
$stmt_projeto->execute();
$projetos = $stmt_projeto->fetchAll(PDO::FETCH_ASSOC);

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

  <!-- mostrando mensagem de projeto excluido-->
  <?php
  //verificando se existe a sessão
  if (isset($_SESSION['projeto-excluido'])) {
    echo $_SESSION['projeto-excluido'];
  }

  //destruindo sessão
  unset($_SESSION['projeto-excluido']);
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

      <!-- dados do anuciante -->
      <li>
        <a href="#dados-anuciante" id="dados-anuciante-link">
          <span class="tooltip">Dados do anuciante</span>
          <span class="material-symbols-outlined"> frame_person </span>
          <span class="menu-item-label">Dados do anuciante</span>
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
        <a href="#sair" id="sair-link" onclick="abrirModalSair(event)">
          <span class="tooltip">Sair</span>
          <span class="material-symbols-outlined">logout</span>
          <span class="menu-item-label">Sair</span>
        </a>
      </li>
    </ul>


  </aside>

  <div class="container">

    <!-- modal confirmação de logout -->
    <div id="modalSair" class='modal modal-session' style='display: none;'>
      <div class='modal-content'>
        <a href="home.php"><span class="close-icon material-symbols-outlined closeIcon"> close </span></a>

        <span class='icon material-symbols-outlined'> cancel </span>
        <h3>Está saindo?</h3>
        <p>Tem certeza de que deseja fazer sair do sistema?</p>
        <div class='btn-wrapper'>
          <a href='home.php' class='btn small-btn cancel-btn closeIcon'>Cancelar</a>
          <a href="../../php/script_logout.php" class="btn small-btn">Sim, sair</a>
        </div>
      </div>
    </div>

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
          <span style="text-transform: capitalize;"><?php echo $nome_anunciante ?></span>
        </div>
      </div>
    </header>

    <main class="content-wrapper">
      <!-- seção do menu inicial -->
      <section class="content" id="menu-inicial">
        <h4>Meus projetos publicados</h4>

        <!-- area dos projetos -->
        <div class="projetos-wrapper">
          <!-- vazendo varredura de cada projeto no banco -->
          <?php foreach ($projetos as $projeto) : ?>
            <!-- fazendo chamada do elemento projeto-anunciante.php -->
            <?php include("../../components/anunciante-projeto.php"); ?>
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

        <a href="adicionar-projeto.php" class="add-btn">
          <span class="material-symbols-outlined"> add </span>
        </a>
      </section>

      <!-- seção dados-pessoais -->
      <section class="content" id="dados-anuciante">

        <?php include("../../components/anunciante-dadosPessoais.php"); ?>

      </section>

      <!-- seção configuracoes -->
      <section class="content" id="configuracoes">
        <h4>Configuração</h4>

        <!-- div principal -->
        <div class="content-section">
          <!-- menu de opções -->
          <div class="section-options">
            <a href="#" class="section-link active" data-target="dadosAcesso-section">Dados de Acesso</a>
            <a href="#" class="section-link" data-target="excluirConta-section">Desativar Conta</a>
            <a href="#" class="section-link" data-target="suporte-section">Suporte</a>
          </div>

          <!-- aba - dados de acesso -->
          <div id="dadosAcesso-section" class="dados-acesso-section content-sections" style="display: flex;">
            <!-- formulario de dados de acesso -->
            <!-- fazendo chamada do elemento -->
            <?php include("../../components/anunciante-dadosAcesso.php"); ?>
          </div>

          <!-- aba - excluir conta -->
          <div id="excluirConta-section" class="content-sections">
            <!-- primeira coluna -->
            <div class="col">
              <!-- pergunta -->
              <form action="../../php/anunciante/script_desativarConta.php" method="post">
                <h3>Você tem certeza que deseja desativar sua conta no POA?</h3>
                <!-- confirmação com checkbox' -->
                <label class="checkbox-input">
                  <input type="checkbox" name="confirmCheckbox" id="confirmCheckbox" required>
                  <span>Sim, tenho certeza que quero desativar minha conta</span>
                </label>

                <!-- button para excluir conta -->
                <button type="submit" class="excluirConta btn">Desativar Conta</button>
              </form>
            </div>

            <!-- segunda coluna -->
            <div class="col col-explicacao">
              <h2>Desativar conta</h2>
              <p>Ao desativar sua conta, todos os seus dados permanecerão armazenados em nosso sistema por um período de tempo. Durante esse período, você terá a oportunidade de reativar sua conta, se desejar. Por favor, esteja ciente de que esta ação é irreversível e que após o período de armazenamento dos dados, eles serão permanentemente removidos do nosso sistema. Isso inclui o seguinte:</p>
              <ul>
                <li>Todos os seus dados pessoais, como nome, endereço de e-mail e qualquer informação de perfil.</li>
                <li>Qualquer conteúdo que você tenha criado, como projetos, uploads de arquivos ou outras contribuições como anunciante.</li>
              </ul>
              <p>
                Certifique-se de fazer o backup de qualquer informação importante antes de prosseguir com a desativação da sua conta.
              </p>
            </div>

          </div>

          <!-- aba - suporte -->
          <div id="suporte-section" class="content-sections">
            <!-- primeira coluna -->
            <div class="col col-explicacao">
              <!-- Título e informações sobre o suporte -->
              <h2>Entre em Contato</h2>
              <p>Em caso de problemas ou dúvidas, entre em contato com nosso suporte técnico através dos contatos abaixo:</p>

              <ul>
                <li>E-mail: suporte@poa.com</li>
                <li>WhatsApp: <a href="https://wa.me/55000000000">+55 92 00000-0000</a></li>
                <li>Facebook: <a href="https://www.facebook.com/paginaexemplo">Página Exemplo</a></li>
                <!-- Adicione outras redes sociais ou métodos de contato conforme necessário -->
              </ul>

              <p>Nossa equipe de suporte terá o prazer de ajudá-lo com qualquer problema que você esteja enfrentando.</p>
            </div>
          </div>
          
        </div>
      </section>
    </main>
  </div>

  <script src="../../js/linksAcesso.js"></script>
  <script src="../../js/aside.js"></script>
  <script src="../../js/modalConfirm.js"></script>

  <!-- script para abrir modal de sair -->
  <script>
    function abrirModalSair(event) {
      event.preventDefault(); // Impede o comportamento padrão do link
      var modal = document.getElementById("modalSair");
      modal.style.display = "flex";
    }
  </script>

</body>

</html>