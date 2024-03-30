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
        <span class="tag formato"><?php echo $projeto['formato']; ?></span>
        <span class="tag categoria"><?php echo $projeto['categoria']; ?></span>
        <span class="tag valor"><?php echo 'R$ ' . $projeto['valor']; ?></span>
    </div>
    <div class="descricao-projeto">
        <p class="descricao-texto"><?php echo $projeto['descricao']; ?></p>
    </div>
    <!-- Botão para abrir o modal -->
    <a href="#" onclick="abrirModal(this)" class="verMais" data-id="<?php echo $projeto['id']; ?>" data-titulo="<?php echo $projeto['titulo']; ?>" data-categoria="<?php echo $projeto['categoria']; ?>" data-formato="<?php echo $projeto['formato']; ?>" data-valor="<?php echo $projeto['valor']; ?>" data-descricao="<?php echo $projeto['descricao']; ?>">Ver mais</a>
</div>

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
                <p class="info-adicional">por<?php echo $projeto['id']; ?>
                    <!-- Nome do autor -->
                    <span id="autor-projeto" class="anunciante"></span>
                </p>
            </div>
        </div>

        <div class="tags-projeto">
            <!-- Formato de trabalho (remoto ou presencial) -->
            <span id="formato-projeto" class="tag formato"></span>

            <!-- Categoria -->
            <span id="categoria-projeto" class="tag categoria"></span>

            <!-- Valor -->
            <span id="valor-projeto" class="tag valor"></span>
        </div>

        <!-- Parágrafo para exibir a descrição do projeto -->
        <p id="descricao-projeto" class="descricao-texto"></p>

        <div class="btn-wrapper">
            <a class="btn normal-btn outline-btn">Candidatos inscritos</a>
            <a href="#" onclick="abrirModalExcluir(this)" class="btn small-btn delete-btn" data-id="<?php echo $projeto['id']; ?>">Excluir projeto</a>
            <a href="#" id="alterarProjeto" class="btn small-btn">Alterar dados</a>
        </div>
    </div>
</div>

<!-- modal de confirmação de exclusão de projeto -->
<div id="modalExcluir" class='modal modal-delete'>
    <div class='modal-content'>
        <a href='../../pages/anunciante/home.php'><span class='modal-close close-icon material-symbols-outlined'> close </span></a>
        <span class='icon material-symbols-outlined'> help </span>
        <h3>Seus dados serão perdidos!</h3>
        <p>Tem certeza que deseja excluir o projeto? Todos os dados serão perdidos!</p>
        <div class='btn-wrapper'>
            <a href='../../pages/anunciante/home.php' class='btn small-btn cancel-btn modal-close'>Cancelar</a>
            <a href='' id="confirmDeleteButton" data-id="<?php echo $projeto['id'] ?>" class='btn small-btn'>Excluir</a>
        </div>
    </div>
</div>

<script>
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
        descricaoProjeto.textContent = descricao + currentProjectId;

        // adicionando o ID do projeto como um parâmetro na URL dos link "Alterar dados"
        var linkAlterar = document.getElementById("alterarProjeto");
        linkAlterar.href = "../../../pages/anunciante/alterar-projeto.php?id=" + currentProjectId;

        // abrindo o modal
        modal.style.display = "flex";
    }

    // função para fechar o modal
    function fecharModal() {
        var modal = document.getElementById("projectModal");
        modal.style.display = "none";
    }

    // atribuindo evento de clique ao ícone de fechamento
    var closeIcon = document.getElementById("closeIcon");
    if (closeIcon) {
        closeIcon.addEventListener("click", fecharModal);
    }

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
</script>