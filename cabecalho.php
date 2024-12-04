<!DOCTYPE html>
<html>

<head>
  <title>Formulario Reserva</title>
  <meta charset="utf-8">
  <link rel="shortcut icon" type="" href="image/comp.ico">
  <meta id="viewport" name="viewport" content="width=device-width, user-scalable=no">
  <link rel="stylesheet" type="text/css" href="style.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">

  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <link rel="stylesheet" type="text/css" href="bootstrap.min.css">
  <script type="text/javascript" src="jquery.min.js"></script>
  <script type="text/javascript" src="bootstrap.min.js"></script>
  <style>
    /* Adiciona o padding-top para que o conteúdo não fique atrás do cabeçalho fixo */
    body {
      padding-top: 80px; /* Ajuste a altura conforme necessário */
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: #1C1C1C;">
    <a class="navbar-brand" id="logotipo" href="index.php">
      <img src="imagens/rossetto28.png" width="70" height="30" alt="Home">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="index.php">
            <ion-icon name="home"></ion-icon> Home<span class="sr-only">(current)</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="adicionar_reserva.php">
            <ion-icon name="add-circle-outline"></ion-icon>Nova Reserva
          </a>
        </li>
        <div class="dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <ion-icon name="search"></ion-icon> Pesquisar
          </a>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="pesquisar_tudo.php">
              <ion-icon name="search"></ion-icon> Pesquisar Nome
            </a>
            <a class="dropdown-item" href="pesquisar_data.php">
              <ion-icon name="search"></ion-icon> Pesquisar data
            </a>
            <a class="dropdown-item" href="pesquisar_almoco.php">
              <ion-icon name="search"></ion-icon> Pesquisar almoço
            </a>
            <a class="dropdown-item" href="pesquisar_janta.php">
              <ion-icon name="search"></ion-icon> Pesquisar jantar
            </a>
            <a class="dropdown-item" href="pesquisar.php">
              <ion-icon name="search"></ion-icon> Pesquisa completa
            </a>
            <a class="dropdown-item" href="pesquisar_diaria_almoco.php">
              <ion-icon name="paper"></ion-icon> Relatório Almoço
            </a>
            <a class="dropdown-item" href="pesquisa_diaria_jantar.php">
              <ion-icon name="paper"></ion-icon> Relatório jantar
            </a>
            <a class="dropdown-item" href="itensCancelados.php">
              <ion-icon name="close"></ion-icon> Reservas Canceladas
            </a>
            <a class="dropdown-item" href="ultimasReservas.php">
              <ion-icon name="close"></ion-icon> Últimas reservas
            </a>
          </div>
        </div>
        <div class="dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <ion-icon name="construct"></ion-icon> Painel Controle
          </a>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="senhaentrar.php">Cadastrar Usuário</a>
            <a class="dropdown-item" href="senhaEntrarVerUsuario.php">
              <ion-icon name="search"></ion-icon>Pesquisar Usuário
            </a>
            <a class="dropdown-item" href="senha_entrar_rodizio.php">Alterar Valor Rodizio</a>
          </div>
        </div>
        <li class="nav-item">
          <a class="nav-link " href="sair.php"><ion-icon name="exit"></ion-icon>Sair</a>
        </li>
      </ul>
    </div>
  </nav>

  <script src="script.js"></script>
</body>

</html>
