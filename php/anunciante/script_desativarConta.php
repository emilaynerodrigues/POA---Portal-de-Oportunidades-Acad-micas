<?php
// Iniciar a sessão, se necessário
session_start();

// Incluir o arquivo de conexão com o banco de dados
include('../conexao.php');
$conn = conectar();

// Verificar se o formulário foi enviado via método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar se o checkbox de confirmação foi marcado
    if (isset($_POST["confirmCheckbox"])) {
        // Consultar o ID do anunciante a ser desativado
        $anunciante_id = $_SESSION['user_id'];

        // Desativar a conta do anunciante na tabela de anunciante
        $sql_anunciante = "UPDATE anunciante SET ativo = FALSE WHERE id = :anunciante_id";
        $stmt_anunciante = $conn->prepare($sql_anunciante);
        $stmt_anunciante->bindParam(':anunciante_id', $anunciante_id);

        // Iniciar uma transação para garantir a atomicidade da operação de desativação
        $conn->beginTransaction();

        try {
            // Executar a desativação da conta do anunciante
            $stmt_anunciante->execute();

            // Confirmar a transação se a operação foi bem-sucedida
            $conn->commit();

            header("Location: ../../index.php");
            // Mensagem de conta desativada com sucesso
            $_SESSION['mensagem'] = " 
            <!-- Modal de confirmação - Conta desativada com sucesso -->
            <div class='modal modal-session' id='modalMensagem'>
                <div class='modal-content'>
                    <a href='php/script_logout.php' class='closeIcon'><span class='modal-close close-icon material-symbols-outlined'> close </span></a>
                    <span class='icon material-symbols-outlined'> account_circle_off </span>
                    <h3>Conta desativada com sucesso!</h3>
                    <p>Sua conta foi desativada com sucesso. Você pode reativá-la a qualquer momento, fazendo login novamente.</p>
                    <div class='btn-wrapper'>
                        <a href='php/script_logout.php' class='btn small-btn modal-close closeIcon'>Entendi</a>
                    </div>
                </div>
            </div>";


            exit();
        } catch (Exception $e) {
            $conn->rollBack();
            // Se ocorrer um erro, desfazer a transação e armazenar a mensagem de erro em uma variável de sessão

            $_SESSION["mensagem"] = " 
            <!-- Modal de confirmação - Erro ao desativar a conta! -->
            <div class='modal modal-session' id='modalMensagem'>
                <div class='modal-content'>
                    <a href='#' class='closeIcon'><span class='modal-close close-icon material-symbols-outlined'> close </span></a>
                    <span class='icon material-symbols-outlined'> check_circle </span>
                    <h3>Erro ao desativar conta!</h3>
                    <p>Ocorreu um erro ao desativar a conta. Por favor, tente novamente mais tarde.</p>
                    <div class='btn-wrapper'>
                        <a href='#' class='btn small-btn modal-close closeIcon'>Entendi</a>
                    </div>
                </div>
            </div>";
        }

        // Redirecionar para a página de origem
        header('Location: ../../pages/anunciante/home.php');
        exit();
    } else {
        // Se o anunciante não marcar o checkbox de confirmação, exibir uma mensagem de erro
        echo "<script>alert('Por favor, marque a caixa de confirmação para desativar sua conta.');</script>";
        exit(); // Encerrar o script para evitar qualquer saída adicional
    }
} else {
    // Se o formulário não foi enviado via método POST, exibir uma mensagem de erro
    echo "<script>alert('Envie o formulário para desativar sua conta.');</script>";
    exit(); // Encerrar o script para evitar qualquer saída adicional
}
