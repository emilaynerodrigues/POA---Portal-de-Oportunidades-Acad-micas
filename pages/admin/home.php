<?php
session_start();

// Verificando se o usuário está logado como admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    // Se não estiver logado como admin, redirecione para a página de login
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

// Obtendo o ID do admin da sessão
$user_id = $_SESSION['user_id'];
$tipoUsuario = $_SESSION['user_type'];

// Incluir o arquivo de conexão com o banco de dados
include("../../php/conexao.php");
$conn = conectar();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ADMINISTRADOR</title>

    <!-- links css -->
    <link rel="stylesheet" href="../../styles/main.css" />
    <link rel="stylesheet" href="../../styles/home.css" />
    <link rel="stylesheet" href="../../styles/crud-projeto.css" />

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

    <!-- menu lateral -->
    <aside class="sidebar">
        <!-- Ícone de hambúrguer -->
        <div class="toggle-container">
            <label for="menuToggle" id="menuIcon">&#9776;</label>
            <input type="checkbox" id="menuToggle" />
        </div>
        <!-- Opções do menu -->
        <ul id="menuOptions">
            <!-- Anunciantes -->
            <li>
                <a href="#home" id="menu-link"><span class="tooltip">Menu Inicial</span>
                    <span class="material-symbols-outlined"> home </span>
                    <span class="menu-item-label">Menu Inicial</span>
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
                    <span style="text-transform: uppercase;"> <?php echo $tipoUsuario ?></span>
                </div>
            </div>
        </header>

        <main class="content-wrapper ">
            <!-- seção do anunciantes -->
            <section class="content" id="home">

                <h4>Lista de usuários</h4>

                <div class="content-admin">
                    <a href="lista-alunos.php" class="content-item">
                        <h3>Alunos</h3>
                        <p>Clique para visualizar os alunos cadastrados no sistema</p>
                    </a>

                    <a href="lista-anunciantes.php" class="content-item">
                        <h3>Anunciantes</h3>
                        <p>Clique para visualizar os anunciantes cadastrados no sistema</p>
                    </a>
                </div>

            </section>


        </main>



    </div>

    <script src="../../js/modalConfirm.js"></script>
    <script src="../../js/aside.js"></script>
    <script src="js/fecharModal.js"></script>


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