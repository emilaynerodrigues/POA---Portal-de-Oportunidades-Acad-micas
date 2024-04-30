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

//recebendo o id do aluno através da URL, utilizando o método GET
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

//caso a variável "id" não esteja vazia, pesquisar pelo aluno no banco de dados.
$query_aluno = "SELECT id, nome, cpf, matricula, genero, dataNasc, email, telefone, whatsapp, linkedin, escolaridade, descricao, curso_qualificacao  FROM aluno WHERE id=:id LIMIT 1";

//preparando a query
$result_aluno = $conn->prepare($query_aluno);
$result_aluno->bindParam(":id", $id, PDO::PARAM_INT);

//executando a consulta
$result_aluno->execute();

//verificar se encontrou o aluno no banco
if ($result_aluno->rowCount() != 1) {
    // aluno não encontrado, redirecionar para página inicial do administrador
    header("Location: ../../pages/admin/home.php");
    exit; // encerrando o script para evitar que o restante seja executado
}

//armazenando os dados em um Array Associativo
$row_aluno = $result_aluno->fetch(PDO::FETCH_ASSOC);

// verificando se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['Editaluno'])) {
    // recebendo os dados do formulário
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    // implementando a atualização do aluno
    $query_update = "UPDATE aluno SET nome=:nome, cpf=:cpf, matricula=:matricula, genero=:genero, dataNasc=:dataNasc, email=:email, telefone=:telefone, whatsapp=:whatsapp, linkedin=:linkedin, 
    escolaridade=:escolaridade, descricao=:descricao, curso_qualificacao=:curso_qualificacao WHERE id=:id";

    //preparando a query 
    $edit_aluno = $conn->prepare($query_update);

    // passando os dados das variáveis para os pseudo-nomes
    $edit_aluno->bindParam(":nome", $dados['nome'], PDO::PARAM_STR);
    $edit_aluno->bindParam(":cpf", $dados['cpf'], PDO::PARAM_STR);
    $edit_aluno->bindParam(":matricula", $dados['matricula'], PDO::PARAM_STR);
    $edit_aluno->bindParam(":genero", $dados['genero'], PDO::PARAM_STR);
    $edit_aluno->bindParam(":dataNasc", $dados['dataNasc'], PDO::PARAM_STR);
    $edit_aluno->bindParam(":email", $dados['email'], PDO::PARAM_STR);
    $edit_aluno->bindParam(":telefone", $dados['telefone'], PDO::PARAM_STR);
    $edit_aluno->bindParam(":whatsapp", $dados['whatsapp'], PDO::PARAM_STR);
    $edit_aluno->bindParam(":linkedin", $dados['linkedin'], PDO::PARAM_STR);
    $edit_aluno->bindParam(":escolaridade", $dados['escolaridade'], PDO::PARAM_STR);
    $edit_aluno->bindParam(":descricao", $dados['descricao'], PDO::PARAM_STR);
    $edit_aluno->bindParam(":curso_qualificacao", $dados['curso_qualificacao'], PDO::PARAM_STR);
    $edit_aluno->bindParam(":id", $id, PDO::PARAM_INT);

    // verificando se a execução da query foi realizada com sucesso
    if ($edit_aluno->execute()) {
        // Atualização bem-sucedida, redirecionar para a página de alteração do aluno
        $_SESSION["mensagem"] = "
        <div class='modal modal-session'>
            <div class='modal-content'>
                <a href='home.php'><span class='modal-close close-icon material-symbols-outlined'> close </span></a>
                <span class='icon material-symbols-outlined'> check_circle </span>
                <h3>Dados alterados com sucesso!</h3>
                <p>As alterações foram salvas com sucesso e seu aluno está atualizado</p>
                <div class='btn-wrapper'>
                    <a href='home.php' class='btn small-btn modal-close'>Entendi</a>
                </div>
            </div>
        </div>";
        //para então mostrar mensagem do modal
        header("Location: aluno-alterar.php?id=$id");
        exit; // encerrando o script para evitar que o restante seja executado
    } else {
        // atualização falhou, exibir mensagem de erro
        $error_message = "Erro ao atualizar o aluno.";
    }
}

