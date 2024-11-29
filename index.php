<?php
session_start();
require 'config.php';

if (empty($_SESSION['mmnlogin'])) {
    header("Location: login.php");
    exit;
}

require 'cabecalho.php';
?>

<div class="container-fluid text-center">
    <script language="JavaScript">
        document.write("<font color='#D2691E' size='5' face='poppins'>");
        var mydate = new Date();
        var year = mydate.getFullYear();
        var day = mydate.getDay();
        var month = mydate.getMonth();
        var daym = mydate.getDate();
        if (daym < 10)
            daym = "0" + daym;

        var dayarray = new Array("Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado");
        var montharray = new Array(
            "de Janeiro de ", "de Fevereiro de ", "de Março de ", "de Abril de ", 
            "de Maio de ", "de Junho de ", "de Julho de ", "de Agosto de ", 
            "de Setembro de ", "de Outubro de ", "de Novembro de ", "de Dezembro de "
        );

        document.write(" " + dayarray[day] + ", " + daym + " " + montharray[month] + year + " ");
        document.write("</font>");
    </script>
    <hr>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
</div>

<?php
// Função para buscar reservas do banco
function getReservations($pdo, $month, $year) {
    $stmt = $pdo->prepare("
        SELECT data,
               SUM(CASE WHEN horario BETWEEN '12:00:00' AND '17:59:00' THEN IF(status != 0, num_pessoas, 0) ELSE 0 END) AS almoco,
               SUM(CASE WHEN horario BETWEEN '18:00:00' AND '23:59:00' THEN IF(status != 0, num_pessoas, 0) ELSE 0 END) AS jantar
        FROM clientes
        WHERE MONTH(data) = :month
          AND YEAR(data) = :year
        GROUP BY data
    ");
    $stmt->execute(['month' => $month, 'year' => $year]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Função para gerar o calendário
function generateCalendar($month, $year, $reservations) {
    $daysOfWeek = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    $numberDays = date('t', $firstDayOfMonth);
    $dateComponents = getdate($firstDayOfMonth);
    $monthName = array(
        "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", 
        "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"
    )[$month - 1];
    $dayOfWeek = $dateComponents['wday'];

    $calendar = "<h2 class='text-center'> $monthName de $year</h2>";
    
    $calendar .= "<table class='calendar table table-bordered'>";
    $calendar .= "<tr>";

    // Cabeçalho com os dias da semana
    foreach ($daysOfWeek as $day) {
        $calendar .= "<th class='header'>$day</th>";
    }
    $calendar .= "</tr><tr>";

    // Dias vazios antes do início do mês
    if ($dayOfWeek > 0) {
        for ($i = 0; $i < $dayOfWeek; $i++) {
            $calendar .= "<td class='empty'></td>";
        }
    }

    // Dias do mês
    $currentDay = 1;
    while ($currentDay <= $numberDays) {
        if ($dayOfWeek == 7) {
            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }

        $currentDate = sprintf('%04d-%02d-%02d', $year, $month, $currentDay);  // Corrigido para exibir a data no formato correto
        $reservationData = array_filter($reservations, function ($reservation) use ($currentDate) {
            return $reservation['data'] === $currentDate;
        });
        $almoco = $reservationData ? current($reservationData)['almoco'] : 0;
        $jantar = $reservationData ? current($reservationData)['jantar'] : 0;

        $calendar .= "<td class='day' rel='$currentDate'>
                        <a href='clikCalendar.php?data=$currentDate'><strong>$currentDay</strong></a>
                        <div>
                            <span class='badge badge-danger'>A: $almoco</span><br>
                            <span class='badge badge-success'>J: $jantar</span>
                        </div>
                      </td>";

        $currentDay++;
        $dayOfWeek++;
    }

    // Dias vazios no final do mês
    if ($dayOfWeek != 7) {
        $remainingDays = 7 - $dayOfWeek;
        for ($i = 0; $i < $remainingDays; $calendar .= "<td class='empty'></td>", $i++);
    }

    $calendar .= "</tr>";
    $calendar .= "</table>";

    return $calendar;
}

// Configuração de mês e ano
$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
$year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

if ($month < 1) {
    $month = 12;
    $year--;
} elseif ($month > 12) {
    $month = 1;
    $year++;
}

$reservations = getReservations($pdo, $month, $year);  // Corrigido para enviar o mês correto (1-12)
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <title>Calendário de Reservas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .calendar .badge {
            font-size: 0.8rem;
            margin-top: 5px;
        }
        .calendar .day {
            height: 10px;
            vertical-align: top;
            align-items: center;
            text-align: center;
            font-size:auto;
        }
        .calendar .empty {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="navigation-buttons text-center mb-4">
            <form method="GET" action="" style="display: inline-block;">
                <input type="hidden" name="month" value="<?= $month - 1 ?>">
                <input type="hidden" name="year" value="<?= $year ?>">
                <button class="btn btn-danger">Mês Anterior</button>
            </form>
            <form method="GET" action="" style="display: inline-block;">
                <input type="hidden" name="month" value="<?= $month + 1 ?>">
                <input type="hidden" name="year" value="<?= $year ?>">
                <button class="btn btn-success">Próximo Mês</button>
            </form>
        </div>
        <?= generateCalendar($month, $year, $reservations) ?>
    </div>
</body>
</html>
