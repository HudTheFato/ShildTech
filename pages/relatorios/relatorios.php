<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - ShieldTech</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
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
        <h2>Relatórios do Sistema</h2>

        <section class="form-section">
            <h3>Filtros de Relatório</h3>
            <form method="get" action="">
                <div class="form-group">
                    <label for="tipo_relatorio">Tipo de Relatório:</label>
                    <select id="tipo_relatorio" name="tipo_relatorio">
                        <option value="moradores" <?= isset($_GET['tipo_relatorio']) && $_GET['tipo_relatorio'] == 'moradores' ? 'selected' : '' ?>>Moradores</option>
                        <option value="funcionarios" <?= isset($_GET['tipo_relatorio']) && $_GET['tipo_relatorio'] == 'funcionarios' ? 'selected' : '' ?>>Funcionários</option>
                        <option value="visitantes" <?= isset($_GET['tipo_relatorio']) && $_GET['tipo_relatorio'] == 'visitantes' ? 'selected' : '' ?>>Visitantes</option>
                        <option value="cargos" <?= isset($_GET['tipo_relatorio']) && $_GET['tipo_relatorio'] == 'cargos' ? 'selected' : '' ?>>Cargos</option>
                        <option value="resumo" <?= isset($_GET['tipo_relatorio']) && $_GET['tipo_relatorio'] == 'resumo' ? 'selected' : '' ?>>Resumo Geral</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="data_inicio">Data Início:</label>
                    <input type="date" id="data_inicio" name="data_inicio" value="<?= $_GET['data_inicio'] ?? '' ?>">
                </div>

                <div class="form-group">
                    <label for="data_fim">Data Fim:</label>
                    <input type="date" id="data_fim" name="data_fim" value="<?= $_GET['data_fim'] ?? '' ?>">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-search"></i> Gerar Relatório
                    </button>
                    <button type="button" onclick="window.print()" class="btn-secondary">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                </div>
            </form>
        </section>

        <?php
        include("../../conectarbd.php");
        
        if (isset($_GET['tipo_relatorio'])) {
            $tipo = $_GET['tipo_relatorio'];
            $data_inicio = $_GET['data_inicio'] ?? '';
            $data_fim = $_GET['data_fim'] ?? '';
            
            echo "<section class='resultado-section'>";
            echo "<h3>Resultado do Relatório</h3>";
            
            switch ($tipo) {
                case 'moradores':
                    $sql = "SELECT * FROM tb_moradores";
                    if ($data_inicio && $data_fim) {
                        $sql .= " WHERE data_cadastro BETWEEN '$data_inicio' AND '$data_fim'";
                    }
                    $sql .= " ORDER BY nome";
                    
                    $resultado = mysqli_query($conn, $sql);
                    $total = mysqli_num_rows($resultado);
                    
                    echo "<div class='relatorio-info'>";
                    echo "<p><strong>Total de Moradores:</strong> $total</p>";
                    echo "</div>";
                    
                    echo "<table class='tabela-relatorio'>";
                    echo "<thead>";
                    echo "<tr><th>Nome</th><th>CPF</th><th>Telefone</th><th>Bloco/Torre</th><th>Veículo</th><th>Animais</th></tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    
                    while ($row = mysqli_fetch_array($resultado)) {
                        echo "<tr>";
                        echo "<td>" . $row['nome'] . "</td>";
                        echo "<td>" . $row['cpf'] . "</td>";
                        echo "<td>" . $row['telefone'] . "</td>";
                        echo "<td>" . $row['bloco'] . "/" . $row['torre'] . "</td>";
                        echo "<td>" . ($row['veiculo'] ?: 'Não possui') . "</td>";
                        echo "<td>" . ($row['animais'] ?: 'Não possui') . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                    break;
                    
                case 'funcionarios':
                    $sql = "SELECT * FROM tb_funcionarios";
                    if ($data_inicio && $data_fim) {
                        $sql .= " WHERE data_admissao BETWEEN '$data_inicio' AND '$data_fim'";
                    }
                    $sql .= " ORDER BY nome";
                    
                    $resultado = mysqli_query($conn, $sql);
                    $total = mysqli_num_rows($resultado);
                    
                    echo "<div class='relatorio-info'>";
                    echo "<p><strong>Total de Funcionários:</strong> $total</p>";
                    echo "</div>";
                    
                    echo "<table class='tabela-relatorio'>";
                    echo "<thead>";
                    echo "<tr><th>Nome</th><th>CPF</th><th>Cargo</th><th>Salário</th><th>Data Admissão</th><th>Status</th></tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    
                    while ($row = mysqli_fetch_array($resultado)) {
                        echo "<tr>";
                        echo "<td>" . $row['nome'] . "</td>";
                        echo "<td>" . $row['cpf'] . "</td>";
                        echo "<td>" . $row['funcao_cargo'] . "</td>";
                        echo "<td>R$ " . number_format($row['salario'], 2, ',', '.') . "</td>";
                        echo "<td>" . date('d/m/Y', strtotime($row['data_admissao'])) . "</td>";
                        echo "<td>" . ($row['status'] ?: 'Ativo') . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                    break;
                    
                case 'visitantes':
                    $sql = "SELECT * FROM tb_visitantes";
                    if ($data_inicio && $data_fim) {
                        $sql .= " WHERE data_nascimento BETWEEN '$data_inicio' AND '$data_fim'";
                    }
                    $sql .= " ORDER BY nome_visitante";
                    
                    $resultado = mysqli_query($conn, $sql);
                    $total = mysqli_num_rows($resultado);
                    
                    echo "<div class='relatorio-info'>";
                    echo "<p><strong>Total de Visitantes:</strong> $total</p>";
                    echo "</div>";
                    
                    echo "<table class='tabela-relatorio'>";
                    echo "<thead>";
                    echo "<tr><th>Nome</th><th>Documento</th><th>Telefone</th><th>Email</th><th>Status</th></tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    
                    while ($row = mysqli_fetch_array($resultado)) {
                        echo "<tr>";
                        echo "<td>" . $row['nome_visitante'] . "</td>";
                        echo "<td>" . $row['num_documento'] . "</td>";
                        echo "<td>" . $row['telefone'] . "</td>";
                        echo "<td>" . ($row['email'] ?: 'Não informado') . "</td>";
                        echo "<td>" . ($row['status'] ?: 'Presente') . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                    break;
                    
                case 'cargos':
                    $sql = "SELECT * FROM tb_cargo ORDER BY nome_cargo";
                    
                    $resultado = mysqli_query($conn, $sql);
                    $total = mysqli_num_rows($resultado);
                    
                    echo "<div class='relatorio-info'>";
                    echo "<p><strong>Total de Cargos:</strong> $total</p>";
                    echo "</div>";
                    
                    echo "<table class='tabela-relatorio'>";
                    echo "<thead>";
                    echo "<tr><th>Nome</th><th>Descrição</th><th>Salário Base</th><th>Carga Horária</th></tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    
                    while ($row = mysqli_fetch_array($resultado)) {
                        echo "<tr>";
                        echo "<td>" . $row['nome_cargo'] . "</td>";
                        echo "<td>" . $row['descricao'] . "</td>";
                        echo "<td>R$ " . number_format($row['salario_base'], 2, ',', '.') . "</td>";
                        echo "<td>" . $row['carga_horaria'] . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                    break;
                    
                case 'resumo':
                    $moradores = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tb_moradores"));
                    $funcionarios = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tb_funcionarios"));
                    $visitantes = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tb_visitantes"));
                    $cargos = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tb_cargo"));
                    
                    echo "<div class='resumo-cards'>";
                    echo "<div class='card'>";
                    echo "<h3><i class='fas fa-users'></i> Moradores</h3>";
                    echo "<p>$moradores</p>";
                    echo "</div>";
                    echo "<div class='card'>";
                    echo "<h3><i class='fas fa-user-tie'></i> Funcionários</h3>";
                    echo "<p>$funcionarios</p>";
                    echo "</div>";
                    echo "<div class='card'>";
                    echo "<h3><i class='fas fa-user-friends'></i> Visitantes</h3>";
                    echo "<p>$visitantes</p>";
                    echo "</div>";
                    echo "<div class='card'>";
                    echo "<h3><i class='fas fa-briefcase'></i> Cargos</h3>";
                    echo "<p>$cargos</p>";
                    echo "</div>";
                    echo "</div>";
                    break;
            }
            echo "</section>";
        }
        ?>
    </main>

    <footer>
        <p>&copy; 2025 ShieldTech. Todos os direitos reservados.</p>
    </footer>
</body>
</html>