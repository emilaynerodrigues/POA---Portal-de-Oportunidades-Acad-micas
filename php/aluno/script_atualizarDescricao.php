<?php
session_start();
include('../conexao.php');
$conn = conectar();

// ID do usuário logado
$id_aluno = $_SESSION['user_id'];

// verificando se o formulário foi enviado e se a descrição foi recebida
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['descricao'])) {
    $descricao = $_POST['descricao'];

    // atualizando a descrição do aluno no banco de dados
    $sql = "UPDATE aluno SET descricao = :descricao WHERE id = :id_aluno";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':id_aluno', $id_aluno);

    if ($stmt->execute()) {
        // definindo a mensagem de sucesso e redirecione para a página inicial
        $_SESSION['mensagem'] =
            "<!-- Modal de confirmação - Tudo certo!! -->
            <div class='modal modal-session' id='modalMensagem'>
                <div class='modal-content'>
                    <a href='#' class='closeIcon'><span class='modal-close close-icon material-symbols-outlined'> close </span></a>
                    <span class='icon material-symbols-outlined'> check_circle </span>
                    <h3>Tudo certo!</h3>
                    <p> Sua descrição foi salvos com sucesso.</p>
                    <div class='btn-wrapper'>
                        <a href='#' class='btn small-btn modal-close closeIcon'>Entendi</a>
                    </div>
                </div>
            </div>";
        header('Location: ../../pages/aluno/home.php');
        exit();
    } else {
        // definindo a mensagem de erro e redirecione para a página inicial
        $_SESSION['mensagem'] =
            "<!-- Modal de confirmação - Ops, algo deu errado! -->
            <div class='modal modal-session' id='modalMensagem'>
                <div class='modal-content'>
                    <a href='#' class='closeIcon'><span class='modal-close close-icon material-symbols-outlined'> close </span></a>
                    <span class='icon material-symbols-outlined'> cancel </span>
                    <h3>Ops, algo deu errado!</h3>
                    <p> Algo deu errado ao atualizar seus dados. Tente novamente mais tarde.</p>
                    <div class='btn-wrapper'>
                        <a href='#' class='btn small-btn modal-close closeIcon'>Entendi</a>
                    </div>
                </div>
            </div>";
        header('Location: ../../pages/aluno/home.php');
        exit();
    }
} else {
    // se a descrição não for recebida, redirecione com mensagem de erro
    $_SESSION['mensagem'] = "Erro: Descrição não recebida.";
    header('Location: ../../pages/aluno/home.php');
    exit();
}
