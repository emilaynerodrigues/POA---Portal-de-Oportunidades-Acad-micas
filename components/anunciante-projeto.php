<!-- informações do projeto -->
<div class="projeto">
    <div class="head-projeto">
        <div class="user-icon"></div>
        <div class="projeto-info">
            <h3 class="titulo-projeto"><?php echo $projeto['titulo']; ?></h3>
            <p class="info-adicional">postado em <span class="anunciante">
                    <?php
                    // formatando a data de postagem
                    $timestamp = strtotime($projeto['dataPostagem']);
                    $dataFormatada = date('d/m/Y', $timestamp);
                    echo $dataFormatada;
                    ?>
                </span></p>
        </div>
    </div>
    <div class="tags-projeto">
        <span style="text-transform: capitalize;" class="tag formato"><?php echo $projeto['formato']; ?></span>
        <span class="tag categoria"><?php echo $projeto['categoria']; ?></span>
        <span class="tag valor"><?php echo 'R$ ' . $projeto['valor']; ?></span>
    </div>
    <div class="descricao-projeto">
        <p class="descricao-texto"><?php echo $projeto['descricao']; ?></p>
    </div>
    <!-- Botão para abrir o modal -->
    <a href="#" onclick="abrirModal(this)" class="verMais" data-id="<?php echo $projeto['id']; ?>" data-titulo="<?php echo $projeto['titulo']; ?>" data-categoria="<?php echo $projeto['categoria']; ?>" data-formato="<?php echo $projeto['formato']; ?>" data-valor="<?php echo $projeto['valor']; ?>" data-descricao="<?php echo $projeto['descricao']; ?>">Ver mais</a>
</div>

<!--------------------------------------------------------------------------------------------------------------->
<!-- modal com todas as informações do projeto + crud -->
<div id="projectModal" class="modal modal-confirm">
    <div class="modal-content">
        <!-- Icon para fechar o modal -->
        <span id="closeIcon" class="close-icon material-symbols-outlined"> close </span>

        <div class="head-projeto">
            <div class="user-icon"></div>
            <div class="projeto-info">
                <!-- Título do projeto -->
                <h3 id="titulo-projeto" class="titulo-projeto"></h3>
                <p class="info-adicional">
                    data do projeto aqui
                </p>
            </div>
        </div>

        <div class="tags-projeto">
            <!-- Formato de trabalho (remoto ou presencial) -->
            <span style="text-transform: capitalize;" id="formato-projeto" class="tag formato"></span>

            <!-- Categoria -->
            <span id="categoria-projeto" class="tag categoria"></span>

            <!-- Valor -->
            <span id="valor-projeto" class="tag valor"></span>
        </div>

        <!-- Parágrafo para exibir a descrição do projeto -->
        <p id="descricao-projeto" class="descricao-texto"></p>

        <div class="btn-wrapper">
            <a href="#" id="verCandidatos" class="btn normal-btn outline-btn">Candidatos inscritos</a>
            <a href="#" onclick="abrirModalExcluir(this)" class="btn small-btn delete-btn" data-id="<?php echo $projeto['id']; ?>">Excluir projeto</a>
            <a href="#" id="alterarProjeto" class="btn small-btn">Alterar dados</a>
        </div>
    </div>
</div>

<!--------------------------------------------------------------------------------------------------------------->
<!-- modal de confirmação de exclusão de projeto -->
<div id="modalExcluir" class='modal modal-delete modal-crud'>
    <div class='modal-content'>
        <a href='#' class="closeIconExcluir"><span class='modal-close close-icon material-symbols-outlined'> close </span></a>
        <span class='icon material-symbols-outlined'> help </span>
        <h3>Seus dados serão perdidos!</h3>
        <p>Tem certeza que deseja excluir o projeto? Todos os dados serão perdidos!</p>
        <div class='btn-wrapper'>
            <a href='#' class='btn small-btn cancel-btn modal-close closeIconExcluir'>Cancelar</a>
            <a href='#' id="confirmDeleteButton" data-id="<?php echo $projeto['id'] ?>" class='btn small-btn'>Excluir</a>
        </div>
    </div>
</div>

