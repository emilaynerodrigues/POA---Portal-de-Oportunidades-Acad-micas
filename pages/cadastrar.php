<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Faça seu cadastro - POA</title>

  <!-- links css -->
  <link rel="stylesheet" href="../styles/main.css" />
  <link rel="stylesheet" href="../styles/initial.css" />
  <!-- <link rel="stylesheet" href="../styles/home.css"> -->

  <!-- link favicon -->
  <link rel="shortcut icon" href="../img/favicon.png" type="image/x-icon" />

  <!-- link font symbols -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body id="cadastrar">
  <header class="header">
    <a href="../index.php">
      <img src="../img/logo-escura.png" alt="Logo do Portal de Oportunidades Acadêmicas" class="logo" style="height: 40px" />
    </a>
  </header>

  <!-- mostrando mensagem de usuario cadastro ou e-mail já em uso -->
  <?php
  //verificando se existe a sessão
  if (isset($_SESSION['mensagem'])) {
    echo $_SESSION['mensagem'];
  }

  //destruindo sessão
  unset($_SESSION['mensagem']);
  ?>


  <main class="container">
    <!-- conteudo principal -->
    <div class="content-wrapper">
      <!-- informação call to action -->
      <div class="cta-info">
        <h2>Criar Conta</h2>
        <p>
          Crie uma conta para encontrar e publicar projetos no Portal de
          Oportunidades
        </p>
      </div>

      <form action="../php/script_cadastroUsuario.php" method="post">

        <!-- nome -->
        <div class="form-item">
          <input type="text" name="nome" id="nome-input" pattern="[a-zA-ZÀ-ÿ\s]+" title="O nome não deve conter números ou caracteres especiais" required />
          <label for="nome-input">Nome completo</label>
        </div>

        <!-- email -->
        <div class="form-item">
          <input type="email" name="email" id="email-input" required />
          <label for="email-input">E-mail</label>
        </div>

        <!-- senha -->
        <div class="form-item">
          <input type="password" name="senha" id="senha-input" required />
          <label for="senha-input">Senha</label>
          <span id="toggle-senha" class="toggle-senha password material-symbols-outlined">visibility</span>
        </div>

        <!-- usuario de trabalho -->
        <div class="form-item select">
          <select name="tipo_usuario" id="usuario-select" onchange="mostrarMatricula(this)" required>
            <option value="" disabled selected hidden>Selecione seu tipo usuario</option>
            <option value="aluno">Aluno</option>
            <option value="anunciante">Anunciante</option>
          </select>

          <label for="usuario-select">Tipo de Usuário</label>
        </div>

        <!-- matricula -->
        <div class="form-item" id="matriculaContainer" style="display: none">
          <input type="text" name="matricula" id="matricula-input" />
          <label for="matricula-input">Matrícula</label>
        </div>


        <button type="submit">Cadastrar</button>
      </form>

      <!-- call to action prompt -->
      <div class="cta-prompt">
        <p>
          Já possui uma conta conta?
          <span><a href="../pages/login.php">Faça login</a></span>
        </p>
      </div>
    </div>

    <!-- imagem -->
    <img src="../img/cadastrar-img.svg" alt="Representação gráfica de jovem com um telefone celular" class="img-inicio" style="height: 400px" />
  </main>

  <!-- vetor de fundo -->
  <img src="../img/elipse.svg" alt="elipse" class="absolute" />

  <script src="../js/senha.js"></script>
</body>

</html>