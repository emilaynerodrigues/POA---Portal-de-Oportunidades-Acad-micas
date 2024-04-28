<?php
// iniciando sessão
session_start();
ob_start(); //limpando buffer

// Verificando se o usuário está logado como anunciante
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'anunciante') {
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

//fazendo conexão com o banco de dados
include("../../php/conexao.php");
$conn = conectar();

//recebendo o id do candidato através da URL, utilizando o método GET
$id_aluno = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

//caso a variável "id" não esteja vazia, pesquisar pelo candidato no banco de dados.
$query_candidato = "SELECT id, nome, cpf, email, matricula, escolaridade, descricao, curso_qualificacao, linkedin, whatsapp FROM aluno WHERE id=:id_aluno LIMIT 1";

//preparando a query
$result_candidato = $conn->prepare($query_candidato);
$result_candidato->bindParam(":id_aluno", $id_aluno, PDO::PARAM_INT);

//executando a consulta
$result_candidato->execute();

//verificar se encontrou o candidato no banco
if ($result_candidato->rowCount() != 1) {
    // candidato não encontrado, redirecionar para página inicial do anunciante
    header("Location: ../../pages/anunciante/home.php");
    exit; // encerrando o script para evitar que o restante seja executado
}

//armazenando os dados em um Array Associativo
$row_candidato = $result_candidato->fetch(PDO::FETCH_ASSOC);

// Consulta SQL para recuperar o nome do projeto ao qual o aluno se candidatou
$query_projeto = "SELECT p.titulo AS nome_projeto
                  FROM projeto p
                  INNER JOIN candidatura c ON p.id = c.projeto_id
                  WHERE c.aluno_id = :id_aluno";

// Preparando e executando a consulta
$result_projeto = $conn->prepare($query_projeto);
$result_projeto->bindParam(":id_aluno", $id_aluno, PDO::PARAM_INT);
$result_projeto->execute();

// Verificando se o projeto foi encontrado
if ($result_projeto->rowCount() > 0) {
    // Recuperando o nome do projeto
    $row_projeto = $result_projeto->fetch(PDO::FETCH_ASSOC);
    $nome_projeto = $row_projeto['nome_projeto'];
} else {
    // Caso o aluno não tenha se candidatado a nenhum projeto
    $nome_projeto = "Nenhum projeto encontrado"; // ou qualquer outra mensagem de sua escolha
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Portfólio - <?php echo ucwords(strtolower($row_candidato['nome'])); ?></title>


    <!-- links css -->
    <link rel="stylesheet" href="../../styles/main.css" />
    <link rel="stylesheet" href="../../styles/home.css" />

    <!-- link favicon -->
    <link rel="shortcut icon" href="../../img/favicon.png" type="image/x-icon" />

    <!-- link font symbols -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- link para font awesome - icons de redes sociais -->
    <script src="https://kit.fontawesome.com/5b0674db03.js" crossorigin="anonymous"></script>
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
            <a href="home.php" class="logo">
                <img src="../../img/logo-escura.png" alt="" srcset="" style="height: 40px" />
            </a>
        </header>

        <main class="content-wrapper">
            <!-- seção do menu inicial -->
            <section class="content content-portfolio">
                <!-- perfil -->
                <div class="profile-aside">
                    <span>PORTFÓLIO DE ALUNO</span>
                    <div class="profile-icon profile-candidato">
                        <span class="material-symbols-outlined">person</span>
                    </div>
                    <p class="nome-candidato"><?php echo $row_candidato['nome']; ?></p>

                    <div class="line"></div>

                    <div class="contato">
                        <h3>Entre em contato</h3>

                        <p>Você pode entrar em contato com aluno através dos canais abaixo:</p>

                        <div class="contato-item">
                            <!-- whatsapp -->
                            <a href="https://api.whatsapp.com/send?phone=<?php echo $row_candidato['whatsapp']; ?>&text=O%20Portal%20de%20Oportunidades%20Acadêmicas%20informa%20que%20você%20foi%20selecionado%20para%20seguir%20no%20projeto%20<?php echo urlencode($nome_projeto); ?>.%20Estamos%20entrando%20em%20contato%20para%20dar%20os%20próximos%20passos%20nessa%20jornada." id="whatsapp" target="_blank">
                                <div class="contato-icon"><i class="fa-brands fa-whatsapp" style="font-size: 18px; font-weight: 500;"></i></div>

                                <?php echo $row_candidato['whatsapp']; ?>
                            </a>

                            <!-- email -->
                            <a href="mailto:<?php echo $row_candidato['email']; ?>?subject=O%20Portal%20de%20Oportunidades%20Acadêmicas%20informa%20que%20você%20foi%20selecionado%20para%20seguir%20no%20projeto%20<?php echo urlencode($nome_projeto); ?>.%20Estamos%20entrando%20em%20contato%20para%20dar%20os%20próximos%20passos%20nessa%20jornada." id="email" target="_blank">
                                <div class="contato-icon"><i class="fa-regular fa-envelope" style="font-size: 16px; font-weight: 200;"></i></div>

                                <?php echo $row_candidato['email']; ?>
                            </a>

                            <!-- linkedin -->
                            <a href="https://www.linkedin.com/in/<?php echo $row_candidato['linkedin']; ?>/?message=O%20Portal%20de%20Oportunidades%20Acadêmicas%20informa%20que%20você%20foi%20selecionado%20para%20seguir%20no%20projeto%20<?php echo urlencode($nome_projeto); ?>.%20Estamos%20entrando%20em%20contato%20para%20dar%20os%20próximos%20passos%20nessa%20jornada." id="linkedin" target="_blank">
                                <div class="contato-icon"><i class="fa-brands fa-linkedin-in" style="font-size: 14px; font-weight: light;"></i></div>

                                <?php echo $row_candidato['linkedin']; ?>
                            </a>

                        </div>
                    </div>
                </div>

                <!-- dados -->
                <div class="col">
                    <!-- formulario de atualização dos dados do candidato -->
                    <!-- <h4>Dados do Aluno</h4> -->
                    <div class="form-1">
                        <!-- descrição -->
                        <div class="item">
                            <h4>Descrição</h4>

                            <div class="text-item">
                                <?php echo $row_candidato['descricao']; ?>
                            </div>
                        </div>

                        <!-- escolaridade -->
                        <div class="item">
                            <h4>Escolaridade</h4>

                            <div class="text-item">
                                <?php echo $row_candidato['escolaridade']; ?>
                            </div>
                        </div>

                        <!-- curso_qualificacao -->
                        <div class="item">
                            <h4>Cursos de Qualificação</h4>

                            <div class="text-item">
                                <?php echo $row_candidato['curso_qualificacao']; ?>
                            </div>
                        </div>


                    </div>
                </div>

            </section>
        </main>
    </div>


    <script src="../../js/modalConfirm.js"></script>
</body>

</html>