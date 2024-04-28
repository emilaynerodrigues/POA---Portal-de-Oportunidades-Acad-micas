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
                <a href="#anunciantes" id="anunciante-link"><span class="tooltip">Anunciantes</span>
                    <span class="material-symbols-outlined"> id_card </span>
                    <span class="menu-item-label">Anunciantes</span>
                </a>
            </li>

            <!-- Alunos -->
            <li>
                <a href="#alunos" id="alunos-link">
                    <span class="tooltip">Alunos</span>
                    <span class="material-symbols-outlined"> group </span>
                    <span class="menu-item-label">Alunos</span>
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

        <main class="content-wrapper">
            <!-- seção do anunciantes -->
            <section class="content" id="anunciantes">
                <h4>Lista de anunciantes</h4>
                <table>
                    <tr class="h-table">
                        <th>Nome do anunciante</th>
                        <th>Nome da empresa</th>
                        <th>Email</th>
                        <th class="action-item">Projetos</th>
                        <th class="action-item">Alterar</th>
                        <th class="action-item">Desativar</th>
                        <th class="action-item">Deletar</th>
                    </tr>
                    <?php
                    // Consultar dados dos anunciantes
                    $queryAnunciantes = $conn->prepare("SELECT * FROM anunciante");
                    $queryAnunciantes->execute();
                    $anunciantes = $queryAnunciantes->fetchAll(PDO::FETCH_ASSOC);

                    // Exibir os anunciantes
                    foreach ($anunciantes as $anunciante) {
                        echo "<tr class='b-table'>";
                        echo "<td class='name'>{$anunciante['nome']}</td>";
                        echo "<td class='name'>{$anunciante['nome_empresa']}</td>";
                        echo "<td class='email'>{$anunciante['email']}</td>";
                        echo "<td class='action-item'><a href='?anunciante_id=" . $anunciante['id'] . "'><span class='material-symbols-outlined'>visibility</span></a></td>"; // Adicione este link para visualizar os projetos do anunciante
                        echo "<td class='action-item'><a href='anunciante-alterar.php?id=" . $anunciante['id'] . "'><span class='material-symbols-outlined'>edit_square</span></a></td>";
                        echo "<td class='action-item'><a href='#'><span class='material-symbols-outlined'>delete</span></a></td>";
                        echo "<td class='action-item'><a href='#' ><span class='delete-action material-symbols-outlined'>delete</span></a></td>";
                        echo "</tr>";
                    }

                    ?>
                </table>
            </section>

            <!-- seção do alunos -->
            <section class="content" id="alunos">
                <h4>Lista de alunos</h4>
                <table class="alunos-table">
                    <tr class="h-table">
                        <th>Nome do aluno</th>
                        <th>Email</th>
                        <th class="action-item">Candidaturas</th>
                        <th class="action-item">Alterar</th>
                        <th class="action-item">Deletar</th>
                    </tr>
                    <?php
                    // Consultar dados dos alunos
                    $queryAlunos = $conn->prepare("SELECT * FROM aluno");
                    $queryAlunos->execute();
                    $alunos = $queryAlunos->fetchAll(PDO::FETCH_ASSOC);

                    // Exibir os alunos
                    foreach ($alunos as $aluno) {
                        echo "<tr class='b-table'>";
                        echo "<td class='name'>{$aluno['nome']}</td>";
                        echo "<td class='email'>{$aluno['email']}</td>";
                        echo "<td class='action-item'><a href='?aluno_id=" . $aluno['id'] . "'><span class='material-symbols-outlined'>view_list</span></a></td>"; // Adicione este link para visualizar os projetos do anunciante
                        echo "<td class='action-item'><a href='aluno-alterar.php?id=" . $aluno['id'] . "' target='_blank'><span class='material-symbols-outlined'>edit_square</span></a></td>";
                        echo "<td class='action-item'><a href='#'><span class='delete-action material-symbols-outlined'>delete</span></a></td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </section>
        </main>

        <!-- Modal para exibir os projetos do usuário -->
        <div id="modalProjetos" class="modal modal-confirm" style="display: none;">
            <div class="modal-content">
                <span class='modal-close close-icon material-symbols-outlined closeIconProjetos'> close </span>

                <div class="wrapper-container">
                    <div class="projetos-wrapper">
                        <!-- Conteúdo do modal de projetos aqui -->
                        <?php
                        // Verifica se o ID do anunciante está presente na URL
                        if (isset($_GET['anunciante_id'])) {
                            $anunciante_id = $_GET['anunciante_id'];
                            // Exibe o script JavaScript para abrir o modal de projetos
                            echo "<script>document.getElementById('modalProjetos').style.display = 'flex';</script>";
                            // Consulta SQL para selecionar os projetos associados ao anunciante com o ID fornecido
                            $sql_projetos = "SELECT * FROM projeto WHERE anunciante_id = :anunciante_id";
                            $stmt_projetos = $conn->prepare($sql_projetos);
                            $stmt_projetos->bindParam(':anunciante_id', $anunciante_id);
                            $stmt_projetos->execute();
                            // Verifica se a consulta foi executada com sucesso
                            if ($stmt_projetos) {
                                // Verifica se há projetos associados a esse anunciante
                                if ($stmt_projetos->rowCount() > 0) {
                                    // Exibindo os detalhes dos projetos associados a esse anunciante
                                    foreach ($stmt_projetos as $projeto) {
                                        echo "
                                        <div class='candidato'>
                                            <div class='profile'>
                                                <span id='nome'>{$projeto['titulo']}</span>
                                            </div>
                                            <a href='mostrar-projeto.php?id={$projeto['id']}' target='_blank' id='verProjeto' class='btn outline-btn'>Ver detalhes</a>
                                        </div>";
                                    }
                                } else {
                                    echo "<p>Ops! Parece que este anunciante ainda não postou nenhum projeto.</p>";
                                }
                            } else {
                                // Se a consulta não foi executada com sucesso, exibir uma mensagem de erro
                                echo "<p>Erro ao executar a consulta.</p>";
                            }
                        } else {
                            // Se o anunciante_id não estiver presente na URL, exibir uma mensagem de erro
                            echo '<p>ID do anunciante não especificado</p>';
                        }
                        ?>
                    </div>
                </div>

            </div>
        </div>


    </div>

    <script src="../../js/modalConfirm.js"></script>
    <script src="../../js/aside.js"></script>

    <!-- script para abrir modal de sair -->
    <script>
        function abrirModalSair(event) {
            event.preventDefault(); // Impede o comportamento padrão do link
            var modal = document.getElementById("modalSair");
            modal.style.display = "flex";
        }

        // Seleciona todos os elementos com a classe 'verProjetos'
        var verProjetosLinks = document.querySelectorAll('.verProjetos');

        // Adiciona um evento de clique a cada link 'verProjetos'
        verProjetosLinks.forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault(); // Impede o comportamento padrão do link

                // Abre o modal de projetos
                var modalProjetos = document.getElementById('modalProjetos');
                modalProjetos.style.display = 'flex';
            });
        });

        // função para fechar o modal de candidatos e reabrir o modal de informações do projeto
        function fecharModalProjetos() {
            var modalProjetos = document.getElementById("modalProjetos");

            modalProjetos.style.display = "none"; // fechando o modal de candidatos

            // atualizando a URL da página para remover o parâmetro do ID do projeto --> restaurando ao padrão da url
            history.replaceState({}, document.title, window.location.pathname);
        }

        var closeIconProjetos = document.querySelectorAll(".closeIconProjetos");
        closeIconProjetos.forEach(function(closeIconProjetos) {
            closeIconProjetos.addEventListener("click", fecharModalProjetos);
        });
    </script>


</body>

</html>