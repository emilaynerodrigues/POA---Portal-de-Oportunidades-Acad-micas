<?php
// iniciando sessão
session_start();

// se conectando ao banco de dados (teste)
include("../conexao.php");
$conn = conectar(); //$conn recebe a função conectar() vindo da conexao.php

// recebendo as variaveis do formulario de adicionar projetos do anunciante pelo metodo post
$titulo = $_POST['titulo'];
$categoria = $_POST['categoria'];
$formato = $_POST['formato'];
$valor = $_POST['valor'];
$dataInicio = $_POST['dataInicio'];
$dataFinal = $_POST['dataFinal'];
$cidade = $_POST['cidade'];
$uf = $_POST['uf'];
$descricao = $_POST['descricao'];

// preparando o insert into com pseudo-nomes
$adicionar = $conn->prepare("INSERT INTO projeto(titulo, categoria, formato, valor, dataInicio, dataFinal, cidade, uf, descricao, dataPostagem)
VALUES (:titulo, :categoria, :formato, :valor, :dataInicio, :dataFinal, :cidade, :uf, :descricao, NOW())");
// a função NOW() retorna a data e hora atuais no formato 'YYYY-MM-DD HH:MM:SS'.

// passando os dados das variaveis para os pseudo-nomes
$adicionar->bindValue(":titulo", $titulo);
$adicionar->bindValue(":categoria", $categoria);
$adicionar->bindValue(":formato", $formato);
$adicionar->bindValue(":valor", $valor);
$adicionar->bindValue(":dataInicio", $dataInicio);
$adicionar->bindValue(":dataFinal", $dataFinal);
$adicionar->bindValue(":cidade", $cidade);
$adicionar->bindValue(":uf", $uf);
$adicionar->bindValue(":descricao", $descricao);

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
