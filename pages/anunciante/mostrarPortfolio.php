<?php
// iniciando sessão
session_start();
ob_start(); //limpando buffer

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

//fazendo conexão com o banco de dados
include("../../php/conexao.php");
$conn = conectar();

//recebendo o id do candidato através da URL, utilizando o método GET
$id_aluno = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

//caso a variável "id" não esteja vazia, pesquisar pelo candidato no banco de dados.
$query_candidato = "SELECT id, nome, cpf, email, matricula, escolaridade, descricao, curso_qualificacao, linkedin FROM aluno WHERE id=:id_aluno LIMIT 1";

//preparando a query
$result_candidato = $conn->prepare($query_candidato);
$result_candidato->bindParam(":id_aluno", $id_aluno, PDO::PARAM_INT);

//executando a consulta
$result_candidato->execute();

//verificar se encontrou o candidato no banco
if ($result_candidato->rowCount() != 1) {
    // candidato não encontrado, redirecionar para página inicial do anunciante
    header("Location: ../../pages/anunciante/home.php");
    exit; // encerrando o script para evitar que o restante seja executado
}

//armazenando os dados em um Array Associativo
$row_candidato = $result_candidato->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfólio - <?php echo $row_candidato['nome'];?></title>
</head>

<body>

    <div class="">
        <?php echo $row_candidato['nome']; ?>
        <br>
        <?php echo $row_candidato['cpf']; ?>
        <br>
        <?php echo $row_candidato['email']; ?>
        <br>
        <?php echo $row_candidato['matricula']; ?>
        <br>
        <?php echo $row_candidato['escolaridade']; ?>

    </div>
</body>

</html>