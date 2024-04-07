<?php
session_start();
ob_start();

// Incluindo arquivo de conexão com o banco de dados
include("../../php/conexao.php");
$conn = conectar();

// Recebendo ID do projeto que deseja visualizar as candidaturas através da URL
$projeto_id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

// Verificando se a variável ID não está vazia (empty)
if (empty($projeto_id)) {
    $_SESSION['mensagem'] = "<p style='color: #f00; text-align: center;'>Erro: Projeto não encontrado</p>";
    header("Location: ../../pages/anunciante/home.php");
}

// Consultar os alunos que se candidataram para o projeto
$sql_candidaturas = "SELECT aluno.id AS aluno_id, aluno.nome AS nome_aluno
                     FROM candidatura 
                     INNER JOIN aluno ON candidatura.aluno_id = aluno.id 
                     WHERE candidatura.projeto_id = :projeto_id";
$stmt_candidaturas = $conn->prepare($sql_candidaturas);
$stmt_candidaturas->bindParam(':projeto_id', $projeto_id);
$stmt_candidaturas->execute();
$candidaturas = $stmt_candidaturas->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Altere os dados do seu projeto - POA</title>

    <!-- links css -->
    <link rel="stylesheet" href="../../../styles/main.css" />
    <link rel="stylesheet" href="../../../styles/home.css" />

    <!-- link favicon -->
    <link rel="shortcut icon" href="../../../img/favicon.png" type="image/x-icon" />

    <!-- link font symbols -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>
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
            <div class="logo">
                <img src="../../../img/logo-escura.png" alt="" srcset="" style="height: 40px" />
            </div>
        </header>

        <main class="content-wrapper">
            <!-- seção do menu inicial -->
            <section class="content">
                <?php

                // Verificar se há candidaturas
                if ($stmt_candidaturas->rowCount() > 0) {
                    // Exibir os detalhes das candidaturas
                    foreach ($candidaturas as $candidatura) {
                        echo "
        <div class='candidato'>
        <div class='profile'>
            <div class='profile-icon'></div>
            <span>{$candidatura['nome_aluno']}</span>
        </div>
        
        <a href='mostrar_dados_aluno.php?id={$candidatura['aluno_id']}' id='verPorfolio' class='btn outline-btn'>Ver portfólio</a>
        </div>";
                    }
                } else {
                    echo "<p>Nenhum aluno se candidatou para este projeto ainda.</p>";
                }

                ?>
            </section>

        </main>
    </div>

</body>

</html>