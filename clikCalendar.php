<?php
session_start();
require 'config.php';

if (empty($_SESSION['mmnlogin'])) {
    header("Location: login.php");
    exit;
}

require 'cabecalho.php';
?>

<meta id="viewport" name="viewport" content="width=device-width, initial-scale=1.0">

<div class="bg-light p-4 rounded shadow-sm mt-4">
    <?php
    // Verifica se há um filtro de data
    $filtro = isset($_GET['data']) ? $_GET['data'] : "";
    if ($filtro != "") {
        $data_formatada = date("d/m/Y", strtotime($filtro));
        echo "<h2 class='text-center mb-4'>Reservas para o dia: $data_formatada</h2>";
    } else {
        echo "<h2 class='text-center mb-4'>Últimas 20 Reservas</h2>";
    }
    ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="adicionar_reserva.php" class="btn btn-success">
            <ion-icon name="add-circle-outline"></ion-icon> Nova Reserva
        </a>
        <form method="GET" class="d-flex">
            <input type="date" name="data" class="form-control me-2" value="<?= $filtro ?? '' ?>">
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover table-sm w-100">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Data</th>
                    <th>N° Pessoas</th>
                    <th>Horário</th>
                    <th>Telefone</th>
                    <th>Tipo de Evento</th>
                    <th>Observação</th>
                    <th>Responsável</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Ajuste na consulta SQL para ignorar status 0
                $sql = "SELECT * FROM clientes WHERE data LIKE '$filtro%' AND status != 0 ORDER BY id DESC LIMIT 20";
                $sql = $pdo->query($sql);
                if ($sql->rowCount() > 0) {
                    foreach ($sql->fetchAll() as $reserva) {
                        $telefone = preg_replace('/[^0-9]/', '', $reserva['telefone']); // Remove caracteres não numéricos
                        $mensagem = "Olá " . htmlspecialchars($reserva['nome']) . "! Tudo bem? Aqui é da Churrascaria Verdanna! Estamos passando para confirmar sua reserva para " . $reserva['num_pessoas'] . " pessoas no dia " . date("d/m/Y", strtotime($reserva['data'])) . " às " . htmlspecialchars($reserva['horario']) . ". Se tudo estiver certo, me confirma com um OK. Agradecemos pela confiança ! Qualquer dúvida, estamos à disposição!";
                        $link_whatsapp = "https://wa.me/55$telefone?text=" . urlencode($mensagem);
                
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($reserva['id']) . '</td>';
                        echo '<td>' . htmlspecialchars($reserva['nome']) . '</td>';
                        echo '<td>' . date("d/m/Y", strtotime($reserva['data'])) . '</td>';
                        echo '<td>' . htmlspecialchars($reserva['num_pessoas']) . '</td>';
                        echo '<td>' . htmlspecialchars($reserva['horario']) . '</td>';
                        echo '<td>' . htmlspecialchars($reserva['telefone']) . '</td>';
                        echo '<td>' . htmlspecialchars($reserva['tipo_evento']) . '</td>';
                        echo '<td>
                                <div class="obs-content">
                                    ' . htmlspecialchars($reserva['observacoes']) . '
                                </div>
                              </td>';
                        echo '<td>' . htmlspecialchars($reserva['usuario_id']) . '</td>';
                        echo '<td>
                                <div class="btn-group">
                                    <a class="btn btn-primary btn-sm" href="editar_reserva.php?id=' . $reserva['id'] . '">Editar</a>
                                    <a class="btn btn-danger btn-sm" href="excluir_reserva.php?id=' . $reserva['id'] . '">Excluir</a>
                                    <a class="btn btn-success btn-sm" href="' . $link_whatsapp . '" target="_blank">Confirmar</a>
                                </div>
                              </td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="10" class="text-center">Nenhuma reserva encontrada com status ativo.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Estilo geral */
    .table th, .table td {
        text-align: center;
        vertical-align: middle;
    }

    .thead-dark th {
        background-color: #343a40;
        color: #fff;
    }

    /* Estilo para o campo de observação */
    .obs-content {
        max-height: 100px; /* Limita a altura do campo */
        overflow-y: auto; /* Adiciona rolagem quando necessário */
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 5px;
        border-radius: 4px;
    }
</style>
