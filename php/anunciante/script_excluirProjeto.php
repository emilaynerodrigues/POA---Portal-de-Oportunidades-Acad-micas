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

//7 - verificar se encontrou algum registro na consulta
if ($result_projeto->rowCount() != 0) { //se for diferente de 0
    //excluindo registro de projeto
    $query_delete_projeto = "DELETE FROM projeto WHERE id=$id";

    //preparando exclusão
    $result_delete_projeto = $conn->prepare($query_delete_projeto);

    //executando o delete
    if ($result_delete_projeto->execute()) {
        header("Location: ../../pages/anunciante/home.php");
        $_SESSION["projeto-excluido"] = " 
        <!-- Modal de confirmação - Projeto excluído! -->
        <div class='modal modal-session'>
            <div class='modal-content'>
                <a href='../../pages/anunciante/home.php'><span class='\modal-close close-icon material-symbols-outlined'> close </span></a>
                <span class='icon material-symbols-outlined'> check_circle </span>
                <h3>Projeto excluído com sucesso!</h3>
                <p>O projeto foi removido permanentemente do sistema</p>
                <div class='btn-wrapper'>
                    <a href='../../pages/anunciante/home.php' class='btn small-btn modal-close'>Entendi</a>
                </div>
            </div>
        </div>";
        exit();
    } else {
        header("Location: ../../pages/anunciante/home.php");
        $_SESSION["projeto-excluido"] = " 
        <!-- Modal de confirmação - Projeto não excluído! -->
        <div class='modal modal-session'>
            <div class='modal-content'>
                <a href='../../pages/anunciante/home.php'><span class='\modal-close close-icon material-symbols-outlined'> close </span></a>
                <span class='icon material-symbols-outlined'> cancel </span>
                <h3>Projeto não excluído!</h3>
                <p>Não foi possível concluir a exclusão do seu projeto. Tente novamente mais tarde!</p>
                <div class='btn-wrapper'>
                    <a href='../../pages/anunciante/home.php' class='btn small-btn modal-close'>Entendi</a>
                </div>
            </div>
        </div>";
        exit();
    }
} else {
    $_SESSION["projeto-excluido"] = " 
    <!-- Modal de confirmação - Projeto não encontrado! -->
    <div class='modal modal-session'>
        <div class='modal-content'>
            <a href='../../pages/anunciante/home.php'><span class='\modal-close close-icon material-symbols-outlined'> close </span></a>
            <span class='icon material-symbols-outlined'> cancel </span>
            <h3>Projeto não encontrado!</h3>
            <p>Não foi possível encontrar seu projeto em nossa base de dados. Tente novamente mais tarde!</p>
            <div class='btn-wrapper'>
                <a href='../../pages/anunciante/home.php' class='btn small-btn modal-close'>Entendi</a>
            </div>
        </div>
    </div>";
}

//script de excluir pode ser testado
