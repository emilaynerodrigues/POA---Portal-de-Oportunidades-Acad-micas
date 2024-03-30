<?php
// iniciando sessão
session_start();

// Verificando se o usuário está logado como anunciante
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Anunciante') {
    // Se não estiver logado como anunciante, redirecione para a página de login
    header('Location: ../../index.php');
    $_SESSION['mensagem'] =
        "<!-- Modal de confirmação - Acesso não autorizado! -->
    <div class='modal modal-session'>
      <div class='modal-content'>
          <span class='icon material-symbols-outlined'> cancel </span>
          <h3>Acesso não autorizado!</h3>
          <p>Você não possui autorização para acessar essa parte do sistema. Por favor, volte a página de login e entre com seus dados.</p>
          <div class='btn-wrapper'>
              <a href='../../pages/login.php' class='btn small-btn modal-close'>Entendi</a>
          </div>
      </div>
    </div>";
    exit();
}

// Obtendo o ID do anunciante da sessão --> vindo da página de login
$anunciante_id = $_SESSION['user_id'];

// se conectando ao banco de dados (teste)
include("../conexao.php");
$conn = conectar(); //$conn recebe a função conectar() vindo da conexao.php

// recebendo as variaveis do formulario de adicionar projetos do anunciante pelo metodo post
$titulo = $_POST['titulo'];
$formato = $_POST['formato'];
$descricao = $_POST['descricao'];
$valor = $_POST['valor'];
$categoria = $_POST['categoria'];
$dataInicio = $_POST['dataInicio'];
$dataFinal = $_POST['dataFinal'];
$cidade = $_POST['cidade'];
$uf = $_POST['uf'];


// preparando o insert into com pseudo-nomes
$adicionar = $conn->prepare("INSERT INTO projeto(titulo, formato, descricao, valor, categoria, dataInicio, dataFinal, dataPostagem, cidade, uf, anunciante_id)
VALUES (:titulo, :formato, :descricao, :valor, :categoria, :dataInicio, :dataFinal,  NOW(), :cidade, :uf, :anunciante_id)");
// a função NOW() retorna a data e hora atuais no formato 'YYYY-MM-DD HH:MM:SS'.

// passando os dados das variaveis para os pseudo-nomes
$adicionar->bindValue(":titulo", $titulo);
$adicionar->bindValue(":formato", $formato);
$adicionar->bindValue(":descricao", $descricao);
$adicionar->bindValue(":valor", $valor);
$adicionar->bindValue(":categoria", $categoria);
$adicionar->bindValue(":dataInicio", $dataInicio);
$adicionar->bindValue(":dataFinal", $dataFinal);
$adicionar->bindValue(":cidade", $cidade);
$adicionar->bindValue(":uf", $uf);
$adicionar->bindValue(":anunciante_id", $anunciante_id);

// executando a inserção dos dados no banco de dados
if ($adicionar->execute()) {
    // mostrando modal de "projeto adicionado" e redirecionando de volta para a página principal
    $_SESSION["projeto-adicionado"] = true;
    header("Location: ../../pages/anunciante/adicionar-projeto.php");
    exit();
} else {
    // Em caso de erro, exiba uma mensagem de erro
    echo "Erro ao adicionar o projeto.";
}
