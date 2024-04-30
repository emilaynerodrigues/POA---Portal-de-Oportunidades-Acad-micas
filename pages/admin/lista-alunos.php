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

// Consulta ao banco de dados para obter os alunos para esta página
$query_alunos = $conn->prepare("SELECT * FROM aluno ORDER BY nome DESC LIMIT $inicio, $limite_result");
$query_alunos->execute();
$alunos = $query_alunos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lista de alunos - POA</title>

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

                <div class="pagination">
                    <?php
                    // Consulta para contar o total de alunos
                    $total_alunos = $conn->query("SELECT COUNT(*) AS total FROM aluno")->fetch(PDO::FETCH_ASSOC);
                    $total_paginas = ceil($total_alunos['total'] / $limite_result);

                    // Botão Voltar
                    if ($pagina_atual > 1) {
                        echo "<a href='lista-alunos.php?page=" . ($pagina_atual - 1) . "' class='pagination-btn'>Voltar
              <span class='icon material-symbols-outlined'>chevron_left</span></a>";
                    }

                    echo "<p>-</p>";

                    // Botão Avançar
                    if ($pagina_atual < $total_paginas) {
                        echo "<a href='lista-alunos.php?page=" . ($pagina_atual + 1) . "' class='pagination-btn'>
              <span class='icon material-symbols-outlined'> navigate_next </span>Próxima</a>";
                    }
                    ?>
                </div>
            </section>

        </main>
    </div>

    <script src="../../js/modalConfirm.js"></script>
</body>

</html>