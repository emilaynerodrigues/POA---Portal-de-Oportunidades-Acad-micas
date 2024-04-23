<?php
session_start();
include('../conexao.php');
$conn = conectar();

// ID do usuário logado
$id_aluno = $_SESSION['user_id'];

// verificando se o formulário foi enviado e se o curso de qualificação foi recebido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['curso_qualificacao'])) {
    $curso_qualificacao = $_POST['curso_qualificacao'];

    // atualizando o curso de qualificação do aluno no banco de dados
    $sql = "UPDATE aluno SET curso_qualificacao = :curso_qualificacao WHERE id = :id_aluno";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':curso_qualificacao', $curso_qualificacao);
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
                    <p> Seus cursos de qualificação foram salvos com sucesso.</p>
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
    // se os cursos de qualificação não forem recebidos, redirecione com mensagem de erro
    $_SESSION['mensagem'] = "Erro: Cursos de qualificação não recebidos.";
    header('Location: ../../pages/aluno/home.php');
    exit();
}
