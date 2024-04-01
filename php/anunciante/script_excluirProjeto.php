<?php
session_start();
ob_start();

//1 - conectando com banco de dados
include("../conexao.php");
$conn = conectar();

//2 - recebendo id do projeto que deseja exluir atraves da URL --> pelo modal do projeto na home
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

//3 - verificando se a variavel id não está vazia (empty)
if (empty($id)) {
    $_SESSION['msg'] = "<p style = 'color#f00; text-align: center;'>Erro: Projeto não encontrado</p>";
    header("Location: ../../pages/anunciante/home.php");
}

//4- procurando projeto selecionado no banco de dados
$projeto = "SELECT id FROM projeto WHERE id=$id LIMIT 1";

//5 - preparando a query
$result_projeto = $conn->prepare($projeto);

//6 - executando a query
$result_projeto->execute();

// Verificar se encontrou algum registro na consulta
if ($result_projeto->rowCount() != 0) {
    // Consultar se existem candidaturas associadas ao projeto
    $sql_verificar_candidaturas = "SELECT COUNT(*) AS total_candidaturas FROM candidatura WHERE projeto_id = :projeto_id";
    $stmt_verificar_candidaturas = $conn->prepare($sql_verificar_candidaturas);
    $stmt_verificar_candidaturas->bindParam(':projeto_id', $id);
    $stmt_verificar_candidaturas->execute();
    $row = $stmt_verificar_candidaturas->fetch(PDO::FETCH_ASSOC);

    // Verificar se há candidaturas associadas ao projeto
    if ($row['total_candidaturas'] > 0) {
        // Se houver candidaturas, redirecionar de volta com mensagem de erro
        header("Location: ../../pages/anunciante/home.php");
        $_SESSION["projeto-excluido"] = "
            <!-- Modal de confirmação - Projeto não pode ser excluído! -->
            <div class='modal modal-session' id='modalMensagem'>
                <div class='modal-content'>
                    <a href='#' class='closeIcon'><span class='modal-close close-icon material-symbols-outlined'> close </span></a>
                    <span class='icon material-symbols-outlined'> check_circle </span>
                    <h3>Projeto não pode ser excluído!</h3>
                    <p>Este projeto não pode ser excluído porque existem candidaturas de alunos associadas a ele.</p>
                    <div class='btn-wrapper'>
                        <a href='#' class='btn small-btn modal-close closeIcon'>Entendi</a>
                    </div>
                </div>
            </div>";
        exit();
    } else {
        // Se não houver candidaturas, proceder com a exclusão do projeto como antes
        $query_delete_projeto = "DELETE FROM projeto WHERE id=$id";

        $result_delete_projeto = $conn->prepare($query_delete_projeto);

        if ($result_delete_projeto->execute()) {
            header("Location: ../../pages/anunciante/home.php");
            $_SESSION["projeto-excluido"] = "
            <!-- Modal de confirmação - Projeto excluído! -->
            <div class='modal modal-session' id='modalMensagem'>
                <div class='modal-content'>
                    <a href='#' class='closeIcon'><span class='\modal-close close-icon material-symbols-outlined'> close </span></a>
                    <span class='icon material-symbols-outlined'> check_circle </span>
                    <h3>Projeto excluído com sucesso!</h3>
                    <p>O projeto foi removido permanentemente do sistema</p>
                    <div class='btn-wrapper'>
                        <a href='#' class='btn small-btn modal-close closeIcon'>Entendi</a>
                    </div>
                </div>
            </div>";
            exit();
        } else {
            header("Location: ../../pages/anunciante/home.php");
            $_SESSION["projeto-excluido"] = "
                <!-- Modal de confirmação - Projeto não excluído! -->
                <div class='modal modal-session' id='modalMensagem'>
                    <div class='modal-content'>
                        <a href='#' class='closeIcon'><span class='\modal-close close-icon material-symbols-outlined'> close </span></a>
                        <span class='icon material-symbols-outlined'> cancel </span>
                        <h3>Projeto não excluído!</h3>
                        <p>Não foi possível concluir a exclusão do seu projeto. Tente novamente mais tarde!</p>
                        <div class='btn-wrapper'>
                            <a href='#' class='btn small-btn modal-close closeIcon'>Entendi</a>
                        </div>
                    </div>
                </div>";
            exit();
        }
    }
} else {
    $_SESSION["projeto-excluido"] = " 
    <!-- Modal de confirmação - Projeto não encontrado! -->
    <div class='modal modal-session' id='modalMensagem'>
        <div class='modal-content'>
            <a href='#' class='closeIcon'><span class='\modal-close close-icon material-symbols-outlined'> close </span></a>
            <span class='icon material-symbols-outlined'> cancel </span>
            <h3>Projeto não encontrado!</h3>
            <p>Não foi possível encontrar seu projeto em nossa base de dados. Tente novamente mais tarde!</p>
            <div class='btn-wrapper'>
                <a href='#' class='btn small-btn modal-close closeIcon'>Entendi</a>
            </div>
        </div>
    </div>";
    exit();
}
