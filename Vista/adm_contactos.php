<?php
  $titulo_pag = 'Contáctanos';
  include_once 'layouts/header.php';
?>

<div class="wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="full-width-container">
      <div class="row mb-2">
        <div class="col-12">
          <!-- Caja azul que contiene el título -->
          <div class="p-3 mb-2 bg-primary text-white rounded">
            <h1 class="mb-0"><?php echo $titulo_pag; ?></h1>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <div class="card card-info card-expanded">
            <div class="card-header">
              <h3 class="card-title">Cómo contactarnos</h3>
            </div>
            <div class="card-body">
              <p>Estamos aquí para ayudarte. Puedes contactarnos a través de los siguientes medios:</p>
              <ul>
                <li><strong>Correo electrónico:</strong> contacto@mitienda.com</li>
                <li><strong>Teléfono:</strong> +1 (555) 123-4567</li>
                <li><strong>Dirección:</strong> Av. Principal 123, Ciudad, País</li>
                <li><strong>Horario de atención:</strong> Lunes a Viernes, de 9:00 AM a 6:00 PM</li>
              </ul>
              <p>Síguenos en nuestras redes sociales:</p>
              <ul>
                <li><strong>Facebook:</strong> <a href="https://www.facebook.com/mitienda" target="_blank">facebook.com/mitienda</a></li>
                <li><strong>Twitter:</strong> <a href="https://www.twitter.com/mitienda" target="_blank">twitter.com/mitienda</a></li>
                <li><strong>Instagram:</strong> <a href="https://www.instagram.com/mitienda" target="_blank">instagram.com/mitienda</a></li>
              </ul>
            </div>
            <!-- /.card-body -->
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>

<?php
include_once 'layouts/footer.php';
?>

<!-- Custom styles for footer to stick to the bottom -->
<style>
  html, body {
    height: 100%;
    margin: 0;
    display: flex;
    flex-direction: column;
  }

  .wrapper {
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .content {
    flex: 1;
  }

  footer {
    background-color: #f8f9fa;
    padding: 1rem;
    text-align: center;
  }

  .full-width-container {
    width: 100%;
    padding: 0;
    margin: 0;
  }

  .col-12 {
    width: 100%;
  }

  /* Custom styles to enlarge the "Cómo contactarnos" section */
  .card-expanded {
    padding: 2rem;
    font-size: 1.2rem; /* Aumenta el tamaño del texto */
    line-height: 1.8; /* Mejora la legibilidad del texto */
  }

  .card-header h3 {
    font-size: 2rem; /* Aumenta el tamaño del título */
    font-weight: bold;
  }

  .card-body ul {
    font-size: 1.4rem; /* Aumenta el tamaño del texto en la lista */
  }
</style>
