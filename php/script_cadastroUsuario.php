<?php
// 1 - INICIALIZANDO AS SESSÕES
session_start();

// 2 - CRIANDO CONEXÃO COM O BANCO DE DADOS
include("conexao.php");
$conn = conectar();

// 3 - RECUPERANDO DADOS DO FORMULÁRIO ATRAVÉS DO MÉTODO POST
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = md5($_POST['senha']);
$tipo_usuario = $_POST['tipo_usuario'];
$matricula = $_POST['matricula'];

// 4 - VERIFICANDO O TIPO DE USUÁRIO E PREPARANDO A CONSULTA SQL
if ($tipo_usuario == "aluno") {
    $cadastro = $conn->prepare("INSERT INTO aluno(nome, email, senha, matricula) VALUES(:nome, :email, :senha, :matricula)");
    $cadastro->bindValue(':matricula', $matricula);
} elseif ($tipo_usuario == "anunciante") {
    $cadastro = $conn->prepare("INSERT INTO anunciante(nome, email, senha) VALUES(:nome, :email, :senha)");
} else {
    echo "Tipo de usuário inválido.";
    exit();
}

// PASSANDO OS DADOS DAS VARIÁVEIS PARA OS PSEUDO-NOMES ATRAVÉS DO MÉTODO bindValue
$cadastro->bindValue(":nome", $nome);
$cadastro->bindValue(":email", $email);
$cadastro->bindValue(":senha", $senha);

// VERIFICANDO SE JÁ EXISTE UM E-MAIL CADASTRADO
$verificarAluno = $conn->prepare("SELECT * FROM aluno WHERE email=?");
$verificarAluno->execute(array($email));

$verificarAnunciante = $conn->prepare("SELECT * FROM anunciante WHERE email=?");
$verificarAnunciante->execute(array($email));

if ($verificarAluno->rowCount() == 0 && $verificarAnunciante->rowCount() == 0) {
    $cadastro->execute();
    header('Location: ../pages/cadastrar.php');
    $_SESSION['mensagem'] =
        "<!-- Modal de confirmação - Usuário cadastrado com sucesso! -->
    <div class='modal modal-session'>
        <div class='modal-content'>
            <a href='../../pages/cadastrar.php'><span class='\modal-close close-icon material-symbols-outlined'> close </span></a>
            <span class='icon material-symbols-outlined'> check_circle </span>
            <h3>Usuário cadastrado com sucesso!</h3>
            <p>Seu cadastro foi realizado com suceFFsso! Clique no botão abaixo para fazer login e acessar sua conta.</p>
            <div class='btn-wrapper'>
                <a href='../../pages/login.php' class='btn small-btn modal-close'>Fazer Login</a>
            </div>
        </div>
    </div>";
    exit();
} else {
    header('Location: ../pages/cadastrar.php');
    $_SESSION['mensagem'] =
        "<!-- Modal de confirmação - E-mail já cadastrado! -->
    <div class='modal modal-session'>
        <div class='modal-content'>
            <a href='../../pages/cadastrar.php'><span class='\modal-close close-icon material-symbols-outlined'> close </span></a>
            <span class='icon material-symbols-outlined'> cancel </span>
            <h3>E-mail já cadastrado!</h3>
            <p>O e-mail que você inseriu já está sendo utilizado por outro usuário. Tente novamente!</p>
            <div class='btn-wrapper'>
                <a href='../../pages/cadastrar.php' class='btn small-btn modal-close'>Entendi</a>
            </div>
        </div>
    </div>";
    exit();
}


