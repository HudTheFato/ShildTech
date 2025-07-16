<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Visitante - ShieldTech</title>
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
        $nome_visitante = mysqli_real_escape_string($conn, $_POST["nome_visitante"]);
        $num_documento = mysqli_real_escape_string($conn, $_POST["num_documento"]);
        $telefone = mysqli_real_escape_string($conn, $_POST["telefone"]);
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $data_nascimento = mysqli_real_escape_string($conn, $_POST["data_nascimento"]);
        $foto = mysqli_real_escape_string($conn, $_POST["foto"]);
        
        $sql = "UPDATE tb_visitantes SET 
                nome_visitante='$nome_visitante', num_documento='$num_documento', 
                telefone='$telefone', email='$email', data_nascimento='$data_nascimento', foto='$foto' 
                WHERE id_visitantes=$id";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Visitante atualizado com sucesso!'); window.location = 'consultar_visitantes.php';</script>";
        } else {
            echo "<script>alert('Erro ao atualizar visitante: " . mysqli_error($conn) . "');</script>";
        }
    }
    
    // Buscar dados do visitante
    $selecionar = mysqli_query($conn, "SELECT * FROM tb_visitantes WHERE id_visitantes=$id");
    $campo = mysqli_fetch_array($selecionar);
    ?>

    <header>
        <nav>
            <div class="logo">
                <h1><i class="fas fa-shield"></i> ShieldTech</h1>
            </div>
            <ul class="menu">
                <li><a href="../../index.php"><i class="fas fa-home"></i> Início</a></li>
                <li><a href="visitantes.php"><i class="fas fa-user-friends"></i> Visitantes</a></li>
                <li><a href="../relatorios/relatorios.php"><i class="fas fa-chart-bar"></i> Relatórios</a></li>
                <li class="dropdown">
                    <a href="#" class="dropbtn"><i class="fas fa-gear"></i> Cadastros</a>
                    <div class="dropdown-content">
                        <a href="../moradores/cadastro_moradores.php">Moradores</a>
                        <a href="../funcionarios/cadastro_funcionarios.php">Funcionários</a>
                        <a href="../cargos/cadastro_cargos.php">Cargos</a>
                        <a href="../animais/cadastro_animais.php">Animais</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Editar Visitante</h2>

        <section class="form-section">
            <h3>Alterar Dados do Visitante</h3>
            <form method="post" action="">
                <input type="hidden" name="id" value="<?= $campo["id_visitantes"] ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nome_visitante">Nome do Visitante:</label>
                        <input type="text" id="nome_visitante" name="nome_visitante" value="<?= $campo["nome_visitante"] ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="num_documento">Número do Documento:</label>
                       <div class="cpf-validation">
                           <input type="text" id="num_documento" name="num_documento" value="<?= $campo["num_documento"] ?>" required>
                           <span class="validation-icon" id="cpf-icon"></span>
                       </div>
                       <div class="cpf-error" id="cpf-error"></div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="telefone">Telefone:</label>
                        <input type="text" id="telefone" name="telefone" value="<?= $campo["telefone"] ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?= $campo["email"] ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="data_nascimento">Data de Nascimento:</label>
                        <input type="date" id="data_nascimento" name="data_nascimento" value="<?= $campo["data_nascimento"] ?>" required>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="foto">Foto (URL):</label>
                    <input type="text" id="foto" name="foto" value="<?= $campo["foto"] ?>">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                    <a href="consultar_visitantes.php" class="btn-secondary">
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
        // Configurar validação de CPF para edição de visitante
        document.addEventListener('DOMContentLoaded', () => {
            const visitanteId = <?= $campo["id_visitantes"] ?>;
            CPFValidator.setupCompleteValidation('num_documento', 'cpf-error', 'visitantes', visitanteId);
        });
    </script>
</body>
</html>