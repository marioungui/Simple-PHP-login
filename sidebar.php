<div class="wrapper">
  <div class="sidebar" data-color="blue">
    <!--
      Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow"
  -->
    <div class="logo">
      <a href="<?php echo DOMAIN . ROOT_URL ?>" class="simple-text logo-mini"> TA </a>
      <a href="<?php echo DOMAIN . ROOT_URL ?>" class="simple-text logo-normal"> <?=APP_NAME?> </a>
    </div>
    <div class="sidebar-wrapper" id="sidebar-wrapper">
      <ul class="nav">
        <li class="active ">
          <a href="<?= ROOT_URL ?>">
            <i class="now-ui-icons design_app"></i>
            <p>Inicio</p>
          </a>
        </li>
        <li>
          <a href="registrar.php">
            <i class="now-ui-icons files_paper"></i>
            <p>Registrar Valores</p>
          </a>
        </li>
        <?php if (isAdmin()) : ?>
        <li>
          <a href="create-user.php">
            <i class="now-ui-icons users_single-02"></i>
            <p>Crear nuevos usuarios</p>
          </a>
        </li>
        <?php endif; ?>
        <?php if (isAdmin()) : ?>
        <li>
          <a href="preguntas.php">
            <i class="now-ui-icons arrows-1_cloud-upload-94"></i>
            <p>Crear preguntas</p>
          </a>
        </li>
        <?php endif; ?>
        <li>
            <a href="logout.php">
                <i class="now-ui-icons arrows-1_share-66"></i>
                <p>Salir del sistema</p>
            </a>
        </li>
      </ul>
    </div>
  </div>
