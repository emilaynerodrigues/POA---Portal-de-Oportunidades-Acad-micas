<?php
session_start();

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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Adicione seu projeto - POA</title>

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

    <!-- criando mensagem de projeto publicado-->
    <?php
    // verificando se a função existe com a função isset
    if (isset($_SESSION['projeto-adicionado'])) : //abrindo if
    ?>
        <!-- Modal de confirmação - Projeto publicado! -->
        <div class="modal modal-session">
            <div class="modal-content">
                <a href="home.php"><span class="modal-close close-icon material-symbols-outlined"> close </span></a>
                <span class="icon material-symbols-outlined"> check_circle </span>
                <h3>Projeto publicado!</h3>
                <p>Seu projeto foi publicado com sucesso! Agora os alunos têm a oportunidade de se candidatar. Esteja atento às candidaturas!</p>
                <div class="btn-wrapper">
                    <a href="home.php" class="btn small-btn modal-close">Entendi</a>
                </div>
            </div>
        </div>

    <?php
    endif; //fechando if
    // destruindo sessão após mostrar a mensagem
    unset($_SESSION['projeto-adicionado']);

    ?>
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
                        <h4>Novo Projeto</h4>
                        <p>Adicione seu projeto e permita que os alunos se candidatem para contribuir</p>
                    </div>

                    <!-- formulario -->
                    <form action="../../php/anunciante/script_adicionarProjeto.php" method="post">
                        <!-- primeira linha -->
                        <div class="row">
                            <!-- titulo do projeto -->
                            <div class="form-item">
                                <input type="text" name="titulo" id="titulo-input" required />
                                <label for="titulo-input">Titulo do Projeto*</label>
                            </div>

                            <!-- categoria -->
                            <div class="form-item select">
                                <select name="categoria" id="categoria-select" required>
                                    <option value="" disabled selected hidden>Selecione uma categoria</option>
                                    <option value="Arte e Design">Arte e Design</option>
                                    <option value="Beleza e Estética">Beleza e Estética</option>
                                    <option value="Gestão e Finanças">Gestão e Finanças</option>
                                    <option value="Manutenção de Computadores">Manutenção de Computadores</option>
                                    <option value="Marketing e Vendas">Marketing e Vendas</option>
                                    <option value="Projetos Sociais">Projetos Sociais</option>
                                    <option value="Suporte e Administrativo">Suporte e Administrativo</option>
                                    <option value="TI e Programação">TI e Programação</option>
                                    <option value="Tradução e Contéudos">Tradução e Contéudos</option>
                                </select>

                                <label for="categoria-select">Categoria*</label>
                            </div>

                            <!-- formato de trabalho -->
                            <div class="form-item select">
                                <select name="formato" id="formato-select" required>
                                    <option value="" disabled selected hidden>Selecione um formato de trabalho</option>
                                    <option value="remoto">Remoto</option>
                                    <option value="presencial">Presencial</option>
                                </select>

                                <label for="formato-select">Formato de Trabalho*</label>
                            </div>
                        </div>

                        <!-- segunda linha -->
                        <div class="row">
                            <!-- valor do projeto -->
                            <div class="form-item">
                                <input type="text" name="valor" id="valor-input" />
                                <label for="valor-input">Valor*</label>
                            </div>
                            <!-- data-inicio -->
                            <div class="form-item">
                                <input type="date" name="dataInicio" id="dataInicio-input" />
                                <label for="dataInicio-input">Data de início</label>
                            </div>
                            <!-- data-final -->
                            <div class="form-item">
                                <input type="date" name="dataFinal" id="dataFinal-input" />
                                <label for="dataFinal-input">Data de finalização</label>
                            </div>
                            <!-- cidade -->
                            <div class="form-item">
                                <input type="text" name="cidade" id="cidade-input" />
                                <label for="cidade-input">Cidade</label>
                            </div>
                            <!-- uf -->
                            <div class="form-item select">
                                <select name="uf" id="uf-select">
                                    <option value="" disabled selected hidden>Selecione</option>
                                    <option value="AC">AC</option>
                                    <option value="AL">AL</option>
                                    <option value="AP">AP</option>
                                    <option value="AM">AM</option>
                                    <option value="BA">BA</option>
                                    <option value="CE">CE</option>
                                    <option value="DF">DF</option>
                                    <option value="ES">ES</option>
                                    <option value="GO">GO</option>
                                    <option value="MA">MA</option>
                                    <option value="MT">MT</option>
                                    <option value="MS">MS</option>
                                    <option value="MG">MG</option>
                                    <option value="PA">PA</option>
                                    <option value="PB">PB</option>
                                    <option value="PR">PR</option>
                                    <option value="PE">PE</option>
                                    <option value="PI">PI</option>
                                    <option value="RJ">RJ</option>
                                    <option value="RN">RN</option>
                                    <option value="RS">RS</option>
                                    <option value="RO">RO</option>
                                    <option value="RR">RR</option>
                                    <option value="SC">SC</option>
                                    <option value="SP">SP</option>
                                    <option value="SE">SE</option>
                                    <option value="TO">TO</option>
                                </select>

                                <label for="uf-select">UF</label>
                            </div>

                        </div>

                        <!-- descricao do projeto -->
                        <div id="descricao" class="form-item">
                            <textarea id="descricao-input" name="descricao" rows="6" cols="50" maxlength="1000" required></textarea>
                            <label for="descricao-input">Descrição*</label>
                            <span id="contador">* 1000 caracteres restantes</span>
                        </div>

                        <div class="btn-wrapper">
                            <button type="submit" class="btn small-btn cancel-btn" id="cancelBtn">Cancelar</button>
                            <button type="submit" class="btn small-btn">Publicar Projeto</button>
                        </div>
                    </form>
                </div>
            </section>

        </main>
    </div>

    <!-- Modal de confirmação - Perda de dados -->
    <div id="confirmModal" class="modal modal-confirm">
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