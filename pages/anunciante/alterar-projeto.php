<?php
// iniciando sessão
session_start();
ob_start(); //limpando buffer

// Verificando se o usuário está logado como aluno
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'anunciante') {
    // Se não estiver logado como aluno, redirecione para a página de login
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

//recebendo o id do projeto através da URL, utilizando o método GET
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

//caso a variável "id" não esteja vazia, pesquisar pelo projeto no banco de dados.
$query_projeto = "SELECT id, titulo, categoria, formato, valor, dataInicio, dataFinal, cidade, uf, descricao, dataPostagem FROM projeto WHERE id=:id LIMIT 1";

//preparando a query
$result_projeto = $conn->prepare($query_projeto);
$result_projeto->bindParam(":id", $id, PDO::PARAM_INT);

//executando a consulta
$result_projeto->execute();

//verificar se encontrou o projeto no banco
if ($result_projeto->rowCount() != 1) {
    // projeto não encontrado, redirecionar para página inicial do anunciante
    header("Location: ../../pages/anunciante/home.php");
    exit; // encerrando o script para evitar que o restante seja executado
}

//armazenando os dados em um Array Associativo
$row_projeto = $result_projeto->fetch(PDO::FETCH_ASSOC);

// verificando se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['EditProjeto'])) {
    // recebendo os dados do formulário
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    // implementando a atualização do projeto
    $query_update = "UPDATE projeto SET titulo=:titulo, categoria=:categoria, formato=:formato, valor=:valor, dataInicio=:dataInicio, dataFinal=:dataFinal, cidade=:cidade, uf=:uf, descricao=:descricao WHERE id=:id";

    //preparando a query 
    $edit_projeto = $conn->prepare($query_update);

    // passando os dados das variáveis para os pseudo-nomes
    $edit_projeto->bindParam(":titulo", $dados['titulo'], PDO::PARAM_STR);
    $edit_projeto->bindParam(":categoria", $dados['categoria'], PDO::PARAM_STR);
    $edit_projeto->bindParam(":formato", $dados['formato'], PDO::PARAM_STR);
    $edit_projeto->bindParam(":valor", $dados['valor'], PDO::PARAM_STR);
    $edit_projeto->bindParam(":dataInicio", $dados['dataInicio'], PDO::PARAM_STR);
    $edit_projeto->bindParam(":dataFinal", $dados['dataFinal'], PDO::PARAM_STR);
    $edit_projeto->bindParam(":cidade", $dados['cidade'], PDO::PARAM_STR);
    $edit_projeto->bindParam(":uf", $dados['uf'], PDO::PARAM_STR);
    $edit_projeto->bindParam(":descricao", $dados['descricao'], PDO::PARAM_STR);
    $edit_projeto->bindParam(":id", $id, PDO::PARAM_INT);

    // verificando se a execução da query foi realizada com sucesso
    if ($edit_projeto->execute()) {
        // Atualização bem-sucedida, redirecionar para a página de alteração do projeto
        $_SESSION["projeto-atualizado"] = true;
        // redirecionar para a página de alteração do projeto após o tratamento do formulário
        //para então mostrar mensagem do modal
        header("Location: alterar-projeto.php?id=$id");
        exit; // encerrando o script para evitar que o restante seja executado
    } else {
        // atualização falhou, exibir mensagem de erro
        $error_message = "Erro ao atualizar o projeto.";
    }
}

