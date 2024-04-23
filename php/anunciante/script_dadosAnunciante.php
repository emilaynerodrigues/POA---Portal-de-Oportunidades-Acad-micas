<?php
session_start();
include('../conexao.php');
$conn = conectar();

$id_anunciante = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dados_anunciante = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    $query_update_anunciante = "UPDATE anunciante SET nome=:nome, nome_empresa=:nome_empresa, cnpj=:cnpj WHERE id=:id";
    $edit_anunciante = $conn->prepare($query_update_anunciante);

    $edit_anunciante->bindParam(":nome", $dados_anunciante['nome'], PDO::PARAM_STR);
    $edit_anunciante->bindParam(":nome_empresa", $dados_anunciante['nome_empresa'], PDO::PARAM_STR);
    $edit_anunciante->bindParam(":cnpj", $dados_anunciante['cnpj'], PDO::PARAM_STR);

    $edit_anunciante->bindParam(":id", $id_anunciante, PDO::PARAM_INT);

    if ($edit_anunciante->execute()) {
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
        header('Location: ../../pages/anunciante/home.php');
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
        header('Location: ../../pages/anunciante/home.php');
        exit();
    }
} else {
    echo "Nenhum dado recebido para atualização.";
}
