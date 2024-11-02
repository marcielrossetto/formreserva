<?php
session_start();
require 'config.php';

if (empty($_SESSION['mmnlogin'])) {
    header("Location: login.php");
    exit;
}

require 'cabecalho.php';
?>

<br>
<div class="container">
    <script language="JavaScript">
        document.write("<font color='#D2691E' size='5' text-align='center' face='poppins'>")
        var mydate = new Date()
        var year = mydate.getYear()
        if (year < 2000)
            year += (year < 1900) ? 1900 : 0
        var day = mydate.getDay()
        var month = mydate.getMonth()
        var daym = mydate.getDate()
        if (daym < 10)
            daym = "0" + daym
        var dayarray = new Array("Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado")
        var montharray = new Array(" de Janeiro de ", " de Fevereiro de ", " de Março de ", "de Abril de ", "de Maio de ", "de Junho de", "de Julho de ", "de Agosto de ", "de Setembro de ", " de Outubro de ", " de Novembro de ", " de Dezembro de ")
        document.write(" " + dayarray[day] + ", " + daym + " " + montharray[month] + year + " ")
        document.write("</b></i></font>")
    </script>
    <hr>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
</div>

<?php
$pdo = new PDO("mysql:dbname=bancoverdanna;host=localhost", "root", "");

function getReservations($pdo, $month, $year) {
    $stmt = $pdo->prepare("SELECT data, SUM(num_pessoas) as total_pessoas FROM clientes WHERE MONTH(data) = :month AND YEAR(data) = :year AND status != 0 GROUP BY data");
    $stmt->execute(['month' => $month, 'year' => $year]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function generateCalendar($month, $year, $reservations) {
    $daysOfWeek = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    $numberDays = date('t', $firstDayOfMonth);
    $dateComponents = getdate($firstDayOfMonth);
    $monthName = $dateComponents['month'];
    $dayOfWeek = $dateComponents['wday'];
    $calendar = "<h2>$monthName $year</h2>";
    $calendar .= "<table class='calendar'>";
    $calendar .= "<tr>";

    foreach ($daysOfWeek as $day) {
        $calendar .= "<th class='header'>$day</th>";
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

        $currentDate = "$year-$month-" . str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $dayReservations = array_filter($reservations, function($reservation) use ($currentDate) {
            return $reservation['data'] == $currentDate;
        });
        $totalPessoas = count($dayReservations) ? current($dayReservations)['total_pessoas'] : 0;

        $calendar .= "<td class='day' rel='$currentDate'><a href='clikCalendar.php?data=$currentDate'>$currentDay";
        if ($totalPessoas > 0) {
            $calendar .= " <span class='badge'>$totalPessoas</span>";
        }
        $calendar .= "</a></td>";

        $currentDay++;
        $dayOfWeek++;
    }

    if ($dayOfWeek != 7) {
        $remainingDays = 7 - $dayOfWeek;
        for ($i = 0; $i < $remainingDays; $i++) {
            $calendar .= "<td class='empty'></td>";
        }
    }

    $calendar .= "</tr>";
    $calendar .= "</table>";

    return $calendar;
}

$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

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
    <title>Calendário</title>
    <style>
        .calendar {
            width: 100%;
            border-collapse: collapse;
        }
        .calendar th {
            background-color: #f2f2f2;
            padding: 5px;
        }
        .calendar td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .calendar .empty {
            background-color: #f9f9f9;
        }
        .calendar .badge {
            background-color: red;
            color: #fff;
            padding: 5px;
            border-radius: 50%;
            font-size: 0.8em;
        }
        </style>
</head>
<body>
    <div class="container navigation-buttons">
        <form method="GET" action="" class="d-inline-block">
            <input type="hidden" name="month" value="<?= $month - 1 ?>">
            <input type="hidden" name="year" value="<?= $year ?>">
            <button class="btn btn-danger" type="submit">Mês Anterior</button>
        </form>
        <form method="GET" action="" class="d-inline-block">
            <input type="hidden" name="month" value="<?= $month + 1 ?>">
            <input type="hidden" name="year" value="<?= $year ?>">
            <button class="btn btn-success" type="submit">Próximo Mês</button>
        </form>
    </div>
    <div class="container">
        <?= generateCalendar($month, $year, $reservations) ?>
    </div>
</body>
</html>
