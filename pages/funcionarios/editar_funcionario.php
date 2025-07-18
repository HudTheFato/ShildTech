<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Funcionário - ShieldTech</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/cpf-validation.css">
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
        $cpf = mysqli_real_escape_string($conn, $_POST["cpf"]);
        $rg = mysqli_real_escape_string($conn, $_POST["rg"]);
        $data_nascimento = mysqli_real_escape_string($conn, $_POST["data_nascimento"]);
        $sexo = mysqli_real_escape_string($conn, $_POST["sexo"]);
        $telefone = mysqli_real_escape_string($conn, $_POST["telefone"]);
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $endereco = mysqli_real_escape_string($conn, $_POST["endereco"]);
        $funcao_cargo = mysqli_real_escape_string($conn, $_POST["funcao_cargo"]);
        $salario = mysqli_real_escape_string($conn, $_POST["salario"]);
        $data_admissao = mysqli_real_escape_string($conn, $_POST["data_admissao"]);
        
        $sql = "UPDATE tb_funcionarios SET 
                nome='$nome', cpf='$cpf', rg='$rg', data_nascimento='$data_nascimento', 
                sexo='$sexo', telefone='$telefone', email='$email', endereco='$endereco', 
                funcao_cargo='$funcao_cargo', salario='$salario', data_admissao='$data_admissao' 
                WHERE id_funcionarios=$id";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Funcionário atualizado com sucesso!'); window.location = 'consultar_funcionarios.php';</script>";
        } else {
            echo "<script>alert('Erro ao atualizar funcionário: " . mysqli_error($conn) . "');</script>";
        }
    }
    
    // Buscar dados do funcionário
    $selecionar = mysqli_query($conn, "SELECT * FROM tb_funcionarios WHERE id_funcionarios=$id");
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
        <h2>Editar Funcionário</h2>

        <section class="form-section">
            <h3>Alterar Dados do Funcionário</h3>
            <form method="post" action="">
                <input type="hidden" name="id" value="<?= $campo["id_funcionarios"] ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nome">Nome Completo:</label>
                        <input type="text" id="nome" name="nome" value="<?= $campo["nome"] ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="cpf">CPF:</label>
                       <div class="cpf-validation">
                           <input type="text" id="cpf" name="cpf" value="<?= $campo["cpf"] ?>" required>
                           <span class="validation-icon" id="cpf-icon"></span>
                       </div>
                       <div class="cpf-error" id="cpf-error"></div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="rg">RG:</label>
                        <input type="text" id="rg" name="rg" value="<?= $campo["rg"] ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="data_nascimento">Data de Nascimento:</label>
                        <input type="date" id="data_nascimento" name="data_nascimento" value="<?= $campo["data_nascimento"] ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="sexo">Sexo:</label>
                        <select id="sexo" name="sexo" required>
                            <option value="">Selecione</option>
                            <option value="Masculino" <?= $campo["sexo"] == "Masculino" ? "selected" : "" ?>>Masculino</option>
                            <option value="Feminino" <?= $campo["sexo"] == "Feminino" ? "selected" : "" ?>>Feminino</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="telefone">Telefone:</label>
                        <input type="tel" id="telefone" name="telefone" value="<?= $campo["telefone"] ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?= $campo["email"] ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="funcao_cargo">Função/Cargo:</label>
                        <select id="funcao_cargo" name="funcao_cargo" required>
                            <option value="">Selecione um cargo</option>
                            <?php
                            $cargos = mysqli_query($conn, "SELECT * FROM tb_cargo ORDER BY nome_cargo");
                            while ($cargo = mysqli_fetch_array($cargos)) {
                                $selected = ($campo["funcao_cargo"] == $cargo["nome_cargo"]) ? "selected" : "";
                                echo "<option value='" . $cargo["nome_cargo"] . "' $selected>" . $cargo["nome_cargo"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="salario">Salário:</label>
                        <input type="number" id="salario" name="salario" step="0.01" min="0" value="<?= $campo["salario"] ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="data_admissao">Data de Admissão:</label>
                        <input type="date" id="data_admissao" name="data_admissao" value="<?= $campo["data_admissao"] ?>" required>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="endereco">Endereço:</label>
                    <input type="text" id="endereco" name="endereco" value="<?= $campo["endereco"] ?>" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                    <a href="consultar_funcionarios.php" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 ShieldTech. Todos os direitos reservados.</p>
    </footer>
    
    <script src="../../js/cpf-validator.js"></script>
    <script>
        // Configurar validação de CPF para edição
        document.addEventListener('DOMContentLoaded', () => {
            const funcionarioId = <?= $campo["id_funcionarios"] ?>;
            CPFValidator.setupCompleteValidation('cpf', 'cpf-error', 'funcionarios', funcionarioId);
        });
    </script>
</body>
</html>