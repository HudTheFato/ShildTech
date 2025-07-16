<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Visitantes - ShieldTech</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php
    include("../../conectarbd.php");
    
    // Processar formulário se foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome_visitante = mysqli_real_escape_string($conn, $_POST["nome_visitante"]);
        $num_documento = mysqli_real_escape_string($conn, $_POST["num_documento"]);
        $telefone = mysqli_real_escape_string($conn, $_POST["telefone"]);
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $data_nascimento = mysqli_real_escape_string($conn, $_POST["data_nascimento"]);
        $foto = mysqli_real_escape_string($conn, $_POST["foto"]);
        $status = mysqli_real_escape_string($conn, $_POST["status"]);
        $sql = "INSERT INTO tb_visitantes (nome_visitante, num_documento, telefone, email, data_nascimento, foto,  status) 
                VALUES ('$nome_visitante', '$num_documento', '$telefone', '$email', '$data_nascimento', '$foto', '$status')";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Visitante registrado com sucesso!');</script>";
        } else {
            echo "<script>alert('Erro ao registrar visitante: " . mysqli_error($conn) . "');</script>";
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
        <h2>Controle de Visitantes</h2>
        
        <section class="form-section">
            <h3>Registro de Visitante</h3>
            <form method="post" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nome_visitante">Nome do Visitante:</label>
                        <input type="text" id="nome_visitante" name="nome_visitante" required>
                    </div>

                    <div class="form-group">
                        <label for="num_documento">Número do Documento:</label>
                        <input type="text" id="num_documento" name="num_documento" placeholder="CPF ou RG" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="telefone">Telefone:</label>
                        <input type="text" id="telefone" name="telefone" placeholder="(00) 00000-0000" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="data_nascimento">Data de Nascimento:</label>
                        <input type="date" id="data_nascimento" name="data_nascimento" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select id="status" name="status" required>
                            <option value="Presente">Presente</option>                   
                        </select>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="foto">Foto (URL):</label>
                    <input type="text" id="foto" name="foto" placeholder="URL da foto">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Registrar Entrada
                    </button>
                    <a href="consultar_visitantes.php" class="btn-secondary">
                        <i class="fas fa-list"></i> Ver Visitantes
                    </a>
                </div>
            </form>
        </section>

        <section class="lista-section">
            <h3>Visitantes Presentes</h3>
            <div class="tabela-container">
                <table class="tabela-relatorio">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Documento</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $selecionar = mysqli_query($conn, "SELECT * FROM tb_visitantes WHERE status = 'Presente' ORDER BY nome_visitante");
                        
                        if (mysqli_num_rows($selecionar) > 0) {
                            while ($campo = mysqli_fetch_array($selecionar)) {
                                echo "<tr>";
                                echo "<td>" . $campo["nome_visitante"] . "</td>";
                                echo "<td>" . $campo["num_documento"] . "</td>";
                                echo "<td>" . $campo["telefone"] . "</td>";
                                echo "<td>" . ($campo["email"] ? $campo["email"] : "Não informado") . "</td>";
                                echo "<td><span class='status-ativo'>" . $campo["status"] . "</span></td>";
                                echo "<td>";
                                echo "<a href='registrar_saida.php?id=" . $campo["id_visitantes"] . "' class='btn-saida'>";
                                echo "<i class='fas fa-sign-out-alt'></i> Registrar Saída</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align: center;'>Nenhum visitante presente</td></tr>";
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

    <script>
        // Máscara para telefone
        document.getElementById('telefone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
                e.target.value = value;
            }
        });
    </script>
</body>
</html>