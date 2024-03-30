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
            <a href='../../pages/login.php'><span class='\modal-close close-icon material-symbols-outlined'> close </span></a>
            <span class='icon material-symbols-outlined'> cancel </span>
            <h3>Preencha todos os campos!</h3>
            <p>Ops! Parece que você esqueceu de preencher alguns campos.
            Por favor, preencha todos os campos obrigatórios para fazer login.</p>
            <div class='btn-wrapper'>
                <a href='../../pages/login.php' class='btn small-btn modal-close'>Entendi</a>
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
$queryAnunciante = $conn->prepare("SELECT id FROM anunciante WHERE email = :e and senha = :s");
$queryAnunciante->bindValue(":e", $email);
$queryAnunciante->bindValue(":s", $senha);
$queryAnunciante->execute();
$rowAnunciante = $queryAnunciante->fetch(PDO::FETCH_ASSOC); //obtem (se houver) os dados do anunciante

if ($rowAluno) {
    $_SESSION['user_id'] = $rowAluno['id'];
    $_SESSION['user_type'] = 'aluno'; // Para distinguir entre aluno e anunciante

    //Redirecionando para home do aluno
    header("Location: ../pages/aluno/home.php");
    exit();
} elseif ($rowAnunciante) {
    $_SESSION['user_id'] = $rowAnunciante['id'];
    $_SESSION['user_type'] = 'anunciante'; // Para distinguir entre aluno e anunciante

    //Redirecionando para home do anunciante
    header("Location: ../pages/anunciante/home.php");
    exit();
} else {
    header("Location: ../pages/login.php"); //Redirecionando para página de login
    $_SESSION['mensagem'] =
        "<!-- Modal de confirmação - Erro ao fazer login! -->
    <div class='modal modal-session'>
        <div class='modal-content'>
            <a href='../../pages/login.php'><span class='\modal-close close-icon material-symbols-outlined'> close </span></a>
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
