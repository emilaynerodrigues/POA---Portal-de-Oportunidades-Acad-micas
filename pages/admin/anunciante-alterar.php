<?php
// iniciando sessão
session_start();
ob_start(); //limpando buffer

// Verificando se o usuário está logado como administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    // Se não estiver logado como administrador, redirecione para a página de login
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

//fazendo conexão com o banco de dados
include("../../php/conexao.php");
$conn = conectar();

//recebendo o id do anunciante através da URL, utilizando o método GET
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

//caso a variável "id" não esteja vazia, pesquisar pelo anunciante no banco de dados.
$query_anunciante = "SELECT id, nome, nome_empresa, cnpj, email FROM anunciante WHERE id=:id LIMIT 1";

//preparando a query
$result_anunciante = $conn->prepare($query_anunciante);
$result_anunciante->bindParam(":id", $id, PDO::PARAM_INT);

//executando a consulta
$result_anunciante->execute();

//verificar se encontrou o anunciante no banco
if ($result_anunciante->rowCount() != 1) {
    // anunciante não encontrado, redirecionar para página inicial do administrador
    header("Location: ../../pages/admin/home.php");
    exit; // encerrando o script para evitar que o restante seja executado
}

//armazenando os dados em um Array Associativo
$row_anunciante = $result_anunciante->fetch(PDO::FETCH_ASSOC);

// verificando se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['EditAnunciante'])) {
    // recebendo os dados do formulário
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    // implementando a atualização do anunciante
    $query_update = "UPDATE anunciante SET nome=:nome, nome_empresa=:nome_empresa, cnpj=:cnpj, email=:email WHERE id=:id";

    //preparando a query 
    $edit_anunciante = $conn->prepare($query_update);

    // passando os dados das variáveis para os pseudo-nomes
    $edit_anunciante->bindParam(":nome", $dados['nome'], PDO::PARAM_STR);
    $edit_anunciante->bindParam(":nome_empresa", $dados['nome_empresa'], PDO::PARAM_STR);
    $edit_anunciante->bindParam(":cnpj", $dados['cnpj'], PDO::PARAM_STR);
    $edit_anunciante->bindParam(":email", $dados['email'], PDO::PARAM_STR);
    $edit_anunciante->bindParam(":id", $id, PDO::PARAM_INT);

    // verificando se a execução da query foi realizada com sucesso
    if ($edit_anunciante->execute()) {
        // Atualização bem-sucedida, redirecionar para a página de alteração do anunciante
        $_SESSION["mensagem"] = "
        <div class='modal modal-session'>
            <div class='modal-content'>
                <a href='home.php'><span class='modal-close close-icon material-symbols-outlined'> close </span></a>
                <span class='icon material-symbols-outlined'> check_circle </span>
                <h3>Dados alterados com sucesso!</h3>
                <p>As alterações foram salvas com sucesso e seu anunciante está atualizado</p>
                <div class='btn-wrapper'>
                    <a href='home.php' class='btn small-btn modal-close'>Entendi</a>
                </div>
            </div>
        </div>";
        //para então mostrar mensagem do modal
        header("Location: anunciante-alterar.php?id=$id");
        exit; // encerrando o script para evitar que o restante seja executado
    } else {
        // atualização falhou, exibir mensagem de erro
        $error_message = "Erro ao atualizar o anunciante.";
    }
}

// verificando se a variável de sessão está definida para exibir o modal de confirmação
$show_modal = isset($_SESSION['anunciante-atualizado']);
// destruindo sessão após mostrar a mensagem
unset($_SESSION['anunciante-atualizado']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Altere os dados do anunciante - POA</title>

    <!-- links css -->
    <link rel="stylesheet" href="../../styles/main.css" />
    <link rel="stylesheet" href="../../styles/home.css" />
    <link rel="stylesheet" href="../../styles/crud-projeto.css" />

    <!-- link favicon -->
    <link rel="shortcut icon" href="../../img/favicon.png" type="image/x-icon" />

    <!-- link font symbols -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>

    <!-- menu lateral -->
    <aside class="sidebar">
        <!-- Ícone de hambúrguer -->
        <div class="toggle-container">
        </div>

        <!-- Opções do menu -->
        <ul id="menuOptions">
        </ul>

    </aside>

    <div class="container">
        <header class="header">
            <div class="logo">
                <img src="../../img/logo-escura.png" alt="" srcset="" style="height: 40px" />
            </div>
        </header>

        <!-- mostrando mensagem de preencha todos os dados e/ou dados incorretos -->
        <?php
        //verificando se existe a sessão
        if (isset($_SESSION['mensagem'])) {
            echo $_SESSION['mensagem'];
        }

        //destruindo sessão
        unset($_SESSION['mensagem']);
        ?>

        <main class="content-wrapper">
            <!-- seção do menu inicial -->
            <section class="content">
                <div class="form-container">
                    <div class="form-head">
                        <h4>Alterar dados do anunciante</h4>
                        <p>Altere os dados do anunciante conforme necessário</p>
                    </div>

                    <!-- formulario -->
                    <form action="" method="post">
                        <!-- nome do anunciante -->
                        <div class="form-item">
                            <input type="text" name="nome" id="nome-input" required value="<?php echo isset($dados['nome']) ? $dados['nome'] : $row_anunciante['nome']; ?>" />
                            <label for="nome-input">Nome do Anunciante*</label>
                        </div>

                        <!-- nome da empresa -->
                        <div class="form-item">
                            <input type="text" name="nome_empresa" id="nome_empresa-input" required value="<?php echo isset($dados['nome_empresa']) ? $dados['nome_empresa'] : $row_anunciante['nome_empresa']; ?>" />
                            <label for="nome_empresa-input">Nome da Empresa*</label>
                        </div>

                        <!-- CNPJ -->
                        <div class="form-item">
                            <input type="text" name="cnpj" id="cnpj-input" required value="<?php echo isset($dados['cnpj']) ? $dados['cnpj'] : $row_anunciante['cnpj']; ?>" />
                            <label for="cnpj-input">CNPJ*</label>
                        </div>

                        <!-- email -->
                        <div class="form-item">
                            <input type="email" name="email" id="email-input" required value="<?php echo isset($dados['email']) ? $dados['email'] : $row_anunciante['email']; ?>" />
                            <label for="email-input">E-mail*</label>
                        </div>

                        <div class="btn-wrapper">
                            <button type="submit" class="btn small-btn" name="EditAnunciante">Alterar Anunciante</button>
                        </div>
                    </form>

                    <button type="submit" class="btn small-btn cancel-btn" id="cancelBtn">Cancelar</button>
                </div>
            </section>

        </main>
    </div>

    <!-- Modal de confirmação -->
    <div id="confirmModal" class="modal-confirm modal">
        <div class="modal-content">
            <span class="modal-close close-icon material-symbols-outlined"> close </span>

            <span class="icon material-symbols-outlined"> cancel </span>
            <h3>Seus dados seão perdidos!</h3>
            <p>Tem certeza que deseja cancelar a operação? Seus dados serão perdidos!</p>
            <div class="btn-wrapper">
                <button class="btn small-btn outline-btn modal-close">Cancelar</button>
                <button class="btn small-btn" id="confirmBtn">Sim</button>
            </div>
        </div>
    </div>

    <script src="../../js/modalConfirm.js"></script>
</body>

</html>