// verificando se a variável de sessão está definida para exibir o modal de confirmação
$show_modal = isset($_SESSION['projeto-atualizado']);
// destruindo sessão após mostrar a mensagem
unset($_SESSION['projeto-atualizado']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Altere os dados do seu projeto - POA</title>

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

    <!-- mostrando mensagem de projeto publicado-->
    <?php if ($show_modal) : ?>
        <!-- Modal de confirmação - Projeto atualizado! -->
        <div class="modal modal-session">
            <div class="modal-content">
                <a href="home.php"><span class="modal-close close-icon material-symbols-outlined"> close </span></a>
                <span class="icon material-symbols-outlined"> check_circle </span>
                <h3>Dados alterados com sucesso!</h3>
                <p>As alterações foram salvas com sucesso e seu projeto está atualizado</p>
                <div class="btn-wrapper">
                    <a href="home.php" class="btn small-btn modal-close">Entendi</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <!-- fechando mensagem de projeto publicado-->

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
                <img src="../../img/logo-escura.png" alt="" srcset="" style="height: 50px" />
            </div>
        </header>

        <main class="content-wrapper">
            <!-- seção do menu inicial -->
            <section class="content">
                <div class="form-container">
                    <div class="form-head">
                        <h4>Alterar dados do projeto</h4>
                        <p>Altere os dados de seu projeto e permita que os alunos se candidatem para contribuir</p>
                    </div>

                    <!-- formulario -->
                    <form action="" method="post">
                        <!-- primeira linha -->
                        <div class="row">
                            <!-- titulo do projeto -->
                            <div class="form-item">
                                <input type="text" name="titulo" id="titulo-input" required value="<?php echo isset($dados['titulo']) ? $dados['titulo'] : $row_projeto['titulo']; ?>" />
                                <label for="titulo-input">Titulo do Projeto*</label>
                            </div>

                            <!-- categoria -->
                            <div class="form-item select">
                                <select name="categoria" id="categoria-select" required>
                                    <option value="" disabled selected hidden>Selecione uma categoria</option>

                                    <?php
                                    $categorias = array("Arte e Design", "Beleza e Estética", "Gestão e Finanças", "Manutenção de Computadores", "Marketing e Vendas", "Projetos Sociais", "Suporte e Administrativo", "TI e Programação", "Tradução e Contéudos");
                                    foreach ($categorias as $categoria) {
                                        echo "<option value='$categoria'";
                                        if (isset($dados['categoria']) && $dados['categoria'] == $categoria) {
                                            echo " selected";
                                        } elseif (isset($row_projeto['categoria']) && $row_projeto['categoria'] == $categoria) {
                                            echo " selected";
                                        }
                                        echo ">$categoria</option>";
                                    }
                                    ?>
                                </select>

                                <label for="categoria-select">Categoria*</label>
                            </div>

                            <!-- formato de trabalho -->
                            <div class="form-item select">
                                <select name="formato" id="formato-select" required>
                                    <option value="" disabled selected hidden>Selecione um formato de trabalho</option>
                                    <?php
                                    $formatos = array("Remoto", "Presencial");
                                    foreach ($formatos as $formato) {
                                        echo "<option value='$formato'";
                                        if (isset($dados['formato']) && $dados['formato'] == $formato) {
                                            echo " selected"; // marca como selecionado se o formato corresponder aos dados recebidos
                                        } elseif (isset($row_projeto['formato']) && $row_projeto['formato'] == $formato) {
                                            echo " selected"; // marca como selecionado se o formato corresponder aos dados do banco de dados
                                        }
                                        echo ">$formato</option>";
                                    }
                                    ?>
                                </select>


                                <label for="formato-select">Formato de Trabalho*</label>
                            </div>

                        </div>

                        <!-- segunda linha -->
                        <div class="row">
                            <!-- valor do projeto -->
                            <div class="form-item">
                                <input type="text" name="valor" id="valor-input" value="<?php echo isset($dados['valor']) ? $dados['valor'] : $row_projeto['valor']; ?>" />
                                <label for="valor-input">Valor</label>
                            </div>

                            <!-- data-inicio -->
                            <div class="form-item">
                                <input type="date" name="dataInicio" id="dataInicio-input" value="<?php echo isset($dados['dataInicio']) ? $dados['dataInicio'] : $row_projeto['dataInicio']; ?>" />
                                <label for="dataInicio-input">Data de início</label>
                            </div>

                            <!-- data-final -->
                            <div class="form-item">
                                <input type="date" name="dataFinal" id="dataFinal-input" value="<?php echo isset($dados['dataFinal']) ? $dados['dataFinal'] : $row_projeto['dataFinal']; ?>" />
                                <label for="dataFinal-input">Data de finalização</label>
                            </div>

                            <!-- cidade -->
                            <div class="form-item">
                                <input type="text" name="cidade" id="cidade-input" value="<?php echo isset($dados['cidade']) ? $dados['cidade'] : $row_projeto['cidade']; ?>" />
                                <label for="cidade-input">Cidade</label>
                            </div>

                            <!-- uf -->
                            <div class="form-item select">
                                <select name="uf" id="uf-select">
                                    <option value="" disabled selected hidden>Selecione</option>
                                    <?php
                                    $ufs = array("AC", "AL", "AP", "AM", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PA", "PB", "PR", "PE", "PI", "RJ", "RN", "RS", "RO", "RR", "SC", "SP", "SE", "TO");
                                    foreach ($ufs as $uf) {
                                        echo "<option value='$uf'";
                                        if (isset($dados['uf']) && $dados['uf'] == $uf) {
                                            echo " selected";
                                        } elseif (isset($row_projeto['uf']) && $row_projeto['uf'] == $uf) {
                                            echo " selected";
                                        }
                                        echo ">$uf</option>";
                                    }
                                    ?>
                                </select>

                                <label for="uf-select">UF</label>
                            </div>

                        </div>

                        <!-- descricao do projeto -->
                        <div id="descricao" class="form-item">
                            <textarea id="descricao-input" name="descricao" rows="6" cols="50" maxlength="1000" required><?php echo isset($dados['descricao']) ? $dados['descricao'] : (isset($row_projeto['descricao']) ? $row_projeto['descricao'] : ''); ?></textarea>
                            <label for="descricao-input">Descrição*</label>
                            <span id="contador">* 1000 caracteres restantes</span>
                        </div>

                        <div class="btn-wrapper">
                            <button type="submit" class="btn small-btn" name="EditProjeto">Alterar Projeto</button>
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