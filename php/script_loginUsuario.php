<?php
// iniciando sessão
session_start();

//1 - CONEXÃO COM O BANCO
include("conexao.php");
$conn = conectar();

//2 - RECUPERANDO OS DADOS NO BANCO DE DADOS
$email = $_POST['email'];
$senha = md5($_POST['senha']);

//3 - VERIFICAR SE OS CAMPOS ESTÃO VAZIOS
if (empty($_POST["email"]) || empty($_POST["senha"])) {
    header("Location: ../pages/login.php"); //Redirecionando para página de login
    $_SESSION['mensagem'] =
        "<!-- Modal de confirmação - Preencha todos os dados! -->
    <div class='modal modal-session'>
        <div class='modal-content'>
            <span class='modal-close close-icon material-symbols-outlined closeIcon'> close </span>
            <span class='icon material-symbols-outlined'> cancel </span>
            <h3>Preencha todos os campos!</h3>
            <p>Ops! Parece que você esqueceu de preencher alguns campos.
            Por favor, preencha todos os campos obrigatórios para fazer login.</p>
            <div class='btn-wrapper'>
                <a href='#' class='btn small-btn modal-close closeIcon'>Entendi</a>
            </div>
        </div>
    </div>";
    exit();
}

//4 - CONSULTAR (Query) DADOS NO BANCO DE DADOS PARA VALIDAR OS DADOS DO USUÁRIO
// Consulta para verificar se as credenciais são válidas para um aluno
$queryAluno = $conn->prepare("SELECT id FROM aluno WHERE email = :e and senha = :s");
$queryAluno->bindValue(":e", $email);
$queryAluno->bindValue(":s", $senha);
$queryAluno->execute();
$rowAluno = $queryAluno->fetch(PDO::FETCH_ASSOC); //obtem (se houver) os dados do aluno

// Consulta para verificar se as credenciais são válidas para um anunciante
$queryAnunciante = $conn->prepare("SELECT id, ativo FROM anunciante WHERE email = :e and senha = :s");
$queryAnunciante->bindValue(":e", $email);
$queryAnunciante->bindValue(":s", $senha);
$queryAnunciante->execute();
$rowAnunciante = $queryAnunciante->fetch(PDO::FETCH_ASSOC); //obtem (se houver) os dados do anunciante

// Consulta para verificar se as credenciais são válidas para um administrador
$queryAdmin = $conn->prepare("SELECT id FROM administrador WHERE email = :e AND senha = :s");
$queryAdmin->bindValue(":e", $email);
$queryAdmin->bindValue(":s", $senha);
$queryAdmin->execute();
$rowAdmin = $queryAdmin->fetch(PDO::FETCH_ASSOC); //obtem (se houver) os dados do administrador

if ($rowAluno) {
    $_SESSION['user_id'] = $rowAluno['id'];
    $_SESSION['user_type'] = 'aluno'; // Para distinguir entre aluno e anunciante

    //Redirecionando para home do aluno
    header("Location: ../pages/aluno/home.php");
    exit();
} elseif ($rowAnunciante) {
    $_SESSION['user_id'] = $rowAnunciante['id'];
    $_SESSION['user_type'] = 'anunciante'; // Para distinguir entre aluno e anunciante

    // Verificar se a conta do anunciante está desativada
    if (!$rowAnunciante['ativo']) {
        // Se a conta estiver desativada, mostrar a mensagem para reativar a conta

        header("Location: ../../pages/login.php");
        $_SESSION['reativar_conta'] =
            "<!-- Modal de confirmação - Deseja reativar sua conta? -->
        <div class='modal modal-session'>
            <div class='modal-content'>
                <a href='../../pages/login.php'><span class='modal-close close-icon material-symbols-outlined'> close </span></a>
                <span class='icon material-symbols-outlined'> help </span>
                <h3>Deseja reativar sua conta?</h3>
                <p>Consultamos nosso sistema e parece que sua conta está desativada. Deseja reativa-lá?</p>
                <div class='btn-wrapper'>
                    <a href='../../pages/login.php' class='btn small-btn cancel-btn'>Cancelar</a>
                    <a href='../../php/anunciante/script_reativarConta.php' class='btn small-btn modal-close'>Sim, reativar</a>
                </div>
            </div>
        </div>";
    } else {
        // Se a conta estiver ativa, redirecione para home do anunciante
        header("Location: ../pages/anunciante/home.php");
        exit();
    }
} elseif ($rowAdmin) {
    $_SESSION['user_id'] = $rowAdmin['id'];
    $_SESSION['user_type'] = 'admin';

    //Redirecionando para home do administrador
    header("Location: ../pages/admin/home.php");
    exit();
} else {
    // Se não encontrou um anunciante com as credenciais fornecidas, redirecionar para página de login
    header("Location: ../pages/login.php"); //Redirecionando para página de login
    $_SESSION['mensagem'] =
        "<!-- Modal de confirmação - Erro ao fazer login! -->
    <div class='modal modal-session'>
        <div class='modal-content'>
            <a href='../../pages/login.php'><span class='modal-close close-icon material-symbols-outlined'> close </span></a>
            <span class='icon material-symbols-outlined'> cancel </span>
            <h3>Erro ao fazer login!</h3>
            <p>Ops! Algo deu errado ao tentar fazer login. Verifique se: </p>
            <ul style='text-align: justify; font-size: 14px; padding-left: 15px;'>
                <li>Todos os campos foram preenchidos corretamente.</li>
                <li>Você digitou o e-mail e senha corretos.</li>
            </ul>
            <p>Por favor, tente novamente.</p>
            <div class='btn-wrapper'>
                <a href='../../pages/login.php' class='btn small-btn modal-close'>Entendi</a>
            </div>
        </div>
    </div>";
    exit();
}
