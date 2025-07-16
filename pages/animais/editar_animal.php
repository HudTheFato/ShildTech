<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Animal - ShieldTech</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php
    include("../../conectarbd.php");
    
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    
    // Processar formulário se foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = mysqli_real_escape_string($conn, $_POST["id"]);
        $nome = mysqli_real_escape_string($conn, $_POST["nome"]);
        $tipo = mysqli_real_escape_string($conn, $_POST["tipo"]);
        $porte = mysqli_real_escape_string($conn, $_POST["porte"]);
        $observacoes = mysqli_real_escape_string($conn, $_POST["observacoes"]);
        $id_morador = mysqli_real_escape_string($conn, $_POST["id_morador"]);
        
        $sql = "UPDATE tb_animais SET 
                nome='$nome', tipo='$tipo', porte='$porte', 
                observacoes='$observacoes', id_morador='$id_morador' 
                WHERE id_animais=$id";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Animal atualizado com sucesso!'); window.location = 'consultar_animais.php';</script>";
        } else {
            echo "<script>alert('Erro ao atualizar animal: " . mysqli_error($conn) . "');</script>";
        }
    }
    
    // Buscar dados do animal
    $selecionar = mysqli_query($conn, "SELECT * FROM tb_animais WHERE id_animais=$id");
    $campo = mysqli_fetch_array($selecionar);
    ?>

    <header>
        <nav>
            <div class="logo">
                <h1><i class="fas fa-shield"></i> ShieldTech</h1>
            </div>
            <ul class="menu">
                <li><a href="../../index.php"><i class="fas fa-home"></i> Início</a></li>
                <li><a href="../visitantes/visitantes.php"><i class="fas fa-user-friends"></i> Visitantes</a></li>
                <li><a href="../relatorios/relatorios.php"><i class="fas fa-chart-bar"></i> Relatórios</a></li>
                <li class="dropdown">
                    <a href="#" class="dropbtn"><i class="fas fa-gear"></i> Cadastros</a>
                    <div class="dropdown-content">
                        <a href="../moradores/cadastro_moradores.php">Moradores</a>
                        <a href="../funcionarios/cadastro_funcionarios.php">Funcionários</a>
                        <a href="../cargos/cadastro_cargos.php">Cargos</a>
                        <a href="cadastro_animais.php">Animais</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Editar Animal</h2>

        <section class="form-section">
            <h3>Alterar Dados do Animal</h3>
            <form method="post" action="">
                <input type="hidden" name="id" value="<?= $campo["id_animais"] ?>">
                
                <div class="form-group">
                    <label for="nome">Nome do Animal:</label>
                    <input type="text" id="nome" name="nome" value="<?= $campo["nome"] ?>" required>
                </div>

                <div class="form-group">
                    <label for="tipo">Tipo:</label>
                    <select id="tipo" name="tipo" required>
                        <option value="">Selecione o tipo</option>
                        <option value="Cão" <?= $campo["tipo"] == "Cão" ? "selected" : "" ?>>Cão</option>
                        <option value="Gato" <?= $campo["tipo"] == "Gato" ? "selected" : "" ?>>Gato</option>
                        <option value="Pássaro" <?= $campo["tipo"] == "Pássaro" ? "selected" : "" ?>>Pássaro</option>
                        <option value="Peixe" <?= $campo["tipo"] == "Peixe" ? "selected" : "" ?>>Peixe</option>
                        <option value="Outro" <?= $campo["tipo"] == "Outro" ? "selected" : "" ?>>Outro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="porte">Porte:</label>
                    <select id="porte" name="porte" required>
                        <option value="">Selecione o porte</option>
                        <option value="Pequeno" <?= $campo["porte"] == "Pequeno" ? "selected" : "" ?>>Pequeno</option>
                        <option value="Médio" <?= $campo["porte"] == "Médio" ? "selected" : "" ?>>Médio</option>
                        <option value="Grande" <?= $campo["porte"] == "Grande" ? "selected" : "" ?>>Grande</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_morador">Morador Responsável:</label>
                    <select id="id_morador" name="id_morador" required>
                        <option value="">Selecione um morador</option>
                        <?php
                        $moradores = mysqli_query($conn, "SELECT * FROM tb_moradores ORDER BY nome");
                        while ($morador = mysqli_fetch_array($moradores)) {
                            $selected = ($campo["id_morador"] == $morador["id_moradores"]) ? "selected" : "";
                            echo "<option value='" . $morador["id_moradores"] . "' $selected>" . $morador["nome"] . " - Bloco " . $morador["bloco"] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="observacoes">Observações:</label>
                    <textarea id="observacoes" name="observacoes" rows="3"><?= $campo["observacoes"] ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                    <a href="consultar_animais.php" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 ShieldTech. Todos os direitos reservados.</p>
    </footer>
</body>
</html>