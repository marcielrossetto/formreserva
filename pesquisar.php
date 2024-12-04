<?php
session_start();
require 'config.php';

if (empty($_SESSION['mmnlogin'])) {
    header("Location: login.php");
    exit;
}

require 'cabecalho.php';
?>

<div style="width: 100%; margin: 20px auto; padding: 10px;">
    <h3 style="text-align: center;">Pesquisar Reservas</h3>

    <form method="POST" style="display: flex; flex-wrap: wrap; justify-content: space-between; gap: 10px; margin-bottom: 20px;">
        <div style="flex: 1 1 200px;">
            <label>Data Inicial:</label>
            <input type="date" name="data_inicio" style="width: 100%; padding: 5px;">
        </div>
        <div style="flex: 1 1 200px;">
            <label>Data Final:</label>
            <input type="date" name="data_fim" style="width: 100%; padding: 5px;">
        </div>
        <div style="flex: 1 1 200px;">
            <label>Horário Inicial:</label>
            <input type="time" name="hora_inicio" style="width: 100%; padding: 5px;">
        </div>
        <div style="flex: 1 1 200px;">
            <label>Horário Final:</label>
            <input type="time" name="hora_fim" style="width: 100%; padding: 5px;">
        </div>
        <div style="flex: 1 1 300px;">
            <label>Buscar</label>
            <input type="text" name="busca" placeholder="Digite nome ou telefone" style="width: 100%; padding: 5px;">
        </div>
        <div style="flex: 1 1 100px; display: flex; align-items: flex-end;">
            <button type="submit" style="padding: 8px 15px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">
                Pesquisar
            </button>
        </div>
    </form>

    <?php
    $data_inicio = $_POST['data_inicio'] ?? "";
    $data_fim = $_POST['data_fim'] ?? "";
    $hora_inicio = $_POST['hora_inicio'] ?? "";
    $hora_fim = $_POST['hora_fim'] ?? "";
    $busca = $_POST['busca'] ?? "";

    $query = "SELECT * FROM clientes WHERE 1=1";

    if ($data_inicio && $data_fim) {
        $query .= " AND data BETWEEN '$data_inicio' AND '$data_fim'";
    }
    if ($hora_inicio && $hora_fim) {
        $query .= " AND horario BETWEEN '$hora_inicio' AND '$hora_fim'";
    }
    if ($busca) {
        $query .= " AND (nome LIKE '%$busca%' OR telefone LIKE '%$busca%')";
    }

    $query .= " ORDER BY data DESC, horario DESC";
    $sql = $pdo->query($query);

    $total_pessoas = $pdo->query("SELECT SUM(num_pessoas) as total FROM clientes WHERE status = 1")->fetch()['total'];
    echo "<h5 style='text-align: center;'>Total de Pessoas nas Reservas Ativas: $total_pessoas</h5>";
    ?>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: center; border: 1px solid #ddd;">
            <thead style="background-color: #343a40; color: white;">
                <tr>
                    <th style="border: 1px solid #ddd; padding: 8px;">ID</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Nome</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Data e Hora</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">N° Pessoas</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Telefone</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Tipo de Evento</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Forma de Pagamento</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Valor</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Mesa</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Observações</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Status</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Usuário</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Motivo cancelamento</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($sql->rowCount() > 0) {
                    foreach ($sql->fetchAll() as $reserva) {
                        $status_class = $reserva['status'] == 0 ? 'color: red; text-decoration: line-through;' : '';
                        $telefone = preg_replace('/[^0-9]/', '', $reserva['telefone']);
                        $mensagem = "Olá " . htmlspecialchars($reserva['nome']) . "! Tudo bem? Aqui é da Churrascaria Verdanna! Confirmamos sua reserva para " . $reserva['num_pessoas'] . " pessoas no dia " . date("d/m/Y", strtotime($reserva['data'])) . " às " . htmlspecialchars($reserva['horario']) . ". Confirme com OK. Agradecemos!";
                        $link_whatsapp = "https://wa.me/55$telefone?text=" . urlencode($mensagem);

                        echo "<tr style='$status_class'>";
                        echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$reserva['id']}</td>";
                        echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$reserva['nome']}</td>";
                        echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . date('d/m/Y H:i', strtotime($reserva['data'] . ' ' . $reserva['horario'])) . "</td>";
                        echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$reserva['num_pessoas']}</td>";
                        echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$reserva['telefone']}</td>";
                        echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$reserva['tipo_evento']}</td>";
                        echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$reserva['forma_pagamento']}</td>";
                        echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$reserva['valor_rodizio']}</td>";
                        echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$reserva['num_mesa']}</td>";
                        echo "<td style='border: 1px solid #ddd; padding: 8px;'>
                                <div style='max-width: 300px; max-height: 100px; overflow-y: auto; word-wrap: break-word; font-size: auto;'>
                                    " . htmlspecialchars($reserva['observacoes']) . "
                                </div>
                            </td>";
                        echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$reserva['status']}</td>";
                        echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$reserva['usuario_id']}</td>";
                        echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$reserva['motivo_cancelamento']}</td>";
                        echo "<td style='border: 1px solid #ddd; padding: 8px;'>
                                <a href='editar_reserva.php?id={$reserva['id']}' style='background-color: #e0e0e0; color: black; padding: 5px 10px; border-radius: 5px; text-decoration: none;'>Editar</a>
                                <a href='excluir_reserva.php?id={$reserva['id']}' style='background-color: #ffcccc; color: black; padding: 5px 10px; border-radius: 5px; text-decoration: none;'>Excluir</a>
                                <a href='$link_whatsapp' target='_blank' style='background-color: #c8e6c9; color: black; padding: 5px 10px; border-radius: 5px; text-decoration: none;'>Confirmar</a>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='13' style='border: 1px solid #ddd; padding: 8px;'>Nenhuma reserva encontrada.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php require 'rodape.php'; ?>