// verificando se a variável de sessão está definida para exibir o modal de confirmação
$show_modal = isset($_SESSION['aluno-atualizado']);
// destruindo sessão após mostrar a mensagem
unset($_SESSION['aluno-atualizado']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Altere os dados do aluno - POA</title>

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

        <main class="content-wrapper dados-wrapper">
            <!-- seção do menu inicial -->
            <section class="content">
                <div class="form-container">
                    <div class="form-head">
                        <h4>Alterar dados do aluno</h4>
                        <p>Altere os dados do aluno conforme necessário</p>
                    </div>

                    <!-- formulario -->
                    <form action="" method="post">
                        <!-- nome do aluno -->
                        <div class="form-item">
                            <input type="text" name="nome" id="nome-input" required value="<?php echo isset($dados['nome']) ? $dados['nome'] : $row_aluno['nome']; ?>" />
                            <label for="nome-input">Nome do aluno*</label>
                        </div>

                        <!-- segunda linha -->
                        <div class="row">
                            <!-- cpf -->
                            <div class="form-item">
                                <input type="text" name="cpf" id="cpf-input" required value="<?php echo isset($dados['cpf']) ? $dados['cpf'] : $row_aluno['cpf']; ?>" />
                                <label for="cpf-input">CPF*</label>
                            </div>

                            <!-- matricula -->
                            <div class="form-item">
                                <input type="text" name="matricula" id="matricula-input" required value="<?php echo isset($dados['matricula']) ? $dados['matricula'] : $row_aluno['matricula']; ?>" />
                                <label for="matricula-input">Matrícula*</label>
                            </div>

                            <!-- genero -->
                            <div class="form-item select">
                                <select name="genero" id="genero-select" required>
                                    <option value="" disabled hidden>Selecione um gênero</option>
                                    <?php
                                    $generos = array("Feminino" => "f", "Masculino" => "m");
                                    foreach ($generos as $genero => $valor) {
                                        echo "<option value='$valor'";
                                        if ($row_aluno['genero'] === $valor) {
                                            echo " selected";
                                        }
                                        echo ">$genero</option>";
                                    }
                                    ?>
                                </select>
                                <label for="genero-select">Gênero*</label>
                            </div>



                            <!-- dataNasc -->
                            <div class="form-item">
                                <input type="date" name="dataNasc" id="dataNasc-input" value="<?php echo isset($dados['dataNasc']) ? $dados['dataNasc'] : $row_aluno['dataNasc']; ?>" required />
                                <label for="dataNasc-input">Data de nascimento*</label>
                            </div>

                        </div>

                        <!-- email -->
                        <div class="form-item">
                            <input type="email" name="email" id="email-input" required value="<?php echo isset($dados['email']) ? $dados['email'] : $row_aluno['email']; ?>" />
                            <label for="email-input">E-mail*</label>
                        </div>

                        <div class="row">
                            <!-- telefone -->
                            <div class="form-item">
                                <input type="text" name="telefone" id="telefone-input" value="<?php echo isset($dados['telefone']) ? $dados['telefone'] : $row_aluno['telefone']; ?>" required />
                                <label for="telefone-input">Telefone*</label>
                            </div>
                            <!-- whatsapp -->
                            <div class="form-item">
                                <input type="text" name="whatsapp" id="whatsapp-input" value="<?php echo isset($dados['whatsapp']) ? $dados['whatsapp'] : $row_aluno['whatsapp']; ?>" required />
                                <label for="whatsapp-input">WhatsApp*</label>
                            </div>

                            <!-- linkedin -->
                            <div class="form-item">
                                <input type="text" name="linkedin" id="linkedin-input" value="<?php echo isset($dados['linkedin']) ? $dados['linkedin'] : $row_aluno['linkedin']; ?>" />
                                <label for="linkedin-input">Linkedin</label>
                            </div>
                        </div>

                        <div class="form-item">
                            <label for="descricao-aluno">Descrição</label>
                            <textarea name="descricao" id="descricao-aluno" cols="30" rows="10"><?php echo isset($dados['descricao']) ? $dados['descricao'] : $row_aluno['descricao']; ?></textarea>
                        </div>

                        <div class="form-item">
                            <label for="escolaridade-aluno">Escolaridade</label>
                            <textarea name="escolaridade" id="escolaridade-aluno" cols="30" rows="10"><?php echo isset($dados['escolaridade']) ? $dados['escolaridade'] : $row_aluno['escolaridade']; ?></textarea>
                        </div>

                        <div class="form-item">
                            <label for="curso-aluno">Cursos de qualificação</label>
                            <textarea name="curso_qualificacao" id="curso-aluno" cols="30" rows="10"><?php echo isset($dados['curso_qualificacao']) ? $dados['curso_qualificacao'] : $row_aluno['curso_qualificacao']; ?></textarea>
                        </div>

                        <div class="btn-wrapper">
                            <button type="submit" class="btn small-btn" name="Editaluno">Alterar aluno</button>
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
            <h3>Seus dados serão perdidos!</h3>
            <p>Tem certeza que deseja cancelar a operação? Seus dados serão perdidos!</p>
            <div class="btn-wrapper">
                <button class="btn small-btn outline-btn modal-close">Cancelar</button>
                <a href="lista-alunos.php" class="btn small-btn">Sim</a>
            </div>
        </div>
    </div>

    <script src="../../js/modalConfirm.js"></script>
</body>

</html>