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
<div class="container-fluid my-4">
    <div class="bg-light p-4 rounded shadow-sm">
        <h2 class="text-center mb-4">Gerenciar Reservas</h2>
        <form method="POST" class="form-inline row justify-content-center mb-4">
            <div class="input-group col-md-3 col-sm-12 mb-3">
                <input class="form-control" name="filtro" type="date" value="<?= $filtro ?? '' ?>">
            </div>
            <div class="input-group col-md-2 col-sm-12 mb-3">
                <button class="btn btn-success" type="submit">Pesquisar</button>
            </div>
        </form>

        <?php
        // Captura a data do filtro
        $filtro = isset($_POST['filtro']) ? $_POST['filtro'] : "";

        // Exibe filtro aplicado
        if ($filtro != "") {
            $data_formatada = date("d/m/Y", strtotime($filtro));
            echo "<h6 class='text-center'>Data <strong>$data_formatada</strong> das 12:00 às 17:59 hs</h6><br>";
        }

        // SQL para contar o total de reservas no intervalo de horário das 12:00 às 17:59
        $sql = "SELECT SUM(num_pessoas) AS total_pessoas FROM clientes 
                WHERE data = '$filtro' AND horario BETWEEN '12:00:00' AND '17:59:00' 
                AND status != 0 
                ORDER BY `data` ASC";
        $stmt = $pdo->query($sql);
        $total_pessoas = 0;
        if ($stmt->rowCount() > 0) {
            $total_pessoas = $stmt->fetch()['total_pessoas'];
            echo "<h4 class='text-center'>Total de pessoas: <span class='badge badge-primary'>$total_pessoas</span></h4><br>";
        } else {
            echo "<h6 class='text-center'>Nenhuma reserva encontrada para esse horário.</h6><br>";
        }
        ?>

        <div class="text-center mb-3">
            <a href="adicionar_reserva.php" class="btn btn-primary">Fazer Nova Reserva</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th>Nome</th>
                        <th>Data</th>
                        <th>N° Pessoas</th>
                        <th>Horário</th>
                        <th>Telefone</th>
                        <th>Tipo de Evento</th>
                        <th>Forma de Pagamento</th>
                        <th>Observações</th>
                        <th>Data de Emissão</th>
                        <th>Usuário ID</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // SQL para buscar as reservas no intervalo de horário das 12:00 às 17:59
                $sql = "SELECT * FROM clientes 
                        WHERE data = '$filtro' AND horario BETWEEN '12:00:00' AND '17:59:00' 
                        AND status != 0 
                        ORDER BY `data` ASC";
                $stmt = $pdo->query($sql);
                if ($stmt->rowCount() > 0) {
                    foreach ($stmt->fetchAll() as $clientes) {
                        $nome = ucfirst(strtolower($clientes['nome']));
                        $data = htmlspecialchars(date("d/m/Y", strtotime($clientes['data'])), ENT_QUOTES, 'UTF-8');
                        $horario = htmlspecialchars(date("H:i", strtotime($clientes['horario'])), ENT_QUOTES, 'UTF-8');
                        $num_pessoas = $clientes['num_pessoas'];
                        $telefone = preg_replace('/[^0-9]/', '', $clientes['telefone']); // Remove caracteres não numéricos

                        $mensagem = "Olá $nome!, Tudo bem? Aqui é da Churrascaria Verdanna! Estamos passando para confirmar sua reserva para $num_pessoas pessoas no dia $data, às $horario hs. Se tudo estiver certo, me confirma com um OK. Agradecemos pela confiança e desejo um excelente dia! Qualquer dúvida, estamos à disposição!";
                        $link_whatsapp = "https://wa.me/55$telefone?text=" . urlencode($mensagem);

                        echo '<tr>';
                        echo '<td>' . $clientes['nome'] . '</td>';
                        echo '<td>' . date("d/m/Y", strtotime($clientes['data'])) . '</td>';
                        echo '<td>' . $clientes['num_pessoas'] . '</td>';
                        echo '<td>' . $clientes['horario'] . '</td>';
                        echo '<td>' . $clientes['telefone'] . '</td>';
                        echo '<td>' . $clientes['tipo_evento'] . '</td>';
                        echo '<td>' . $clientes['forma_pagamento'] . '</td>';
                        echo '<td class="obs-column"><div class="obs-content">' . htmlspecialchars($clientes['observacoes']) . '</div></td>';
                        echo '<td>' . date("d/m/Y", strtotime($clientes['data_emissao'])) . '</td>';
                        echo '<td>' . htmlspecialchars($clientes['usuario_id']) . '</td>';
                        echo '<td>
                                <div class="btn-group">
                                    <a class="btn btn-primary btn-sm" href="editar_reserva.php?id=' . $clientes['id'] . '">Editar</a>
                                    <a class="btn btn-danger btn-sm" href="excluir_reserva.php?id=' . $clientes['id'] . '">Excluir</a>
                                    <a class="btn btn-success btn-sm" href="' . $link_whatsapp . '" target="_blank">Confirmar</a>
                                </div>
                              </td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="11" class="text-center">Nenhuma reserva encontrada para essa data.</td></tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Estilo geral */
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
    }

    /* Botões */
    .btn-group .btn {
        margin: 0 3px;
    }
    .btn-group .btn:hover {
        transform: scale(1.05);
        transition: all 0.3s ease;
    }

    /* Tabela */
    .table th, .table td {
        text-align: center;
        vertical-align: middle;
    }
    .table thead th {
        background-color: #343a40;
        color: #fff;
    }

    /* Observações */
    .obs-content {
        max-width: 250px;
        max-height: 100px;
        overflow-y: auto;
        white-space: pre-wrap;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 5px;
        border-radius: 4px;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .btn-group .btn {
            width: 100%;
            margin: 5px 0;
        }

        .obs-content {
            max-width: 100%;
        }
    }
</style>

<?php require 'rodape.php'; ?>