<!-- modal de candidatos inscritos no projeto -->
<?php
// Verifica se o ID do projeto está presente na URL
if (isset($_GET['projeto_id'])) {
    $projeto_id = $_GET['projeto_id'];
    // Exibe o script JavaScript para abrir o modal de candidatos
    echo "<script>document.getElementById('modalCandidatos').style.display = 'flex';</script>";
}
?>

<div id="modalCandidatos" class="modal modal-confirm" style="display: none;">
    <div class="modal-content">
        <span class='modal-close close-icon material-symbols-outlined closeIconCandidatos'> close </span>

        <div class="candidatos-wrapper">
            <!-- Conteúdo do modal de candidatos aqui -->
            <?php
            // Verificando se o projeto_id está definido
            if (isset($_GET['projeto_id'])) {
                $projeto_id = $_GET['projeto_id']; // Obtendo o ID do projeto do parâmetro GET
                // Conectando ao banco de dados
                $sql_candidaturas = "SELECT aluno.id AS aluno_id, aluno.nome AS nome_aluno
                                     FROM candidatura
                                     INNER JOIN aluno ON candidatura.aluno_id = aluno.id
                                     WHERE candidatura.projeto_id = :projeto_id";
                $stmt_candidaturas = $conn->prepare($sql_candidaturas);
                $stmt_candidaturas->bindParam(':projeto_id', $projeto_id);
                $stmt_candidaturas->execute();
                $candidaturas = $stmt_candidaturas->fetchAll(PDO::FETCH_ASSOC);
                // Verificando se há candidaturas
                if ($stmt_candidaturas->rowCount() > 0) {
                    // Exibindo os detalhes das candidaturas
                    foreach ($candidaturas as $candidatura) {
                        // echo "<div>{$projeto_id}</div>";
                        echo "
                        <div class='candidato'>
                            <div class='profile'>
                                <div class='profile-icon'></div>
                                <span id='nome'>{$candidatura['nome_aluno']}</span>
                            </div>
                            <a href='mostrarPortfolio.php?id={$candidatura['aluno_id']}' target='_blank' id='verPorfolio' class='btn outline-btn'>Ver portfólio</a>
                        </div>";
                    }
                } else {
                    echo "<p>Nenhum aluno se candidatou para este projeto ainda.</p>";
                }
            } else {
                // Se o projeto_id não for especificado, exibir uma mensagem de erro ou redirecionar para outra página
                echo 'ID do projeto não especificado';
            }
            ?>
        </div>

    </div>
</div>

