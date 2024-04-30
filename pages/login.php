<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Faça Login - POA</title>

  <!-- links css -->
  <link rel="stylesheet" href="../styles/main.css" />
  <link rel="stylesheet" href="../styles/initial.css" />

  <!-- link favicon -->
  <link rel="shortcut icon" href="../img/favicon.png" type="image/x-icon" />

  <!-- link font symbols -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body id="login">
  <header class="header">
    <a href="../index.php">
      <img src="../img/logo-escura.png" alt="Logo do Portal de Oportunidades Acadêmicas" class="logo" style="height: 40px" />
    </a>
  </header>

  <!-- mostrando mensagem de preencha todos os dados e/ou dados incorretos -->
  <?php
  //verificando se existe a sessão
  if (isset($_SESSION['mensagem'])) {
    echo $_SESSION['mensagem'];
  }

  //destruindo sessão
  unset($_SESSION['mensagem']);
  ?>

  <!-- mostrando mensagem de preencha todos os dados e/ou dados incorretos -->
  <?php
  //verificando se existe a sessão
  if (isset($_SESSION['reativar_conta'])) {
    echo $_SESSION['reativar_conta'];
  }

  //destruindo sessão
  unset($_SESSION['reativar_conta']);
  ?>


  <main class="container">
    <!-- imagem -->
    <img src="../img/login-img.svg" alt="Representação gráfica de dois jovens (uma moça e um rapaz) trocando informações" class="img-inicio" style="height: 400px" />
    <!-- conteudo principal -->
    <div class="content-wrapper">
      <!-- informação call to action -->
      <div class="cta-info">
        <h2>Entrar na sua conta</h2>
        <p>
          Entre em sua conta para encontrar e publicar projetos no Portal de
          Oportunidades Acadêmicas
        </p>
      </div>

      <!-- formulário de login -->
      <form action="../php/script_loginUsuario.php" method="post">
        <!-- cpf -->
        <div class="form-item">
          <input type="text" name="email" id="email-input" required />
          <label for="email-input">E-mail</label>
        </div>
        <!-- senha -->
        <div class="form-item">
          <input type="password" name="senha" id="senha-input" required />
          <label for="senha-input">Senha</label>
          <span id="toggle-senha" class="toggle-senha password material-symbols-outlined">visibility</span>
        </div>

        <button type="submit">Entrar</button>
      </form>

      <!-- call to action prompt -->
      <div class="cta-prompt">
        <p>
          Não possui conta?
          <span><a href="../pages/cadastrar.php">Crie uma conta</a></span>
        </p>
      </div>
    </div>
  </main>

  <!-- vetor de fundo -->
  <img src="../img/elipse.svg" alt="elipse" class="absolute" />

  <script src="../js/aside.js"></script>
  <script src="../js/senha.js"></script>
  <script src="../js/fecharModal.js"></script>

</body>

</html>