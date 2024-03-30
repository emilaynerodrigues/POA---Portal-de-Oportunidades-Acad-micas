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
        // Consultar o ID do anunciante a ser excluído
        $anunciante_id = $_SESSION['user_id'];

        // Excluir os projetos associados ao anunciante
        $sql_projeto = "DELETE FROM projeto WHERE anunciante_id = :anunciante_id";
        $stmt_projeto = $conn->prepare($sql_projeto);
        $stmt_projeto->bindParam(':anunciante_id', $anunciante_id);

        // Excluir a conta do anunciante da tabela de anunciante
        $sql_anunciante = "DELETE FROM anunciante WHERE id = :anunciante_id";
        $stmt_anunciante = $conn->prepare($sql_anunciante);
        $stmt_anunciante->bindParam(':anunciante_id', $anunciante_id);

        // Iniciar uma transação para garantir a atomicidade das operações de exclusão
        $conn->beginTransaction();

        try {
            // Executar a exclusão dos projetos associados
            $stmt_projeto->execute();

            // Executar a exclusão do anunciante
            $stmt_anunciante->execute();

            // Confirmar a transação se todas as operações foram bem-sucedidas
            $conn->commit();

            // Se a exclusão for bem-sucedida, você pode redirecionar o usuário para uma página de confirmação ou fazer outras ações
            // Por exemplo, redirecionar para uma página de confirmação:
            // header("Location: confirmacao_exclusao_conta.php");
            echo "<script>alert('Conta de anunciante e projetos associados excluídos com sucesso!');</script>";
            exit(); // Encerrar o script para evitar qualquer saída adicional
        } catch (Exception $e) {
            // Se ocorrer um erro, desfazer a transação e exibir uma mensagem de erro
            $conn->rollBack();
            echo "<script>alert('Erro ao excluir conta de anunciante e projetos associados.');</script>";
            exit(); // Encerrar o script para evitar qualquer saída adicional
        }
    } else {
        // Se o usuário não marcar o checkbox de confirmação, exibir uma mensagem de erro
        echo "<script>alert('Por favor, marque a caixa de confirmação para excluir sua conta de anunciante e projetos associados.');</script>";
        exit(); // Encerrar o script para evitar qualquer saída adicional
    }
} else {
    // Se o formulário não foi enviado via método POST, exibir uma mensagem de erro
    echo "<script>alert('Envie o formulário para excluir sua conta de anunciante e projetos associados.');</script>";
    exit(); // Encerrar o script para evitar qualquer saída adicional
}
