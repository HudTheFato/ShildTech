<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Funcionários - ShieldTech</title>
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
                <li class="dropdown">
                    <a href="#" class="dropbtn"><i class="fas fa-gear"></i> Cadastros</a>
                    <div class="dropdown-content">
                        <a href="../moradores/cadastro_moradores.php">Moradores</a>
                        <a href="cadastro_funcionarios.php">Funcionários</a>
                        <a href="../cargos/cadastro_cargos.php">Cargos</a>
                        <a href="../animais/cadastro_animais.php">Animais</a>                    
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Funcionários Cadastrados</h2>
        
        <div class="actions-bar">
            <a href="cadastro_funcionarios.php" class="btn-primary">
                <i class="fas fa-plus"></i> Novo Funcionário
            </a>
        </div>

        <section class="lista-section">
            <div class="cards-funcionarios">
                <?php
                include("../../conectarbd.php");
                $selecionar = mysqli_query($conn, "SELECT * FROM tb_funcionarios ORDER BY nome");
                
                if (mysqli_num_rows($selecionar) > 0) {
                    while ($campo = mysqli_fetch_array($selecionar)) {
                        echo "<div class='card-funcionario'>";
                        echo "<div class='card-header'>";
                        echo "<i class='fas fa-user-tie'></i>";
                        echo "<h4>" . $campo["nome"] . "</h4>";
                        echo "</div>";
                        echo "<div class='card-body'>";
                        echo "<p><i class='fas fa-id-badge'></i> <strong>CPF:</strong> " . $campo["cpf"] . "</p>";
                        echo "<p><i class='fas fa-briefcase'></i> <strong>Cargo:</strong> " . $campo["funcao_cargo"] . "</p>";
                        echo "<p><i class='fas fa-calendar-alt'></i> <strong>Admissão:</strong> " . date('d/m/Y', strtotime($campo["data_admissao"])) . "</p>";
                        echo "<p><i class='fas fa-phone'></i> <strong>Telefone:</strong> " . $campo["telefone"] . "</p>";
                        echo "<p><i class='fas fa-envelope'></i> <strong>Email:</strong> " . $campo["email"] . "</p>";
                        echo "<p><i class='fas fa-dollar-sign'></i> <strong>Salário:</strong> R$ " . number_format($campo["salario"], 2, ',', '.') . "</p>";
                        echo "</div>";
                        echo "<div class='card-footer'>";
                        echo "<a href='editar_funcionario.php?id=" . $campo["id_funcionarios"] . "' class='btn-editar'>";
                        echo "<i class='fas fa-edit'></i> Editar</a>";
                        echo "<a href='excluir_funcionario.php?id=" . $campo["id_funcionarios"] . "' class='btn-remover' onclick='return confirm(\"Tem certeza que deseja excluir este funcionário?\")'>";
                        echo "<i class='fas fa-trash'></i> Remover</a>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='sem-registros'>";
                    echo "<i class='fas fa-user-slash'></i>";
                    echo "<p>Nenhum funcionário cadastrado</p>";
                    echo "</div>";
                }
                ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 ShieldTech. Todos os direitos reservados.</p>
    </footer>
</body>
</html>