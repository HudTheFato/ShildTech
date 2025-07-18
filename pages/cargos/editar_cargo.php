<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cargo - ShieldTech</title>
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
        $nome_cargo = mysqli_real_escape_string($conn, $_POST["nome_cargo"]);
        $descricao = mysqli_real_escape_string($conn, $_POST["descricao"]);
        $salario_base = mysqli_real_escape_string($conn, $_POST["salario_base"]);
        $carga_horaria = mysqli_real_escape_string($conn, $_POST["carga_horaria"]);
        
        $sql = "UPDATE tb_cargo SET 
                nome_cargo='$nome_cargo', descricao='$descricao', 
                salario_base='$salario_base', carga_horaria='$carga_horaria' 
                WHERE id_cargos=$id";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Cargo atualizado com sucesso!'); window.location = 'consultar_cargos.php';</script>";
        } else {
            echo "<script>alert('Erro ao atualizar cargo: " . mysqli_error($conn) . "');</script>";
        }
    }
    
    // Buscar dados do cargo
    $selecionar = mysqli_query($conn, "SELECT * FROM tb_cargo WHERE id_cargos=$id");
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
                <li><a href="../reservas/reservas.php"><i class="fas fa-calendar"></i> Reservas</a></li>
                <li class="dropdown">
                    <a href="#" class="dropbtn"><i class="fas fa-gear"></i> Cadastros</a>
                    <div class="dropdown-content">
                        <a href="cadastro_moradores.php">Moradores</a>
                        <a href="../funcionarios/cadastro_funcionarios.php">Funcionários</a>
                        <a href="../cargos/cadastro_cargos.php">Cargos</a>
                        <a href="../animais/cadastro_animais.php">Animais</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Editar Cargo</h2>

        <section class="form-section">
            <h3>Alterar Dados do Cargo</h3>
            <form method="post" action="">
                <input type="hidden" name="id" value="<?= $campo["id_cargos"] ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nome_cargo">Nome do Cargo:</label>
                        <input type="text" id="nome_cargo" name="nome_cargo" value="<?= $campo["nome_cargo"] ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="salario_base">Salário Base (R$):</label>
                        <input type="number" id="salario_base" name="salario_base" step="0.01" min="0" value="<?= $campo["salario_base"] ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="carga_horaria">Carga Horária (hh:mm):</label>
                        <input type="time" id="carga_horaria" name="carga_horaria" value="<?= $campo["carga_horaria"] ?>" required>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="descricao">Descrição:</label>
                    <textarea id="descricao" name="descricao" rows="3" required><?= $campo["descricao"] ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                    <a href="consultar_cargos.php" class="btn-secondary">
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