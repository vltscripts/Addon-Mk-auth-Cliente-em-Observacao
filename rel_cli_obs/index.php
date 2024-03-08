<?php
include('addons.class.php');

session_name('mka');
session_start();

if (!isset($_SESSION['MKA_Logado'])) {
    exit('Acesso negado... <a href="/admin/">Fazer Login</a>');
}

// Assuming $Manifest is defined somewhere before this code
$manifestTitle = isset($Manifest->{'name'}) ? $Manifest->{'name'} : '';
$manifestVersion = isset($Manifest->{'version'}) ? $Manifest->{'version'} : '';
?>

<!DOCTYPE html>
<?php
if (isset($_SESSION['MM_Usuario'])) {
    echo '<html lang="pt-BR">'; // Fix versão antiga MK-AUTH
} else {
    echo '<html lang="pt-BR" class="has-navbar-fixed-top">';
}
?>
<html lang="pt-BR">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <title>MK - AUTH :: <?= htmlspecialchars($manifestTitle . " - V " . $manifestVersion); ?></title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js">
    <link rel="stylesheet" href="../../estilos/mk-auth.css">
    <link rel="stylesheet" href="../../estilos/font-awesome.css">
    <script src="../../scripts/jquery.js"></script>
    <script src="../../scripts/mk-auth.js"></script>

    <style type="text/css">
        /* Estilos CSS personalizados */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            color: #333;
        }

        hr {
            width: 80%;
            margin: 20px auto;
            border-top: 1px solid #ccc;
        }

        form,
        .table-container,
        .client-count-container {
            width: 80%;
            margin: 0 auto;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="submit"],
        .clear-button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .clear-button {
            background-color: #e74c3c;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .clear-button:hover {
            background-color: #c0392b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            padding: 2px;
            text-align: left;
        }

        table th {
            background-color: #4caf50;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        h1 {
            color: #4caf50;
        }

        .client-count-container {
            text-align: center;
            margin-top: 10px;
        }

        .client-count {
            color: #4caf50;
            font-weight: bold;
        }

        .client-count.blue {
            color: #2196F3;
        }

        .nome_cliente a {
            color: blue;
            text-decoration: none;
            font-weight: bold;
        }

        .nome_cliente a:hover {
            text-decoration: underline;
        }

        .nome_cliente td {
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        .nome_cliente:nth-child(odd) {
            background-color: #FFFF99;
        }

        /* Adiciona uma borda direita na coluna "Nome do Cliente" */
        table th:first-child,
        table td:first-child {
            border-right: 1px solid #ddd;
        }

        /* Adiciona uma borda direita na coluna "Data remover" */
        table th:nth-child(2),
        table td:nth-child(2) {
            border-right: 1px solid #ddd;
        }
    </style>

    <script type="text/javascript">
        function clearSearch() {
            document.getElementById('search').value = '';
            document.forms['searchForm'].submit();
        }

        document.addEventListener("DOMContentLoaded", function() {
            var cells = document.querySelectorAll('.table-container tbody td.plan-name');
            cells.forEach(function(cell) {
                cell.addEventListener('click', function() {
                    var planName = this.innerText;
                    document.getElementById('search').value = planName;
                    document.title = 'Painel: ' + planName;
                    document.forms['searchForm'].submit();
                });
            });
        });

        function sortTable(columnIndex) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.querySelector('.table-container table');
            switching = true;
            dir = 'asc';
            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("TD")[columnIndex];
                    y = rows[i + 1].getElementsByTagName("TD")[columnIndex];
                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount++;
                } else {
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }
    </script>

</head>

