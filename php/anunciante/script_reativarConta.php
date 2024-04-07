<?php
// Iniciar a sessão, se necessário
session_start();

// Incluir o arquivo de conexão com o banco de dados
include("../conexao.php");
$conn = conectar();

// Verificar se o usuário está logado e é um anunciante
if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'anunciante') {
    // Atualizar o status da conta do anunciante para ativo
    $anunciante_id = $_SESSION['user_id'];
    $sql = "UPDATE anunciante SET ativo = TRUE WHERE id = :anunciante_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':anunciante_id', $anunciante_id);

    if ($stmt->execute()) {
        // Redirecionar de volta para a página home do anunciante
        header("Location: ../../pages/anunciante/home.php");
        $_SESSION['mensagem'] =
            "<!-- Modal de confirmação - Conta reativada com sucesso! -->
        <div id='modalMensagem' class='modal modal-session'>
            <div class='modal-content'>
                <span class='modal-close close-icon material-symbols-outlined closeIcon'> close </span>
                <span class='icon material-symbols-outlined'> check_circle </span>
                <h3>Conta reativada com sucesso!</h3>
                <p>Sua conta foi reativada com sucesso! Você pode utilizar todos as funcionalidades do sistema.</p>
                <div class='btn-wrapper'>
                    <a href='#' class='btn small-btn modal-close closeIcon'>Entendi</a>
                </div>
            </div>
        </div>";

        exit();
    } else {
        // Se ocorrer um erro ao reativar a conta, exibir mensagem de erro
        $_SESSION['mensagem'] = "<div class='alert alert-danger' role='alert'>Erro ao reativar a conta. Por favor, tente novamente mais tarde.</div>";
        header("Location: ../pages/anunciante/home.php");
        exit();
    }
} else {
    // Se o usuário não estiver autenticado como anunciante, redirecionar para a página de login
    header("Location: ../pages/login.php");
    exit();
}
