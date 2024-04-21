<?php
session_start();
include('../conexao.php');
$conn = conectar();

$id_aluno = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dados_contato = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    $query_update_contato = "UPDATE aluno SET telefone=:telefone, whatsapp=:whatsapp, linkedin=:linkedin WHERE id=:id";
    $edit_contato = $conn->prepare($query_update_contato);

    $edit_contato->bindParam(":telefone", $dados_contato['telefone'], PDO::PARAM_STR);
    $edit_contato->bindParam(":whatsapp", $dados_contato['whatsapp'], PDO::PARAM_STR);
    $edit_contato->bindParam(":linkedin", $dados_contato['linkedin'], PDO::PARAM_STR);
    $edit_contato->bindParam(":id", $id_aluno, PDO::PARAM_INT);

    if ($edit_contato->execute()) {
        $_SESSION['mensagem'] =
            "<!-- Modal de confirmação - Tudo certo!! -->
                <div class='modal modal-session' id='modalMensagem'>
                    <div class='modal-content'>
                        <a href='#' class='closeIcon'><span class='\modal-close close-icon material-symbols-outlined'> close </span></a>
                        <span class='icon material-symbols-outlined'> check_circle </span>
                        <h3>Tudo certo!</h3>
                        <p> Seus dados foram salvos com sucesso.</p>
                        <div class='btn-wrapper'>
                            <a href='#' class='btn small-btn modal-close closeIcon'>Entendi</a>
                        </div>
                    </div>
                </div>";
        header('Location: ../../pages/aluno/home.php');
        exit();
    } else {
        $_SESSION['mensagem'] =
            "<!-- Modal de confirmação - Ops, algo deu errado! -->
                <div class='modal modal-session' id='modalMensagem'>
                    <div class='modal-content'>
                        <a href='#' class='closeIcon'><span class='\modal-close close-icon material-symbols-outlined'> close </span></a>
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
    echo "Nenhum dado recebido para atualização.";
}
