<form id="formAtualizarSenha" action="../../php/aluno/script_atualizarSenha.php" method="post">
    <!-- input de email - apenas de leitura -->
    <div class="form-item">
        <input type="email" name="email" id="emailCandidato-input" class="emailCandidato-input" autocomplete="email" value="<?php echo $email_aluno ?>" readonly />
        <label for="emailCandidato-input" class="input-fill">E-mail*</label>
    </div>

    <div class="row">
        <!-- input de senha atual -->
        <div class="form-item">
            <input type="password" name="senha_atual" id="senhaAtual-input" required>
            <label for="senhaAtual-input">Senha atual*</label>
            <span data-target="senhaAtual-input" class="toggle-senha password material-symbols-outlined">visibility</span>
        </div>

        <!-- input de senha nova -->
        <div class="form-item">
            <input type="password" name="nova_senha" id="senhaNova-input" required>
            <label for="senhaNova-input">Nova senha*</label>
            <span data-target="senhaNova-input" class="toggle-senha password material-symbols-outlined">visibility</span>
        </div>
    </div>

    <div class="btn-wrapper">
        <button type="submit" class="btn small-btn">Salvar</button>
    </div>
</form>

<button class="btn small-btn cancel-btn" id="cancelBtn">Cancelar</button>

<!-- Modal de Alerta de Campo Vazio -->
<div id="confirmVazio" class="modal modal-confirm" style="display: none;">
    <div class="modal-content">
        <span class="modal-close close-icon material-symbols-outlined" onclick="fecharModalVazio()"> close </span>
        <span class="icon material-symbols-outlined"> cancel </span>
        <h3>Campos Vazios!</h3>
        <p>Por favor, preencha todos os campos obrigatórios!</p>
        <div class="btn-wrapper">
            <button class="btn small-btn outline-btn modal-close" onclick="fecharModalVazio()">Fechar</button>
        </div>
    </div>
</div>

<!-- Modal de confirmação de atualização de dados de acesso -->
<div class='modal modal-delete' id="modalDadosAcesso">
    <div class='modal-content'>
        <a href="#" onclick="fecharModalDados()"><span class='modal-close close-icon material-symbols-outlined'> close </span></a>
        <span class='icon material-symbols-outlined'> help </span>
        <h3>Tem certeza?</h3>
        <p>Seus dados de acesso serão atualizados em nossa base de dados. Tem certeza que deseja continuar?</p>
        <div class='btn-wrapper'>
            <button class='btn small-btn cancel-btn modal-close' onclick="fecharModalDados()">Cancelar</button>
            <button class='btn small-btn' id="confirmButton">Sim</button>
        </div>
    </div>
</div>

<script>
    // Função para validar o formulário
    function validarFormulario() {
        var camposObrigatorios = document.querySelectorAll("input[required]");
        var camposPreenchidos = true;

        camposObrigatorios.forEach(function(campo) {
            if (campo.value.trim() === '') {
                camposPreenchidos = false;
            }
        });

        return camposPreenchidos;
    }

    // Função para abrir a modal de alerta de campo vazio
    function abrirModalVazio() {
        var modal = document.getElementById("confirmVazio");
        modal.style.display = "flex";
    }

    // Função para fechar a modal de alerta de campo vazio
    function fecharModalVazio() {
        var modal = document.getElementById("confirmVazio");
        modal.style.display = "none";
    }

    // Função para abrir o modal de confirmação de atualização de dados
    function abrirModalAcessoDados() {
        // Validar o formulário antes de abrir o modal
        if (!validarFormulario()) {
            abrirModalVazio();
            return;
        }

        var modal = document.getElementById("modalDadosAcesso");
        modal.style.display = "flex";
    }

    // Função para lidar com a submissão do formulário
    function submeterFormulario() {
        // Validar o formulário antes de submetê-lo
        if (!validarFormulario()) {
            abrirModalVazio();
            return;
        }

        var form = document.getElementById("formAtualizarSenha");
        form.submit();
    }

    // Adicionar evento de clique ao botão de confirmação dentro do modal
    var confirmButton = document.getElementById("confirmButton");
    confirmButton.addEventListener("click", function() {
        submeterFormulario();
    });

    // Função para fechar o modal
    function fecharModalDados() {
        var modal = document.getElementById("modalDadosAcesso");
        modal.style.display = "none";
    }

    // Esconder e mostrar senha dos inputs password
    var toggleSenhaIcons = document.querySelectorAll(".toggle-senha");
    toggleSenhaIcons.forEach(function(icon) {
        icon.addEventListener("click", function() {
            var targetId = icon.getAttribute("data-target");
            var senhaInput = document.getElementById(targetId);

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