<!--------------------------------------------------------------------------------------------------------------->
<!-- script + modal para recuperar alunos cadastrados nos projetos -->
<script>
    var currentProjectId; // Variável global para armazenar o ID do projeto atual

    // função para abrir o modal e exibir a descrição do projeto
    function abrirModal(link) {
        var modal = document.getElementById("projectModal");
        var tituloProjeto = document.getElementById("titulo-projeto");
        var categoriaProjeto = document.getElementById("categoria-projeto");
        var formatoProjeto = document.getElementById("formato-projeto");
        var valorProjeto = document.getElementById("valor-projeto");
        var descricaoProjeto = document.getElementById("descricao-projeto");
        currentProjectId = link.getAttribute("data-id"); // Obtendo o ID do projeto a partir do link

        // obtendo os dados do projeto do atributo de dados do link
        var titulo = link.getAttribute("data-titulo");
        var categoria = link.getAttribute("data-categoria");
        var formato = link.getAttribute("data-formato");
        var valor = link.getAttribute("data-valor");
        var descricao = link.getAttribute("data-descricao");

        // atualizando o conteúdo do modal com os dados do projeto
        tituloProjeto.textContent = titulo;
        categoriaProjeto.textContent = categoria;
        formatoProjeto.textContent = formato;
        valorProjeto.textContent = "R$ " + valor;
        descricaoProjeto.textContent = descricao;

        // adicionando o ID do projeto como um parâmetro na URL dos link "Alterar dados"
        var linkAlterar = document.getElementById("alterarProjeto");
        linkAlterar.href = "../../../pages/anunciante/alterar-projeto.php?id=" + currentProjectId;

        // adicionando o ID do projeto como um parâmetro na URL dos link "Candidatos inscritos"
        var linkCandidatos = document.getElementById("verCandidatos");
        linkCandidatos.href = "?projeto_id=" + currentProjectId;

        // abrindo o modal
        modal.style.display = "flex";
    }

    // função para fechar o modal
    function fecharModal() {
        var modal = document.getElementById("projectModal");
        modal.style.display = "none";
    }

    // <!--------------------------------------------------------------------------------------------------------------->
    // função para abrir o modal de confirmação de exclusão
    function abrirModalExcluir(link) {
        var modal = document.getElementById("modalExcluir");
        var modalProjeto = document.getElementById("projectModal");

        // fechando modal dos projetos
        modalProjeto.style.display = "none";

        var confirmDeleteButton = document.getElementById("confirmDeleteButton");
        currentProjectId = link.getAttribute("data-id"); // Obtendo o ID do projeto a partir do link

        // Configurando o link de exclusão com o ID correto do projeto
        confirmDeleteButton.href = "../../php/anunciante/script_excluirProjeto.php?id=" + currentProjectId;

        // Abrindo o modal de confirmação de exclusão
        modal.style.display = "flex";
    }

    // função para fechar o modal de excluir
    function fecharModalExcluir() {
        var modal = document.getElementById("modalExcluir");
        var modalProjeto = document.getElementById("projectModal");

        modal.style.display = "none"; //fechando modal de exclusão
        modalProjeto.style.display = "flex"; //mostrando de volta o modal do projeto
    }

    // <!--------------------------------------------------------------------------------------------------------------->
    // função para abrir o modal de candidatos
    function abrirModalCandidatos() {
        var modal = document.getElementById("modalCandidatos");
        var modalProjeto = document.getElementById("projectModal");

        // Fechando o modal do projeto
        modalProjeto.style.display = "none";

        // Abrindo o modal de candidatos
        modal.style.display = "flex";
    }

    // função para fechar o modal de candidatos e reabrir o modal de informações do projeto
    function fecharModalCandidatos() {
        var modalCandidatos = document.getElementById("modalCandidatos");
        var modalProjeto = document.getElementById("projectModal");

        modalCandidatos.style.display = "none"; // fechando o modal de candidatos
        modalProjeto.style.display = "flex"; // mostrando de volta o modal do projeto

        // obtendo o ID do projeto a partir do link "Ver mais" antes de fechar o modal de candidatos
        var linkVerMais = document.querySelector(".verMais");
        currentProjectId = linkVerMais.getAttribute("data-id");

        // obtendo as informações do projeto a partir dos atributos de dados do botão "Ver mais"
        var titulo = document.querySelector(".verMais[data-id='" + currentProjectId + "']").getAttribute("data-titulo");
        var categoria = document.querySelector(".verMais[data-id='" + currentProjectId + "']").getAttribute("data-categoria");
        var formato = document.querySelector(".verMais[data-id='" + currentProjectId + "']").getAttribute("data-formato");
        var valor = document.querySelector(".verMais[data-id='" + currentProjectId + "']").getAttribute("data-valor");
        var descricao = document.querySelector(".verMais[data-id='" + currentProjectId + "']").getAttribute("data-descricao");

        // atualizando as informações do projeto no modal
        document.getElementById("titulo-projeto").textContent = titulo;
        document.getElementById("categoria-projeto").textContent = categoria;
        document.getElementById("formato-projeto").textContent = formato;
        document.getElementById("valor-projeto").textContent = "R$ " + valor;
        document.getElementById("descricao-projeto").textContent = descricao;

        // atualizando a URL da página para remover o parâmetro do ID do projeto --> restaurando ao padrão da url
        history.replaceState({}, document.title, window.location.pathname);
    }

    // <!--------------------------------------------------------------------------------------------------------------->
    // atribuindo eventos de clique aos ícones de fechamento
    document.getElementById("closeIcon").addEventListener("click", fecharModal);
    document.getElementById("verCandidatos").addEventListener("click", abrirModalCandidatos);

    var closeIconExcluir = document.querySelectorAll(".closeIconExcluir");
    closeIconExcluir.forEach(function(closeIconExcluir) {
        closeIconExcluir.addEventListener("click", fecharModalExcluir);
    });

    var closeIconCandidatos = document.querySelectorAll(".closeIconCandidatos");
    closeIconCandidatos.forEach(function(closeIconCandidatos) {
        closeIconCandidatos.addEventListener("click", fecharModalCandidatos);
    });
</script>