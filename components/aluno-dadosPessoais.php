<?php
$conn = conectar();

$id_aluno = $_SESSION['user_id'];

$sql = "SELECT nome, cpf, genero, dataNasc, email, matricula, telefone, whatsapp, linkedin FROM aluno WHERE id = $id_aluno";
$stmt = $conn->query($sql);

if (!$stmt) {
    die("Erro na consulta: " . $conn->errorInfo()[2]);
}

$dados_aluno = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar se o campo CNPJ está vazio para decidir sobre o atributo readonly
if (empty($dados_aluno['cpf'])) {
    $cpfReadOnly = ""; // Se estiver vazio, não será readonly
    $cpfInput = "class=''";
} else {
    $cpfReadOnly = "readonly class='emailCandidato-input'"; // Caso contrário, será readonly
    $cpfInput = "input-fill";
}
?>

<div class="dados-wrapper">
    <!-- perfil -->
    <div class="profile-aside">
        <span>PERFIL</span>
        <div class="profile-icon profile-candidato">
            <span class="material-symbols-outlined">person</span>
        </div>
        <p class="matricula">Matrícula: <span><?php echo $dados_aluno['matricula']; ?></span></p>
        <p class="nome-candidato"><?php echo $dados_aluno['nome']; ?></p>
    </div>

    <!-- dados -->
    <div class="col">
        <!-- formulario de atualização dos dados do candidato -->
        <form class="form-1" action="../../php/aluno/script_dadosCandidato.php" method="post" id="form-dados">
            <!-- dados do candidato -->
            <h4>Dados do Candidato</h4>
            <!-- nome completo -->
            <div class="form-item">
                <input type="text" name="nome" id="nome-input" value="<?php echo $dados_aluno['nome']; ?>" required />
                <label for="nome-input">Nome completo*</label>
            </div>

            <!-- segunda linha -->
            <div class="row">
                <!-- cpf -->
                <div class="form-item">
                    <input type="text" name="cpf" id="cpf-input" value="<?php echo $dados_aluno['cpf']; ?>" required <?php echo $cpfReadOnly; ?> />
                    <label for="cpf-input" class="<?php echo $cpfInput; ?>">CPF*</label>
                </div>

                <!-- genero -->
                <div class="form-item select">
                    <select name="genero" id="genero-select" required>
                        <option value="" disabled selected hidden>Selecione um gênero</option>
                        <?php
                        $generos = array("Feminino" => "F", "Masculino" => "M");
                        foreach ($generos as $genero => $valor) {
                            echo "<option value='$valor'";
                            if (isset($dados_aluno['genero']) && $dados_aluno['genero'] == $valor) {
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
                    <input type="date" name="dataNasc" id="dataNasc-input" value="<?php echo $dados_aluno['dataNasc']; ?>" required />
                    <label for="dataNasc-input">Data de nascimento*</label>
                </div>

            </div>

            <!-- botões -->
            <div class="btn-wrapper">
                <!-- botões -->
                <div class="btn-wrapper">
                    <a href="#" onclick="abrirModalCandidato(event)" class="btn small-btn">Salvar</a>
                </div>

            </div>
        </form>

        <!-- formulario de atualização das formas de contato -->
        <form class="form-2" action="../../php/aluno/script_dadosContato.php" method="post" id="form-contato">
            <!-- formas de contato -->
            <h4>Formas de Contato</h4>
            <!-- email -->
            <div class="form-item">
                <input type="email" name="email" id="emailCandidato-input" class="emailCandidato-input" autocomplete="email" value="<?php echo $dados_aluno['email']; ?>" readonly />
                <label for="emailCandidato-input" class="input-fill">E-mail*</label>
            </div>


            <div class="row">
                <!-- telefone -->
                <div class="form-item">
                    <input type="text" name="telefone" id="telefone-input" value="<?php echo $dados_aluno['telefone']; ?>" required />
                    <label for="telefone-input">Telefone*</label>
                </div>
                <!-- whatsapp -->
                <div class="form-item">
                    <input type="text" name="whatsapp" id="whatsapp-input" value="<?php echo $dados_aluno['whatsapp']; ?>" required />
                    <label for="whatsapp-input">WhatsApp*</label>
                </div>
            </div>

            <!-- linkedin -->
            <div class="form-item">
                <input type="linkedin" name="linkedin" id="linkedin-input" value="<?php echo $dados_aluno['linkedin']; ?>" />
                <label for="linkedin-input">LinkedIn</label>
            </div>


            <!-- botões -->
            <div class="btn-wrapper">
                <a href="#" onclick="abrirModalContato(event)" class="btn small-btn">Salvar</a>

            </div>
        </form>
    </div>
</div>

<!-- modal de confirmação de atualização de dados -->
<div class='modal modal-delete' id="modalDadosCandidato">
    <div class='modal-content'>
        <span class='modal-close close-icon material-symbols-outlined closeModal'> close </span>
        <span class='icon material-symbols-outlined'> help </span>
        <h3>Tem certeza?</h3>
        <p>Seus dados de acesso serão atualizados em nossa base de dados. Tem certeza que deseja continuar?</p>
        <div class='btn-wrapper'>
            <a href='#' class='btn small-btn cancel-btn closeModal'>Cancelar</a>
            <a href='' id="confirmButton" class='btn small-btn'>Sim</a>
        </div>
    </div>
</div>


<script>
    // Função para fechar os modais
    function fecharModais() {
        var modais = document.querySelectorAll('.modal');
        modais.forEach(function(modal) {
            modal.style.display = "none";
        });
    }

    // Adicionando evento de clique nos elementos com a classe closeModal
    var closeButtons = document.querySelectorAll('.closeModal');
    closeButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            fecharModais();
        });
    });

    // Função para abrir o modal de confirmação de atualização de dados
    function abrirModalCandidato(event) {
        event.preventDefault(); // Impede o comportamento padrão do link

        var modal = document.getElementById("modalDadosCandidato");

        // Configurar o link de confirmação para enviar o formulário
        var confirmButton = modal.querySelector("#confirmButton");
        confirmButton.onclick = function(event) {
            event.preventDefault(); // Impede o comportamento padrão do link

            // Submeter o formulário para atualizar os dados do aluno
            var form = document.getElementById("form-dados");
            form.submit();
        };
        // Abrir o modal de confirmação de atualização de dados
        modal.style.display = "flex";
    }

    // Função para abrir o modal de confirmação de atualização de dados - contato
    function abrirModalContato(event) {
        event.preventDefault(); // Impede o comportamento padrão do link

        var modal = document.getElementById("modalDadosCandidato");

        // Configurar o link de confirmação para enviar o formulário
        var confirmButton = modal.querySelector("#confirmButton");
        confirmButton.onclick = function(event) {
            event.preventDefault(); // Impede o comportamento padrão do link

            // Submeter o formulário para atualizar os dados do aluno
            var form = document.getElementById("form-contato");
            form.submit();
        };
        // Abrir o modal de confirmação de atualização de dados
        modal.style.display = "flex";
    }
</script>