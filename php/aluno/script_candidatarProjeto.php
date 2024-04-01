<?php
session_start(); // iniciando sessão
ob_start(); // limpando o buffer de saída

// fazendo a conexão com o banco de dados
include("../conexao.php");
$conn = conectar(); // estabelece a conexão com o banco de dados

echo "<script>console.log('Projeto ID: " . $projeto_id . "');</script>";
echo "<script>console.log('Aluno ID: " . $aluno_id . "');</script>";


// recebendo o ID do projeto através da URL --> vem a partir da url do modal de candidatura
$projeto_id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

// recebendo o ID do aluno --> vem a partir da session no login
$aluno_id = $_SESSION['user_id'];

// Verifica se o ID do projeto não está vazio
if (empty($projeto_id)) {
    $_SESSION['mensagem'] = "<p style='color: red; text-align: center;'>Erro: Projeto não encontrado</p>";
    header("Location: ../../pages/aluno/home.php"); // voltando para a página inicial do aluno em caso de erro
    exit(); // encerrando o script 
}

// verificando se o aluno já se candidatou a este projeto
$query_check = "SELECT * FROM candidatura WHERE projeto_id=:projeto_id AND aluno_id=:aluno_id";
$stmt_check = $conn->prepare($query_check); // preparando a consulta SQL
$stmt_check->bindParam(":projeto_id", $projeto_id, PDO::PARAM_INT); // definindo o parâmetro do ID do projeto
$stmt_check->bindParam(":aluno_id", $aluno_id, PDO::PARAM_INT); // definindo o parâmetro do ID do aluno
$stmt_check->execute(); // executando a consulta

// aluno já está candidatado ao projeto??? deve mostrar mensagem --> se o contator de linhas for maior que 0 significa que o 
//aluno já esta candidatado nesse projeto
if ($stmt_check->rowCount() > 0) {
    header("Location: ../../pages/aluno/home.php");
    $_SESSION["mensagem"] = " 
    <!-- Modal de confirmação - Ops, você já se candidatou! -->
    <div class='modal modal-session' id='modalMensagem'>
        <div class='modal-content'>
            <a href='#' class='closeIcon'><span class='modal-close close-icon material-symbols-outlined'> close </span></a>
            <span class='icon material-symbols-outlined'> cancel </span>
            <h3>Ops, você já se candidatou!</h3>
            <p>Você já está candidatado a este projeto. Não é possível se candidatar mais de uma vez ao mesmo projeto.</p>
            <div class='btn-wrapper'>
                <a href='#'class='btn small-btn modal-close closeIcon'>Entendi</a>
            </div>
        </div>
    </div>";
    exit(); // encerrando o script após o redirecionamento
}

// inserindo candidatura do aluno ao projeto na tabela "candidaturas"
$query_insert = "INSERT INTO candidatura (data_candidatura, projeto_id, aluno_id) VALUES (NOW(), :projeto_id, :aluno_id)";
$stmt_insert = $conn->prepare($query_insert); // preparando a consulta SQL
$stmt_insert->bindParam(":projeto_id", $projeto_id, PDO::PARAM_INT); // definindo o parâmetro do ID do projeto
$stmt_insert->bindParam(":aluno_id", $aluno_id, PDO::PARAM_INT); // definindo o parâmetro do ID do aluno
$stmt_insert->execute(); // executando a consulta de inserção

// verificando se a candidatura foi inserida com sucesso --> se o contador de linhas for maior que 0 significa que a candidatura foi feita com sucesso
if ($stmt_insert->rowCount() > 0) {
    // definindo mensagem de sucesso
    header("Location: ../../pages/aluno/home.php");
    $_SESSION["mensagem"] = " 
    <!-- Modal de confirmação - Candidatura feita com sucesso! -->
    <div class='modal modal-session' id='modalMensagem'>
        <div class='modal-content' >
            <a href='#' class='closeIcon'><span class='\modal-close close-icon material-symbols-outlined'> close </span></a>
            <span class='icon material-symbols-outlined'> check_circle </span>
            <h3>Candidatura feita com sucesso!</h3>
            <p>Sua candidatura foi enviada com sucesso!
            Se você for selecionado, o anuciante entrará em contato em breve. Fique atento às suas mensagens!</p>
            <div class='btn-wrapper'>
                <a href='#' class='btn small-btn modal-close closeIcon'>Entendi</a>
            </div>
        </div>
    </div>";
    exit(); // encerrando o script após o redirecionamento
} else {
    // definindo mensagem de erro
    header("Location: ../../pages/aluno/home.php");
    $_SESSION["mensagem"] = " 
    <!-- Modal de confirmação - Erro ao se candidatar! -->
    <div class='modal modal-session' id='modalMensagem'>
        <div class='modal-content'>
            <a href='#' class='closeIcon'><span class='\modal-close close-icon material-symbols-outlined'> close </span></a>
            <span class='icon material-symbols-outlined'> cancel </span>
            <h3>Erro ao se candidatar!</h3>
            <p>Ocorreu um erro ao processar sua candidatura. Por favor, tente novamente mais tarde.</p>
            <div class='btn-wrapper'>
                <a href='#' class='btn small-btn modal-close closeIcon'>Entendi</a>
            </div>
        </div>
    </div>";
    exit(); // encerrando o script após o redirecionamento
}
