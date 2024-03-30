<form action="">
    <!-- input de email - apenas de leitura -->
    <div class="form-item">
        <input type="email" name="email" id="email-input" readonly>
        <label for="email-input" class="email-input-label">E-mail*</label>
    </div>

    <div class="row">
        <!-- input de senha atual -->
        <div class="form-item">
            <input type="password" name="senha" id="senhaAtual-input">
            <label for="senhaAtual-input">Senha atual*</label>
            <span data-target="senhaAtual-input" class="toggle-senha password material-symbols-outlined">visibility</span>
        </div>

        <!-- input de senha nova -->
        <div class="form-item">
            <input type="password" name="senha" id="senhaNova-input">
            <label for="senhaNova-input">Nova senha*</label>
            <span data-target="senhaNova-input" class="toggle-senha password material-symbols-outlined">visibility</span>
        </div>

    </div>

    <div class="btn-wrapper">
        <a href="#" onclick="abrirModalAcessoDados(this)" class="btn small-btn">Salvar</a>
    </div>
</form>

<button class="btn small-btn cancel-btn" id="cancelBtn">Cancelar</button>

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

<!-- modal de confirmação de atualização de dados de acesso -->
<div class='modal modal-delete' id="modalDadosAcesso">
    <div class='modal-content'>
        <a href="#" onclick="fecharModal(this)"><span class='modal-close close-icon material-symbols-outlined'> close </span></a>
        <span class='icon material-symbols-outlined'> help </span>
        <h3>Tem certeza?</h3>
        <p>Seus dados de acesso serão atualizados em nossa base de dados. Tem certeza que deseja continuar?</p>
        <div class='btn-wrapper'>
            <a href='#' onclick="fecharModal(this)" class='btn small-btn cancel-btn modal-close'>Cancelar</a>
            <a href='' id="confirmDeleteButton" data-id="<?php echo $projeto['id'] ?>" class='btn small-btn'>Sim</a>
        </div>
    </div>
</div>

<script>
    // função para abrir o modal de confirmação de exclusão
    function abrirModalAcessoDados(link) {
        var modal = document.getElementById("modalDadosAcesso");


        // var confirmDeleteButton = document.getElementById("confirmDeleteButton");
        // currentProjectId = link.getAttribute("data-id"); // Obtendo o ID do projeto a partir do link

        // Configurando o link de exclusão com o ID correto do projeto
        // confirmDeleteButton.href = "../../php/anunciante/script_excluirProjeto.php?id=" + currentProjectId;

        // Abrindo o modal de confirmação de exclusão
        modal.style.display = "flex";
    }

    // função para fechar o modal
    function fecharModal() {
        var modal = document.getElementById("modalDadosAcesso");
        modal.style.display = "none";
    }

    // esconder e mostrar senha dos inputs password
    // selecionando para todos os elementos com a classe toggle-senha
    var toggleSenhaIcons = document.querySelectorAll(".toggle-senha");

    // adicionando um evento de clique a cada ícone de alternância de senha
    toggleSenhaIcons.forEach(function(icon) {
        icon.addEventListener("click", function() {
            // obtendo o ID do input de senha alvo a partir do atributo data-target (senhaAtual-input e senhaNova-input)
            var targetId = icon.getAttribute("data-target");
            // recebe o id vindo do data-target
            var senhaInput = document.getElementById(targetId);

            // alternando entre os tipos de input (password/text)
            if (senhaInput.type === "password") {
                senhaInput.type = "text";
                icon.textContent = "visibility_off";
            } else {
                senhaInput.type = "password";
                icon.textContent = "visibility";
            }
        });
    });
</script>