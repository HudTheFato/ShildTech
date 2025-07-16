<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Moradores - ShieldTech</title>
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
                        <a href="cadastro_moradores.php">Moradores</a>
                        <a href="../funcionarios/cadastro_funcionarios.php">Funcionários</a>
                        <a href="../cargos/cadastro_cargos.php">Cargos</a>
                        
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Moradores Cadastrados</h2>
        
        <div class="actions-bar">
            <a href="cadastro_moradores.php" class="btn-primary">
                <i class="fas fa-plus"></i> Novo Morador
            </a>
        </div>

        <section class="lista-section">
            <div class="tabela-container">
                <table class="tabela-relatorio">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th>Bloco/Torre</th>
                            <th>Andar</th>
                            <th>Veículo</th>
                            <th>Animais</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include("../../conectarbd.php");
                        $selecionar = mysqli_query($conn, "SELECT * FROM tb_moradores ORDER BY nome");
                        
                        if (mysqli_num_rows($selecionar) > 0) {
                            while ($campo = mysqli_fetch_array($selecionar)) {
                                echo "<tr>";
                                echo "<td>" . $campo["id_moradores"] . "</td>";
                                echo "<td>" . $campo["nome"] . "</td>";
                                echo "<td>" . $campo["cpf"] . "</td>";
                                echo "<td>" . $campo["telefone"] . "</td>";
                                echo "<td>" . ($campo["email"] ? $campo["email"] : "Não informado") . "</td>";
                                echo "<td>" . $campo["bloco"] . "/" . $campo["torre"] . "</td>";
                                echo "<td>" . $campo["andar"] . "</td>";
                                echo "<td>" . ($campo["veiculo"] ? $campo["veiculo"] : "Não possui") . "</td>";
                                echo "<td>" . ($campo["animais"] ? $campo["animais"] : "Não possui") . "</td>";
                                echo "<td><span class='status-ativo'>" . ($campo["status"] ? $campo["status"] : "Ativo") . "</span></td>";
                                echo "<td class='acoes'>";
                                echo "<a href='editar_morador.php?id=" . $campo["id_moradores"] . "' class='btn-editar'>";
                                echo "<i class='fas fa-edit'></i> Editar</a>";
                                echo "<a href='excluir_morador.php?id=" . $campo["id_moradores"] . "' class='btn-excluir' onclick='return confirm(\"Tem certeza que deseja excluir este morador?\")'>";
                                echo "<i class='fas fa-trash'></i> Excluir</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='11' style='text-align: center;'>Nenhum morador cadastrado</td></tr>";
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