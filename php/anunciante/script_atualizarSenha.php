<?php
session_start();
include('../conexao.php');
$conn = conectar();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];
    $email = isset($_POST["email"]) ? $_POST["email"] : null;
    $senha_atual = isset($_POST["senha_atual"]) ? $_POST["senha_atual"] : null;
    $nova_senha = isset($_POST["nova_senha"]) ? $_POST["nova_senha"] : null;

    if (empty($email) || empty($senha_atual) || empty($nova_senha)) {
        $_SESSION['mensagem'] =
            "<!-- Modal de confirmação - Campos vazios! -->
            <div class='modal modal-session' id='modalMensagem'>
                <div class='modal-content'>
                    <a href='#' class='closeIcon'><span class='\modal-close close-icon material-symbols-outlined'> close </span></a>
                    <span class='icon material-symbols-outlined'> cancel </span>
                    <h3>Campos vazios!</h3>
                    <p>Preencha todos os campos e tente novamente.</p>
                    <div class='btn-wrapper'>
                        <a href='#' class='btn small-btn modal-close closeIcon'>Entendi</a>
                    </div>
                </div>
            </div>";
        header('Location: ../../pages/anunciante/home.php');
        exit();
    }

    if (senha_atual_correta($conn, $user_id, $senha_atual)) {
        if ($nova_senha === $senha_atual) {
            $_SESSION['mensagem'] =
                "<!-- Modal de confirmação - Senhas iguais! -->
            <div class='modal modal-session' id='modalMensagem'>
                <div class='modal-content'>
                    <a href='#' class='closeIcon'><span class='\modal-close close-icon material-symbols-outlined'> close </span></a>
                    <span class='icon material-symbols-outlined'> cancel </span>
                    <h3>Senhas iguais!</h3>
                    <p>A nova senha não pode ser igual a senha atual.</p>
                    <div class='btn-wrapper'>
                        <a href='#' class='btn small-btn modal-close closeIcon'>Entendi</a>
                    </div>
                </div>
            </div>";
            header('Location: ../../pages/anunciante/home.php');
            exit();
        }

        $nova_senhaCriptografada = md5($nova_senha);

        $queryUpdate = "UPDATE anunciante SET senha = :nova_senha WHERE id = :id";
        $stmt = $conn->prepare($queryUpdate);
        $stmt->bindParam(':nova_senha', $nova_senhaCriptografada);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
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
            $_SESSION['mensagem'] =
                "<!-- Modal de confirmação -Erro ao atualizar sua senha! -->
                <div class='modal modal-session' id='modalMensagem>
                    <div class='modal-content'>
                        <a href='#'><span class='modal-close close-icon material-symbols-outlined closeIcon'> close </span></a>
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
        $_SESSION['mensagem'] =
            "<!-- Modal de confirmação - Senha atual incorreta! -->
            <div class='modal modal-session' id='modalMensagem'>
                <div class='modal-content'>
                    <a href='#' class='closeIcon'><span class='\modal-close close-icon material-symbols-outlined'> close </span></a>
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

function senha_atual_correta($conn, $user_id, $senha_atual)
{
    $query = "SELECT senha FROM anunciante WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $senha_atual_md5 = md5($senha_atual);
        if ($senha_atual_md5 === $row['senha']) {
            return true;
        }
    }

    return false;
}
