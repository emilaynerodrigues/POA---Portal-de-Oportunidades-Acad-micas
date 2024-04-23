<?php
$conn = conectar();

$id_aluno = $_SESSION['user_id'];

$sql = "SELECT descricao, escolaridade, curso_qualificacao FROM aluno WHERE id = $id_aluno";
$stmt = $conn->query($sql);

if (!$stmt) {
    die("Erro na consulta: " . $conn->errorInfo()[2]);
}

$dados_aluno = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="content-section">
    <!-- menu de opções -->
    <div class="section-options">
        <a href="#" class="section-link" data-target="sobreMim-section">Sobre mim</a>
        <a href="#" class="section-link" data-target="escolaridade-section">Escolaridade</a>
        <a href="#" class="section-link" data-target="cursos-section">Cursos de Qualificação</a>
    </div>

    <!-- aba - sobre mim -->
    <div id="sobreMim-section" class="content-sections" style="display: flex;">
        <h4>Fale sobre você</h4>

        <p>Neste campo, você pode compartilhar informações sobre seus interesses, hobbies, experiências profissionais e objetivos pessoais. Use este espaço para se expressar de forma autêntica e genuína, permitindo que outras pessoas conheçam um pouco mais sobre você.</p>

        <form action="../../php/aluno/script_atualizarDescricao.php" method="post">

            <textarea name="descricao" id="descricao-aluno" cols="30" rows="10"><?php echo $dados_aluno['descricao']; ?></textarea>

            <div class="btn-wrapper">
                <a href="#" class="btn small-btn" onclick="abrirModalPorfolio('atualizar_descricao')">Salvar descrição</a>
            </div>
        </form>
    </div>

    <!-- aba - escolaridade -->
    <div id="escolaridade-section" class="content-sections" style="display: none">
        <h4>Dados escolares</h4>

        <p>Neste campo, você pode compartilhar informações sobre sua trajetória escolar, incluindo suas experiências acadêmicas, conquistas, interesses educacionais e objetivos relacionados à sua formação. Use este espaço para fornecer uma visão mais completa de sua jornada educacional, permitindo que outras pessoas conheçam melhor seu histórico escolar e suas aspirações acadêmicas.</p>

        <form action="../../php/aluno/script_atualizarEscolaridade.php" method="post">

            <textarea name="escolaridade" id="escolaridade-aluno" cols="30" rows="10"><?php echo $dados_aluno['escolaridade']; ?></textarea>

            <div class="btn-wrapper">
                <a href="#" class="btn small-btn" onclick="abrirModalPorfolio('atualizar_escolaridade')">Salvar escolaridade</a>
            </div>
        </form>
    </div>

    <!-- aba - cursos -->
    <div id="cursos-section" class="content-sections" style="display: none">
        <h4>Cursos de Qualificação</h4>

        <p>Neste campo, você pode compartilhar informações sobre os cursos de qualificação que realizou, incluindo certificações, workshops, treinamentos e outras experiências de aprendizado que contribuíram para o desenvolvimento das suas habilidades e conhecimentos. Use este espaço para destacar suas conquistas e demonstrar seu comprometimento com o aprimoramento profissional.</p>

        <form action="../../php/aluno/script_atualizarCurso.php" method="post">

            <textarea name="curso_qualificacao" id="cursos-aluno" cols="30" rows="10"><?php echo $dados_aluno['curso_qualificacao']; ?></textarea>

            <div class="btn-wrapper">
                <a href="#" class="btn small-btn" onclick="abrirModalPorfolio('atualizar_curso')">Salvar cursos</a>

            </div>
        </form>
    </div>
</div>

<!-- Modal de confirmação de atualização de dados de acesso -->
<div class='modal modal-delete' id="modalPorfolio">
    <div class='modal-content'>
        <a href="#" onclick="fecharModal()"><span class='modal-close close-icon material-symbols-outlined'> close </span></a>
        <span class='icon material-symbols-outlined'> help </span>
        <h3>Tem certeza?</h3>
        <p>Seus dados de acesso serão atualizados em nossa base de dados. Tem certeza que deseja continuar?</p>
        <div class='btn-wrapper'>
            <a href='#' onclick="fecharModal()" class='btn small-btn cancel-btn modal-close'>Cancelar</a>
            <!-- Adicione um evento onclick para chamar a função confirmarAtualizacao() -->
            <a href='#' id="confirmButton" class='btn small-btn' onclick="confirmarAtualizacao()">Sim</a>
        </div>
    </div>
</div>

<script>
    var campoAtualizacao = ""; // Variável para armazenar a ação a ser atualizada

    // Função para abrir o modal de confirmação de atualização de dados
    function abrirModalPorfolio(acao) {
        // Armazenar a ação a ser atualizada
        campoAtualizacao = acao;

        var modal = document.getElementById("modalPorfolio");
        modal.style.display = "flex"; // Abrir o modal de confirmação de atualização de dados
    }

    // Função para fechar o modal
    function fecharModal() {
        var modal = document.getElementById("modalPorfolio");
        modal.style.display = "none"; // Fechar o modal de confirmação de atualização de dados
    }

    // Função para confirmar a atualização
    function confirmarAtualizacao() {
        // Submeter o formulário correspondente à ação
        var form;
        if (campoAtualizacao === 'atualizar_descricao') {
            form = document.querySelector("form[action='../../php/aluno/script_atualizarDescricao.php']");
        } else if (campoAtualizacao === 'atualizar_escolaridade') {
            form = document.querySelector("form[action='../../php/aluno/script_atualizarEscolaridade.php']");
        } else if (campoAtualizacao === 'atualizar_curso') {
            form = document.querySelector("form[action='../../php/aluno/script_atualizarCurso.php']");
        } else {
            return; // Ação inválida, não faz nada
        }

        // Adicionar um campo oculto para indicar qual ação está sendo atualizada
        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", "acao");
        hiddenField.setAttribute("value", campoAtualizacao);
        form.appendChild(hiddenField);
        // Enviar o formulário
        form.submit();
    }
</script>