<?php
session_start();
require 'config.php';
if (empty($_SESSION['mmnlogin'])) {
    header("Location: login.php");
    exit;
}
require 'cabecalho.php';
?>

<meta id="viewport" name="viewport" content="width=device-width, user-scalable=no">
<div class="container-fluid"><br>
    <div class="container-fluid">
        <form method="POST" class="form-inline row">
            <input class="form-control mr-sm-4" name="filtro_pesquisar" required type="text" placeholder="Pesquisar por nome ou telefone">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Pesquisar</button>
        </form>
    </div>

    <?php
    $filtro_pesquisar = isset($_POST['filtro_pesquisar']) ? $_POST['filtro_pesquisar'] : "";
    if ($filtro_pesquisar != "") {
        echo "<h6>Resultado com <strong>'$filtro_pesquisar'</strong></h6><br>";
    }

    $sql = "SELECT SUM(num_pessoas) AS total_pessoas FROM clientes WHERE (nome LIKE '%$filtro_pesquisar%' OR telefone LIKE '%$filtro_pesquisar%' OR telefone2 LIKE '%$filtro_pesquisar%') AND status != 0";
    $sql = $pdo->query($sql);
    $total_pessoas = 0;
    if ($sql->rowCount() > 0) {
        $total_pessoas = $sql->fetch()['total_pessoas'];
        echo "<h6>Total: $total_pessoas</h6><br>";
    }
    ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm table-warning">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Data</th>
                    <th>N° pessoas</th>
                    <th>Horário</th>
                    <th>Telefone</th>
                    <th>Telefone 2</th>
                    <th>Observações</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $sql = "SELECT * FROM clientes WHERE (nome LIKE '%$filtro_pesquisar%' OR telefone LIKE '%$filtro_pesquisar%' OR telefone2 LIKE '%$filtro_pesquisar%') AND status != 0 ORDER BY `data` ASC";
            $sql = $pdo->query($sql);
            if ($sql->rowCount() > 0) {
                foreach ($sql->fetchAll() as $clientes) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($clientes['nome']) . '</td>';
                    echo '<td>' . date("d/m/Y", strtotime($clientes['data'])) . '</td>';
                    echo '<td>' . htmlspecialchars($clientes['num_pessoas']) . '</td>';
                    echo '<td>' . htmlspecialchars($clientes['horario']) . '</td>';
                    echo '<td>' . htmlspecialchars($clientes['telefone']) . '</td>';
                    echo '<td>' . htmlspecialchars($clientes['telefone2']) . '</td>';
                    echo '<td class="obs-column"><div class="container">' . nl2br(htmlspecialchars($clientes['observacoes'])) . '</div></td>';

                    // Link para WhatsApp
                    $telefone = preg_replace('/[^0-9]/', '', $clientes['telefone']); 
                    $mensagem = "Olá " . ucfirst(strtolower($clientes['nome'])) . "! Estamos confirmando sua reserva para " . $clientes['num_pessoas'] . " pessoas no dia " . date("d/m/Y", strtotime($clientes['data'])) . " às " . $clientes['horario'] . " hs. Confirme com um OK.";
                    $link_whatsapp = "https://wa.me/55$telefone?text=" . urlencode($mensagem);

                    echo '<td>
                            <div class="btn-group">
                                <a class="btn btn-primary btn-sm" href="editar_reserva.php?id=' . $clientes['id'] . '">Editar</a>
                                <a class="btn btn-danger btn-sm" href="excluir_reserva.php?id=' . $clientes['id'] . '">Excluir</a>
                                <a class="btn btn-success btn-sm" href="' . $link_whatsapp . '" target="_blank">Confirmar</a>
                            </div>
                          </td>';
                    echo '</tr>';
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Estilo para botões */
    .btn-group .btn {
        margin: 0 5px;
        font-size: 0.875rem;
    }

    .btn-group .btn:hover {
        transform: scale(1.05);
        transition: transform 0.3s ease;
    }

    /* Estilo para a tabela */
    .table th, .table td {
        text-align: center;
        vertical-align: middle;
    }

    .table th {
        background-color: #f8f9fa;
    }

    /* Estilo para a coluna de observações */
    .obs-column .container {
        max-width: 200px;
        height: 70px;
        overflow-y: auto;
        white-space: normal;
        word-wrap: break-word;
        border: 1px solid #ddd;
        padding: 5px;
        box-sizing: border-box;
        font-size: 12px;
        background-color: #f9f9f9;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .btn-group .btn {
            display: block;
            width: 100%;
            margin: 5px 0;
        }
    }
</style>
<?php require 'rodape.php'; ?>