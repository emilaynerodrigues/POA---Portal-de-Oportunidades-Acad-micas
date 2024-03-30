<?php
// função para otimização de conexão em outros arquivos
function conectar()
{
    //tratamento de excessos com tryCatch - tenta fazer a coneção e se não conseguir mostra a mensagem do erro (dentro do catch)
    try {
        $conn = new PDO("mysql:host=localhost; dbname=poa", "root", "");
        // echo "conectado com sucesso!";
    } catch (PDOException $exception) {
        echo "Mensagem de error: " . $exception->getMessage(); //mensagem do erro
        echo "<br><br>Código de error: " . $exception->getCode(); //codigo do erro
    }

    // retorna a variavel de conexao
    return $conn;
}

conectar(); //chamada da função
?>