<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Cargos - ShieldTech</title>
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
                        <a href="../funcionarios/cadastro_funcionarios.php">Funcionários</a>
                        <a href="cadastro_cargos.php">Cargos</a>
                        <a href="../animais/cadastro_animais.php">Animais</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Cargos Cadastrados</h2>
        
        <div class="actions-bar">
            <a href="cadastro_cargos.php" class="btn-primary">
                <i class="fas fa-plus"></i> Novo Cargo
            </a>
        </div>

        <section class="lista-section">
            <div class="tabela-container">
                <table class="tabela-relatorio">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome do Cargo</th>
                            <th>Descrição</th>
                            <th>Salário Base</th>
                            <th>Carga Horária</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include("../../conectarbd.php");
                        $selecionar = mysqli_query($conn, "SELECT * FROM tb_cargo ORDER BY nome_cargo");
                        
                        if (mysqli_num_rows($selecionar) > 0) {
                            while ($campo = mysqli_fetch_array($selecionar)) {
                                echo "<tr>";
                                echo "<td>" . $campo["id_cargos"] . "</td>";
                                echo "<td>" . $campo["nome_cargo"] . "</td>";
                                echo "<td>" . $campo["descricao"] . "</td>";
                                echo "<td>R$ " . number_format($campo["salario_base"], 2, ',', '.') . "</td>";
                                echo "<td>" . $campo["carga_horaria"] . "</td>";
                                echo "<td class='acoes'>";
                                echo "<a href='editar_cargo.php?id=" . $campo["id_cargos"] . "' class='btn-editar'>";
                                echo "<i class='fas fa-edit'></i> Editar</a>";
                                echo "<a href='excluir_cargo.php?id=" . $campo["id_cargos"] . "' class='btn-excluir' onclick='return confirm(\"Tem certeza que deseja excluir este cargo?\")'>";
                                echo "<i class='fas fa-trash'></i> Excluir</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align: center;'>Nenhum cargo cadastrado</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 ShieldTech. Todos os direitos reservados.</p>
    </footer>
</body>
</html>