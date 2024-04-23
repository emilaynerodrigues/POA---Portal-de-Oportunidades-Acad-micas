<form action="../../php/anunciante/script_atualizarSenha.php" method="post">
    <!-- input de email - apenas de leitura -->
    <div class="form-item">
        <input type="email" name="email" id="emailCandidato-input" class="emailCandidato-input" autocomplete="email" value="<?php echo $dados_anunciante['email']; ?>" readonly />
        <label for="emailCandidato-input" class="input-fill">E-mail*</label>
    </div>

    <div class="row">
        <!-- input de senha atual -->
        <div class="form-item">
            <input type="password" name="senha_atual" id="senhaAtual-input">
            <label for="senhaAtual-input">Senha atual*</label>
            <span data-target="senhaAtual-input" class="toggle-senha password material-symbols-outlined">visibility</span>
        </div>

        <!-- input de senha nova -->
        <div class="form-item">
            <input type="password" name="nova_senha" id="senhaNova-input">
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
            <a href='' id="confirmButton" class='btn small-btn'>Sim</a>
        </div>
    </div>
</div>

<script>
    // função para abrir o modal de confirmação de exclusão
    function abrirModalAcessoDados(link) {
        var modal = document.getElementById("modalDadosAcesso");

        // Configurando o link de confirmação com a função de submissão do formulário
        var confirmButton = modal.querySelector("#confirmButton");
        confirmButton.onclick = function(event) {
            event.preventDefault(); // Evita o comportamento padrão do link

            // Submete o formulário para atualizar a senha
            var form = document.querySelector("form");
            form.submit();
        };

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