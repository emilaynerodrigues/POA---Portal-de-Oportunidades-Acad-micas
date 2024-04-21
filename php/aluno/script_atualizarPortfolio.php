<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('../conexao.php');
$conn = conectar();

// ID do usuário logado
$id_aluno = $_SESSION['user_id'];

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao'])) {
    // Verificar a ação solicitada
    $acao = $_POST['acao'];

    switch ($acao) {
        case 'atualizar_descricao':
            if (isset($_POST['descricao'])) {
                $descricao = $_POST['descricao'];
                // Atualizar a descrição do aluno no banco de dados
                $sql = "UPDATE aluno SET descricao = :descricao WHERE id = :id_aluno";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':descricao', $descricao);
                $stmt->bindParam(':id_aluno', $id_aluno);
                if ($stmt->execute()) {
                    // Defina a mensagem de sucesso e redirecione para a página inicial
                    $_SESSION['mensagem'] = "Descrição atualizada com sucesso.";
                    header('Location: ../../pages/aluno/home.php');
                    exit();
                } else {
                    // Defina a mensagem de erro e redirecione para a página inicial
                    $_SESSION['mensagem'] = "Erro ao atualizar a descrição. Tente novamente mais tarde.";
                    header('Location: ../../pages/aluno/home.php');
                    exit();
                }
            }
            break;
        case 'atualizar_escolaridade':
            if (isset($_POST['escolaridade'])) {
                $escolaridade = $_POST['escolaridade'];
                echo "Escolaridade recebida: " . $escolaridade; // Adiciona uma mensagem de depuração
                // Atualizar a escolaridade do aluno no banco de dados
                $sql = "UPDATE aluno SET escolaridade = :escolaridade WHERE id = :id_aluno";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':escolaridade', $escolaridade);
                $stmt->bindParam(':id_aluno', $id_aluno);
                if ($stmt->execute()) {
                    // Defina a mensagem de sucesso e redirecione para a página inicial
                    $_SESSION['mensagem'] = "Escolaridade atualizada com sucesso.";
                    header('Location: ../../pages/aluno/home.php');
                    exit();
                } else {
                    // Defina a mensagem de erro e redirecione para a página inicial
                    $_SESSION['mensagem'] = "Erro ao atualizar a escolaridade. Tente novamente mais tarde.";
                    header('Location: ../../pages/aluno/home.php');
                    exit();
                }
            } else {
                echo "Escolaridade não recebida."; // Adicionando mensagem de erro
            }
            break;


        default:
            // Se nenhuma ação válida for especificada, redirecione para a página inicial
            header('Location: ../../pages/aluno/home.php');
            exit();
    }
}