<body>
    <?php include('../../topo.php'); ?>

    <nav class="breadcrumb has-bullet-separator is-centered" aria-label="breadcrumbs">
        <ul>
            <li><a href="#"> ADDON</a></li>
            <li class="is-active">
                <a href="#" aria-current="page"> <?php echo htmlspecialchars($manifestTitle . " - V " . $manifestVersion); ?> </a>
            </li>
        </ul>
    </nav>

    <?php include('config.php'); ?>

    <?php
    if ($acesso_permitido) {
        // Formulário Atualizado com Funcionalidade de Busca
    ?>
        <form id="searchForm" method="GET">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 10px;">
                <div style="width: 60%; margin-right: 10px;">
                    <label for="search" style="font-weight: bold; margin-bottom: 5px;">Buscar Cliente:</label>
                    <input type="text" id="search" name="search" placeholder="Digite o Nome do Cliente" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc;">
                </div>
                <div style="display: flex; align-items: flex-end;">
                    <input type="submit" value="Buscar" style="padding: 10px; border: 1px solid #4caf50; background-color: #4caf50; color: white; font-weight: bold; cursor: pointer; border-radius: 5px; margin-right: 10px;">
                    <button type="button" onclick="clearSearch()" class="clear-button" style="padding: 10px; border: 1px solid #e74c3c; background-color: #e74c3c; color: white; font-weight: bold; cursor: pointer; border-radius: 5px; margin-right: 10px;">Limpar</button>
                    <button type="button" onclick="sortTable(1)" class="clear-button sort-button-1" style="padding: 10px; border: 1px solid #4336f4; background-color: #4336f4; color: white; font-weight: bold; cursor: pointer; border-radius: 5px;">Ordenar</button>
                </div>
            </div>
        </form>

        <?php
        // Dados de conexão com o banco de dados já estão em config.php
        // Consulta SQL para obter a quantidade de clientes sem carne
        $countQuery = "SELECT COUNT(c.login) AS client_count, SUM(IF(c.tit_vencidos > 0, c.tit_vencidos, 0)) AS overdue_count FROM sis_cliente c WHERE c.cli_ativado = 's' AND c.observacao = 'sim'";

        if (!empty($_GET['search'])) {
            $countQuery .= " AND (c.login LIKE ? OR c.nome LIKE ?)";
        }

        $stmt = mysqli_prepare($link, $countQuery);

        if (!empty($_GET['search'])) {
            $search = '%' . mysqli_real_escape_string($link, $_GET['search']) . '%';
            mysqli_stmt_bind_param($stmt, "ss", $search, $search);
        }

        mysqli_stmt_execute($stmt);
        $countResult = mysqli_stmt_get_result($stmt);

        if ($countResult) {
            $countRow = mysqli_fetch_assoc($countResult);
            $clientCount = $countRow['client_count'];
            $overdueCount = $countRow['overdue_count']; // Nova variável para contagem de boletos vencidos

            echo "<div class='client-count-container'><p class='client-count blue'>Clientes em Observação: $clientCount</p></div>";
        } else {
            echo "<div class='client-count-container'><p class='client-count blue'>Erro ao obter a quantidade de clientes</p></div>";
        }

        // Tabela: Nomes dos Clientes com Logins Lado a Lado
        ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nome do Cliente</th>
                        <th>Data remover</th>
                        <th>Boletos Vencidos</th> <!-- Adicionando a coluna Boletos Vencidos -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Adicione a condição de busca, se houver
                    $searchCondition = '';
                    if (!empty($_GET['search'])) {
                        $search = mysqli_real_escape_string($link, $_GET['search']);
                        $searchCondition = " AND (c.login LIKE '%$search%' OR c.nome LIKE '%$search%')";
                    }

                    // Consulta SQL para obter os clientes em observação com data de remoção
                    $query = "SELECT c.uuid_cliente, c.nome, c.rem_obs, c.tit_vencidos
                              FROM sis_cliente c
                              WHERE c.cli_ativado = 's' AND c.observacao = 'sim'"
                        . $searchCondition .
                        " ORDER BY c.rem_obs DESC"; // Ordenar por data de remoção em ordem decrescente

                    // Execute a consulta
                    $result = mysqli_query($link, $query);

                    // Verifique se a consulta foi bem-sucedida
                    if ($result) {
                        // Exiba os resultados da consulta SQL
                        $rowNumber = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $nome_por_num_titulo = "Nome do Cliente: " . $row['nome'] . " - UUID: " . $row['uuid_cliente'];

                            // Adiciona a classe 'nome_cliente' e 'highlight' (para linhas ímpares) alternadamente
                            $rowNumber++;
                            $nomeClienteClass = ($rowNumber % 2 == 0) ? 'nome_cliente' : 'nome_cliente highlight';

                            // Adiciona o link apenas no campo de nome do cliente
                            echo "<tr class='$nomeClienteClass'>";
							
							// Nome do Cliente	
                            echo "<td style='position: relative;'>";
                            echo "<img src='img/icon_ativo.png' alt='Ícone de Nome' width='25' height='25' style='position: absolute; left: 0; top: 50%; transform: translateY(-50%);'> ";
                            echo "<a href='../../cliente_det.hhvm?uuid=" . $row['uuid_cliente'] . "' target='_blank' title='VER CLIENTE: $nome_por_num_titulo'>" . $row['nome'] . "</a>";
                            echo "</td>";
							
							// Data remover	
                            echo "<td style='border: 1px solid #ddd; padding: 1px; text-align: center; color: #e61515; font-weight: bold; position: relative;'>";
                            echo "<img src='img/calendario.png' alt='Ícone de Valor' width='20' height='20' style='position: absolute; left: 0; top: 50%; transform: translateY(-50%);'> ";
                            echo ($row['rem_obs'] ? date('d/m/Y', strtotime($row['rem_obs'])) : 'N/A');
                            echo "</td>";
                            
							// Titulos Vencidos
                            echo "<td style='border: 1px solid #ddd; padding: 1px; text-align: center; color: #e61515; font-weight: bold; position: relative;'>";
                            echo "<img src='img/icon_boleto.png' alt='Ícone de Valor' width='20' height='20' style='position: absolute; left: 0; top: 50%; transform: translateY(-50%);'> ";
                            echo $row['tit_vencidos'];
							echo "</tr>";
							echo "</td>";
                        }
                    } else {
                        // Se a consulta falhar, exiba uma mensagem de erro
                        echo "<tr><td colspan='3'>Erro na consulta: " . mysqli_error($link) . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    <?php
    } else {
        echo "Acesso não permitido!";
    }
    ?>

    <?php include('../../baixo.php'); ?>

    <script src="../../menu.js.php"></script>
    <?php include('../../rodape.php'); ?>
</body>

</html>
