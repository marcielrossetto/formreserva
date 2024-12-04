<?php
session_start();
require 'config.php';

if (empty($_SESSION['mmnlogin'])) {
    header("Location: login.php");
    exit;
}

require 'cabecalho.php';

function getReservations($pdo, $month, $year) {
    $stmt = $pdo->prepare("SELECT data, SUM(CASE WHEN horario BETWEEN '12:00:00' AND '17:59:00' THEN IF(status != 0, num_pessoas, 0) ELSE 0 END) AS almoco, SUM(CASE WHEN horario BETWEEN '18:00:00' AND '23:59:00' THEN IF(status != 0, num_pessoas, 0) ELSE 0 END) AS jantar FROM clientes WHERE MONTH(data) = :month AND YEAR(data) = :year GROUP BY data");
    $stmt->execute(['month' => $month, 'year' => $year]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function generateCalendar($month, $year, $reservations) {
    $daysOfWeek = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    $numberDays = date('t', $firstDayOfMonth);
    $dateComponents = getdate($firstDayOfMonth);
    $monthName = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'][$month - 1];
    $dayOfWeek = $dateComponents['wday'];

    $calendar = "<h2 class='calendar-title'>$monthName de $year</h2>";
    $calendar .= "<table class='calendar-table'>";
    $calendar .= "<tr>";
    foreach ($daysOfWeek as $day) {
        $calendar .= "<th class='calendar-header'>$day</th>";
    }
    $calendar .= "</tr><tr>";

    if ($dayOfWeek > 0) {
        for ($i = 0; $i < $dayOfWeek; $i++) {
            $calendar .= "<td class='empty'></td>";
        }
    }

    $currentDay = 1;
    while ($currentDay <= $numberDays) {
        if ($dayOfWeek == 7) {
            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }

        $currentDate = sprintf('%04d-%02d-%02d', $year, $month, $currentDay);
        $reservationData = array_filter($reservations, fn($res) => $res['data'] === $currentDate);
        $almoco = $reservationData ? current($reservationData)['almoco'] : 0;
        $jantar = $reservationData ? current($reservationData)['jantar'] : 0;

        $calendar .= "<td class='calendar-day'>
                        <a href='clikCalendar.php?data=$currentDate'><strong>$currentDay</strong></a>
                        <div>
                            <span class='badge badge-danger'>A: $almoco</span><br>
                            <span class='badge badge-success'>J: $jantar</span>
                        </div>
                      </td>";

        $currentDay++;
        $dayOfWeek++;
    }

    if ($dayOfWeek != 7) {
        $remainingDays = 7 - $dayOfWeek;
        for ($i = 0; $i < $remainingDays; $calendar .= "<td class='empty'></td>", $i++);
    }

    $calendar .= "</tr></table>";
    return $calendar;
}

$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
$year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

if ($month < 1) {
    $month = 12;
    $year--;
} elseif ($month > 12) {
    $month = 1;
    $year++;
}

$reservations = getReservations($pdo, $month, $year);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <title>Calendário de Reservas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
a {
    color: #33b1c5; /* Cor do link */
    text-decoration: none; /* Remove o sublinhado padrão */
     font-size: 1.5rem;
}
        .calendar-title {
            font-size: 1.5rem;
            text-align: left;
            margin: 10px 0 20px;
        }

        .calendar-table {
            width: 100%;
            border-collapse: collapse;
        }

        .calendar-table th,
        .calendar-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .calendar-header {
            background-color: black;
            color: white;
        }

        .calendar-day {
            background-color: white;
        }
.badge { 
    display: inline-flex;  /* Usando flexbox para melhor controle */
    align-items: center;   /* Alinha o texto verticalmente */
    justify-content: center; /* Centraliza o texto horizontalmente */
    padding: 1px; 
    margin:2px;
    border-radius: 5px;
    width: 83px; 
    white-space: nowrap; /* Impede que o texto quebre */ 
    overflow: hidden; /* Oculta o texto que ultrapassar o limite */ 
    text-overflow: ellipsis; /* Adiciona reticências ao texto que ultrapassar o limite */
    height: 32px;
    font-size: 1rem; /* Tamanho da fonte ajustável */
}


        .badge-danger {
            background-color:#708090;
            color:white;
        }

        .badge-success {
            background-color: #363636;
            color: white;
        }

        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .btn-navigation {
            padding: 8px 15px; font-size: 0.9rem; border: none; border-radius: 20px; /* Bordas arredondadas */ cursor: pointer; transition: all 0.3s ease; /* Efeito suave ao passar o mouse */ background: linear-gradient(145deg, #444, #333); /* Gradiente suave para combinar com o menu preto */ color: white; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);        }

        .btn-navigation:hover {
            background: white; /* Cor de fundo ao passar o mouse */ color: #333; /* Cor do texto ao passar o mouse */ transform: translateY(-3px); /* Leve elevação ao passar o mouse */ box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
        }
        .btn-navigation:active { transform: translateY(1px); /* Efeito de clique */ box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body>
    <div class="container">
        <div class="navigation-buttons">
            <form method="GET" action="">
                <input type="hidden" name="month" value="<?= $month - 1 ?>">
                <input type="hidden" name="year" value="<?= $year ?>">
                <button class="btn-navigation">Anterior</button>
            </form>
            <form method="GET" action="">
                <input type="hidden" name="month" value="<?= $month + 1 ?>">
                <input type="hidden" name="year" value="<?= $year ?>">
                <button class="btn-navigation">Próximo</button>
            </form>
        </div>
        <?= generateCalendar($month, $year, $reservations) ?>
    </div>
</body>
</html>

<?php require 'rodape.php'; ?>
