<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Portal de Oportunidades Acadêmicas</title>

  <!-- links css -->
  <link rel="stylesheet" href="styles/main.css" />
  <link rel="stylesheet" href="styles/initial.css">

  <!-- link favicon -->
  <link rel="shortcut icon" href="../img/favicon.png" type="image/x-icon" />

  <!-- link font symbols -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body id="inicio">
  <header class="header">
    <a href="index.html">
      <img src="img/logo-escura.png" alt="Logo do Portal de Oportunidades Acadêmicas" class="logo" style="height: 50px" />
    </a>

    <nav class="menu-nav">
      <a href="" class="menu-item">como funciona</a>
      <a href="pages/cadastrar.php" class="menu-item">cadastra-se</a>
      <a href="pages/login.php" class="btn small-btn white-hover">entrar</a>
    </nav>
  </header>

  <!-- mostrando mensagem de usuario não autorizado-->
  <?php
  //verificando se existe a sessão
  if (isset($_SESSION['mensagem'])) {
    echo $_SESSION['mensagem'];
  }

  //destruindo sessão
  unset($_SESSION['mensagem']);
  ?>


  <main class="container">
    <!-- conteudo textual + botões -->
    <div class="content-hero">
      <h1>
        Encontre e publique projetos <span>freelancers</span>, tudo online
      </h1>
      <p>
        Platafoma para divulgação e contratação de projetos freelancers para
        estudantes do SENAC
      </p>
      <div class="btn-wrapper">
        <a href="pages/login.php" class="btn normal-btn">encontrar projeto</a>
        <a href="pages/login.php" class="btn normal-btn outline-btn">publicar projeto</a>
      </div>
    </div>

    <!-- imagem -->
    <img src="img/inicio-img.svg" alt="Representação gráfica de dois jovens (uma moça e um rapaz) trocando informações" class="img-inicio" style="height: 400px" />
  </main>
  <!-- vetor de fundo -->
  <img src="img/elipse.svg" alt="elipse" class="absolute" />
  <script src="js/main.js"></script>
</body>

</html>