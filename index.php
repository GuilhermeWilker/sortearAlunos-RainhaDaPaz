<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribuição de Alunos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #007BFF;
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1em;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .nav form {
            display: flex;
            gap: 1em;
            align-items: center;
        }

        .nav input[type="file"] {
            padding: 5px;
        }

        .nav button {
            background-color: #007BFF;
            border: none;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .nav button:hover {
            background-color: #0056b3;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .sala {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .sala h2 {
            margin-top: 0;
            color: #007BFF;
        }

        .alunos {
            list-style-type: none;
            padding-left: 0;
        }

        .alunos li {
            margin: 4px 0;
            font-size: 14px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5em;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group input[type="number"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Distribuição de Alunos</h1>

        <nav class="nav">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="num_salas">Número de Salas</label>
                    <input type="number" name="num_salas" id="num_salas" min="1" value="8" />
                </div>
                <input type="file" name="upload_arquivo" />
                <button type="submit" name="upload">Gerar Salas</button>
            </form>

            <form action="gerar_pdf.php" method="post">
                <button type="submit" name="download_pdf">Baixar PDF</button>
            </form>

            <form action="gerar_excel.php" method="post">
                <button type="submit" name="download_excel">Baixar Excel</button>
            </form>
        </nav>

        <?php
        if (isset($_FILES['upload_arquivo']) && $_FILES['upload_arquivo']['error'] == UPLOAD_ERR_OK) {
            $arquivo = $_FILES['upload_arquivo']['tmp_name'];
            $alunos = file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            // Embaralhar alunos
            shuffle($alunos);

            $num_salas = isset($_POST['num_salas']) ? intval($_POST['num_salas']) : 8;
            $alunos_selecionados = array_slice($alunos, 0, 300);

            $salas = array_fill(0, $num_salas, []);

            function distribuirAlunos($alunos, &$salas, $num_salas)
            {
                foreach ($alunos as $i => $aluno) {
                    $sala_index = $i % $num_salas;
                    $salas[$sala_index][] = $aluno;
                }
            }

            distribuirAlunos($alunos_selecionados, $salas, $num_salas);

            session_start();
            $_SESSION['salas'] = $salas;

            foreach ($salas as $index => $sala) {
                echo "<div class='sala'>";
                echo "<h2>Sala " . ($index + 1) . ": " . count($sala) . " alunos</h2>";
                echo "<ul class='alunos'>";

                $totalAlunos = count($sala);
                foreach ($sala as $i => $aluno) {
                    if ($i == $totalAlunos - 1) {
                        echo "<li>$aluno.</li>"; // Último aluno com ponto final
                    } else {
                        echo "<li>$aluno,</li>";
                    }
                }

                echo "</ul>";
                echo "</div>";
            }
        }
        ?>

    </div>
</body>

</html>