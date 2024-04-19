<?php
// iniciando sessão, fazer a conexão com o banco de dados e verificar se o usuário está autenticado, se necessário
session_start();

include('../conexao.php');
$conn = conectar();

// ID do usuário logado
$user_id = $_SESSION['user_id'];

// Verificar se os dados do formulário foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // recuperando os dados do formulário
    $email = $_POST["email"];
    $senha_atual = $_POST["senha_atual"]; //pega a senha do formulario
    $nova_senha = $_POST["nova_senha"];

    // Verificar se a senha atual está correta
    if (senha_atual_correta($conn, $user_id, $senha_atual)) {
        // criptografando a nova senha usando MD5
        $nova_senhaCriptografada = md5($nova_senha);

        // atualizando a senha no banco de dados
        $queryUpdate = "UPDATE anunciante SET senha = :nova_senha WHERE id = :id";
        $stmt = $conn->prepare($queryUpdate);
        $stmt->bindParam(':nova_senha', $nova_senhaCriptografada);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();

        unset($_SESSION['mensagem']); // limpando o buffer da sessão mensagem

        // verificando se a atualização da senha foi bem-sucedida
        if ($stmt->rowCount() > 0) {
            // Senha atualizada com sucesso
            $_SESSION['mensagem'] =
                "<!-- Modal de confirmação - Senha atualizada com sucesso! -->
            <div class='modal modal-session' id='modalMensagem'>
            <div class='modal-content'>
            <a href='#' class='closeIcon'><span class='\modal-close close-icon material-symbols-outlined'> close </span></a>
            <span class='icon material-symbols-outlined'> check_circle </span>
            <h3>Senha alterada com sucesso!</h3>
            <p> Sua senha foi alterada com sucesso. Agora você pode utilizar a nova senha para acessar sua conta com total segurança.</p>
            <div class='btn-wrapper'>
            <a href='#' class='btn small-btn modal-close closeIcon'>Entendi</a>
            </div>
            </div>
            </div>";
            header('Location: ../../pages/anunciante/home.php');
            exit();
        } else {
            // Ocorreu um erro ao atualizar a senha
            $_SESSION['mensagem'] =
                "<!-- Modal de confirmação -Erro ao atualizar sua senha! -->
           <div class='modal modal-session' id='modalMensagem'>
            <div class='modal-content'>
            <a href='#' class='closeIcon'><span class='modal-close close-icon material-symbols-outlined'> close </span></a>
           <span class='icon material-symbols-outlined'> cancel </span>
           <h3>Erro ao atualizar sua senha!</h3>
           <p> Desculpe, ocorreu um erro ao tentar alterar sua senha. Por favor, tente novamente mais tarde.</p>
           <div class='btn-wrapper'>
           <a href='#' class='btn small-btn modal-close closeIcon'>Entendi</a>
           </div>
           </div>
           </div>";
            header('Location: ../../pages/anunciante/home.php');
            exit();
        }
    } else {
        // A senha atual fornecida está incorreta
        $_SESSION['mensagem'] =
            "<!-- Modal de confirmação - Senha atual incorreta! -->
           <div class='modal modal-session'>
            <div class='modal-content' id='modalMensagem'>
            <a href='#' class='closeIcon'><span class='modal-close close-icon material-symbols-outlined'> close </span></a>
           <span class='icon material-symbols-outlined'> cancel </span>
           <h3>Senha atual incorreta!</h3>
           <p> Ops! Parece que a senha atual fornecida está incorreta. Por favor, verifique se digitou corretamente sua senha atual e tente novamente.</p>
           <div class='btn-wrapper'>
           <a href='#' class='btn small-btn modal-close closeIcon'>Entendi</a>
           </div>
           </div>
           </div>";
        header('Location: ../../pages/anunciante/home.php');
        exit();
    }
}

// Função para verificar se a senha atual fornecida está correta
function senha_atual_correta($conn, $user_id, $senha_atual)
{
    // Consultar o banco de dados para obter a senha criptografada associada ao usuário na tabela "anunciante"
    $query = "SELECT senha FROM anunciante WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Criptografar a senha atual fornecida pelo usuário usando MD5
    $senha_atual_md5 = md5($senha_atual);

    // Verificar se a senha atual fornecida corresponde à senha criptografada na tabela "anunciante" no banco de dados
    if ($row && $senha_atual_md5 === $row['senha']) {
        return true; // Senha atual está correta
    } else {
        return false; // Senha atual está incorreta
    }
}
