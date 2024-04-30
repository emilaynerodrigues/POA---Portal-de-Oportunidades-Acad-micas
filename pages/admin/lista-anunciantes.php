<?php
// iniciando sessão
session_start();
ob_start(); //limpando buffer

// Verificando se o usuário está logado como anunciante
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
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

//fazendo conexão com o banco de dados
include("../../php/conexao.php");
$conn = conectar();

// Lógica de paginação
$limite_result = 8; // Definir a quantidade de projetos por página
$pagina_atual = isset($_GET['page']) ? $_GET['page'] : 1; // Obter a página atual da URL

$inicio = ($pagina_atual - 1) * $limite_result; // Calcular o início da seleção de registros

// Consulta ao banco de dados para obter os anunciantes para esta página
$query_anunciantes = $conn->prepare("SELECT * FROM anunciante ORDER BY nome DESC LIMIT $inicio, $limite_result");
$query_anunciantes->execute();
$anunciantes = $query_anunciantes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lista de anunciantes - POA</title>

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

    <!-- menu lateral -->
    <aside class="sidebar">
        <!-- Ícone de hambúrguer -->
        <div class="toggle-container">
        </div>

        <!-- Opções do menu -->
        <ul id="menuOptions">
        </ul>

    </aside>

    <div class="container">
        <header class="header">
            <a href="home.php" class="logo">
                <img src="../../img/logo-escura.png" alt="" srcset="" style="height: 40px" />
            </a>
        </header>

        <main class="content-wrapper">
            <!-- seção do menu inicial -->
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
                    // Exibir os anunciantes
                    foreach ($anunciantes as $anunciante) {
                        echo "<tr class='b-table'>";
                        echo "<td class='name'>{$anunciante['nome']}</td>";
                        echo "<td class='name'>{$anunciante['nome_empresa']}</td>";
                        echo "<td class='email'>{$anunciante['email']}</td>";
                        echo "<td class='action-item'><a href='?anunciante_id=" . $anunciante['id'] . "'><span class='material-symbols-outlined'>visibility</span></a></td>"; // Adicione este link para visualizar os projetos do anunciante
                        echo "<td class='action-item'><a href='anunciante-alterar.php?id=" . $anunciante['id'] . "'><span class='material-symbols-outlined'>edit_square</span></a></td>";
                        echo "<td class='action-item'><a href='#'><span class='material-symbols-outlined'>disabled_by_default</span></a></td>";
                        echo "<td class='action-item'><a href='#' class='deleteAnunciante' data-anunciante-id=" . $anunciante['id'] . "'><span class='delete-action material-symbols-outlined'>delete</span></a></td>";
                        echo "</tr>";
                    }
                    ?>
                </table>

                <div class="pagination">
                    <?php
                    // Consulta para contar o total de anunciantes
                    $total_anunciantes = $conn->query("SELECT COUNT(*) AS total FROM anunciante")->fetch(PDO::FETCH_ASSOC);
                    $total_paginas = ceil($total_anunciantes['total'] / $limite_result);

                    // Botão Voltar
                    if ($pagina_atual > 1) {
                        echo "<a href='lista-anunciantes.php?page=" . ($pagina_atual - 1) . "' class='pagination-btn'>Voltar
              <span class='icon material-symbols-outlined'>chevron_left</span></a>";
                    }

                    echo "<p>-</p>";

                    // Botão Avançar
                    if ($pagina_atual < $total_paginas) {
                        echo "<a href='lista-anunciantes.php?page=" . ($pagina_atual + 1) . "' class='pagination-btn'>
              <span class='icon material-symbols-outlined'> navigate_next </span>Próxima</a>";
                    }
                    ?>
                </div>


            </section>

        </main>
    </div>


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


    <!-- modal de confirmação de deletar anunciante de dados -->
    <div class='modal modal-delete' id="modalAnunciante">
        <div class='modal-content'>
            <span class='modal-close close-icon material-symbols-outlined closeModal'> close </span>
            <span class='icon material-symbols-outlined'> help </span>
            <h3>Tem certeza?</h3>
            <p>Todos os dados vinculados ao anunciante serão apagados permanentemente da base de dados. Você tem certeza que deseja continuar?</p>
            <div class='btn-wrapper'>
                <a href='#' class='btn small-btn cancel-btn closeModal'>Cancelar</a>
                <a href='' id="confirmButton" class='btn small-btn'>Sim</a>
            </div>
        </div>
    </div>

    <script src="../../js/modalConfirm.js"></script>
    <script src="../../js/fecharModal.js"></script>


    <!-- script para abrir modal de sair -->
    <script>
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
        // Adicione um evento de clique aos ícones de exclusão na tabela de anunciantes
        var deleteAnuncianteIcons = document.querySelectorAll('.deleteAnunciante');
        deleteAnuncianteIcons.forEach(function(icon) {
            icon.addEventListener('click', function(event) {
                event.preventDefault();
                var anuncianteId = this.getAttribute('data-anunciante-id');
                console.log(anuncianteId)
                // Abrir o modal de confirmação
                var modalAnunciante = document.getElementById('modalAnunciante');
                modalAnunciante.style.display = 'flex';

                // Adicionar o ID do anunciante ao link de confirmação no modal
                var confirmButton = document.getElementById('confirmButton');
                confirmButton.setAttribute('href', '../../php/admin/script_deletarAnunciante.php?anunciante_id=' + anuncianteId);
            });
        });
    </script>

</body>

</html>