<?php
// Incluir o arquivo de conexão com o banco de dados
include("../conexao.php");
$conn = conectar();

// Verificar se o ID do anunciante está presente na solicitação
if (isset($_GET['anunciante_id'])) {
    $anunciante_id = $_GET['anunciante_id'];

    // Excluir todas as candidaturas vinculadas aos projetos do anunciante
    $sql_delete_candidaturas = "DELETE FROM candidatura WHERE projeto_id IN (SELECT id FROM projeto WHERE anunciante_id = :anunciante_id)";
    $stmt_delete_candidaturas = $conn->prepare($sql_delete_candidaturas);
    $stmt_delete_candidaturas->bindParam(':anunciante_id', $anunciante_id);
    $stmt_delete_candidaturas->execute();

    // Excluir todos os projetos do anunciante
    $sql_delete_projetos = "DELETE FROM projeto WHERE anunciante_id = :anunciante_id";
    $stmt_delete_projetos = $conn->prepare($sql_delete_projetos);
    $stmt_delete_projetos->bindParam(':anunciante_id', $anunciante_id);
    $stmt_delete_projetos->execute();

    // Finalmente, excluir o anunciante
    $sql_delete_anunciante = "DELETE FROM anunciante WHERE id = :anunciante_id";
    $stmt_delete_anunciante = $conn->prepare($sql_delete_anunciante);
    $stmt_delete_anunciante->bindParam(':anunciante_id', $anunciante_id);
    $stmt_delete_anunciante->execute();

    // Redirecionar para a página de anunciantes após a exclusão
    $_SESSION['mensagem'] =
        "<!-- Modal de confirmação - Dados deletados com sucesso! -->
        <div class='modal modal-session'>
            <div class='modal-content'>
                <a href='../../pages/home.php'><span class='modal-close close-icon material-symbols-outlined'> close </span></a>
                <span class='icon material-symbols-outlined'> check_circle </span>
                <h3>Dados deletados com sucesso!</h3>
                <p>Os dados do anunciante, assim como os projetos e candidaturas vinculados a ele, foram deletados permanentemente da base de dados.</p>
                <div class='btn-wrapper'>
                    <a href='../php/pages/home.php' class='btn small-btn modal-close'>Entendi</a>
                </div>
            </div>
        </div>";
    header('Location: ../../pages/admin/home.php');

    exit();
} else {
    // Se o ID do anunciante não estiver presente na solicitação, redirecionar de volta para a página de anunciantes
    header('Location: ../../pages/admin/home.php');

    exit();
}
