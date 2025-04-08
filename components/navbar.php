<?php
$current_page = basename($_SERVER['REQUEST_URI']);
?>

<nav class="navbar navbar-expand-lg bg-body-tertiary shadow-sm">
<div class="container-fluid">
  <a class="navbar-brand" href="#">Dashboard EI-TI</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <li class="nav-item">
        <a class="nav-link <?= ($current_page == 'dashboard.php') ? 'active' : '' ?>" href="dashboard.php">Inicio</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= ($current_page == 'historico.php') ? 'active' : '' ?>" href="historico.php">Historico</a>
      </li>
    </ul>
    <a href="logout.php" class="nav-link <?= ($current_page == 'logout.php') ? 'active' : '' ?>">Terminar Sessão</a>
  </div>
</div>
</nav>
