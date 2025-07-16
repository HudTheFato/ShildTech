<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Animais - ShieldTech</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php
    include("../../conectarbd.php");
    
    // Processar formulário se foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = mysqli_real_escape_string($conn, $_POST["nome"]);
        $tipo = mysqli_real_escape_string($conn, $_POST["tipo"]);
        $porte = mysqli_real_escape_string($conn, $_POST["porte"]);
        $observacoes = mysqli_real_escape_string($conn, $_POST["observacoes"]);
        $id_morador = mysqli_real_escape_string($conn, $_POST["id_morador"]);
        
        $sql = "INSERT INTO tb_animais (nome, tipo, porte, observacoes, id_morador) 
                VALUES ('$nome', '$tipo', '$porte', '$observacoes', '$id_morador')";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Animal cadastrado com sucesso!'); window.location = 'consultar_animais.php';</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar animal: " . mysqli_error($conn) . "');</script>";
        }
    }
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
        <h2>Gestão de Animais</h2>

        <div class="form-grid">
            <section class="form-section">
                <h3>Cadastro de Animal</h3>
                <form method="post" action="">
                    <div class="form-group">
                        <label for="nome">Nome do Animal:</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>

                    <div class="form-group">
                        <label for="tipo">Tipo:</label>
                        <select id="tipo" name="tipo" required>
                            <option value="">Selecione o tipo</option>
                            <option value="Cão">Cão</option>
                            <option value="Gato">Gato</option>
                            <option value="Pássaro">Pássaro</option>
                            <option value="Peixe">Peixe</option>
                            <option value="Outro">Outro</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="porte">Porte:</label>
                        <select id="porte" name="porte" required>
                            <option value="">Selecione o porte</option>
                            <option value="Pequeno">Pequeno</option>
                            <option value="Médio">Médio</option>
                            <option value="Grande">Grande</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="id_morador">Morador Responsável:</label>
                        <select id="id_morador" name="id_morador" required>
                            <option value="">Selecione um morador</option>
                            <?php
                            $moradores = mysqli_query($conn, "SELECT * FROM tb_moradores ORDER BY nome");
                            while ($morador = mysqli_fetch_array($moradores)) {
                                echo "<option value='" . $morador["id_moradores"] . "'>" . $morador["nome"] . " - Bloco " . $morador["bloco"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="observacoes">Observações:</label>
                        <textarea id="observacoes" name="observacoes" rows="3" placeholder="Informações adicionais sobre o animal"></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> Cadastrar Animal
                        </button>
                        <a href="consultar_animais.php" class="btn-secondary">
                            <i class="fas fa-list"></i> Ver Animais
                        </a>
                    </div>
                </form>
            </section>

            <section class="info-section">
                <h3>Informações sobre Animais</h3>
                <div class="info-cards">
                    <?php
                    $total_animais = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tb_animais"));
                    $caes = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tb_animais WHERE tipo = 'Cão'"));
                    $gatos = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tb_animais WHERE tipo = 'Gato'"));
                    $outros = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tb_animais WHERE tipo NOT IN ('Cão', 'Gato')"));
                    ?>
                    
                    <div class="info-card">
                        <i class="fas fa-paw"></i>
                        <h4>Total de Animais</h4>
                        <p><?= $total_animais ?></p>
                    </div>
                    
                    <div class="info-card">
                        <i class="fas fa-dog"></i>
                        <h4>Cães</h4>
                        <p><?= $caes ?></p>
                    </div>
                    
                    <div class="info-card">
                        <i class="fas fa-cat"></i>
                        <h4>Gatos</h4>
                        <p><?= $gatos ?></p>
                    </div>
                    
                    <div class="info-card">
                        <i class="fas fa-dove"></i>
                        <h4>Outros</h4>
                        <p><?= $outros ?></p>
                    </div>
                </div>

                <div class="recent-animals">
                    <h4>Últimos Animais Cadastrados</h4>
                    <div class="recent-list">
                        <?php
                        $recentes = mysqli_query($conn, "SELECT a.*, m.nome as nome_morador FROM tb_animais a
                                                        LEFT JOIN tb_moradores m ON a.id_morador = m.id_moradores 
                                                        ORDER BY a.id_animais DESC LIMIT 5");
                        while ($animal = mysqli_fetch_array($recentes)) {
                            echo "<div class='recent-item'>";
                            echo "<strong>" . $animal['nome'] . "</strong> (" . $animal['tipo'] . ")";
                            echo "<br><small>Responsável: " . $animal['nome_morador'] . "</small>";
                            echo "</div>";
                        }
                        ?>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 ShieldTech. Todos os direitos reservados.</p>
    </footer>
</body>
</html>