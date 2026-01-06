<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Destinos - Agencia de Viajes</title>
    <link rel="stylesheet" href="../css/paises.css" />
  </head>
  <body>
  <header class="header">
    <div class="container">
        <div class="btn-menu">
            <label for="btn-menu">☰</label>
        </div>
        <nav class="menu">
            <a href="administrador.php">Inicio</a>
            <a href="paises.php">Países</a>
            <a href="modificar MVC.php">Destinos</a>
            <a href="blog.php">Sugerencias</a>
        </nav>
    </div>
</header>


<div class="capa"></div>
<input type="checkbox" id="btn-menu" />
<div class="container-menu">
    <div class="cont-menu">
        <nav>
            <ul>
                <li><a href="mi-cuenta.php">Mi Cuenta</a></li>
                <li><a href="mis_reservas.php">Mis Reservas</a></li>
                <li><a href="pagos.php">Pagos y Boletos</a></li>
                <li><a href="bitacora.php">Bitacora</a></li>
                <li><a href="reporte.php">Reportes</a></li>
                <li><a href="politica.php">Politicas</a></li>
            </ul>
            <div class="logout-container">
             <a href="../auth/logout.php" id="logout-button" class="btn-logout">Cerrar Sesión</a>
            </div>

        </nav>
        <label for="btn-menu">✖️</label>
    </div>
</div>

    <main>
      <section class="destinations">
        <header>
          <h2>Nuestros Destinos</h2>
        </header>
        <div class="destination-grid">
          
          <article class="destination-card">
            <div class="icon-container">
              <img src="../assets/images/img banderas/bolivia.jpg" alt="Bandera de Bolivia" class="icon">
            </div>
            <h3>Bolivia</h3>
            <p>Explora la diversidad cultural y natural de Bolivia.</p>

          </article>
    
          <article class="destination-card">
            <div class="icon-container">
              <img src="../assets/images/img banderas/canada.jpg" alt="Bandera de Canadá" class="icon">
            </div>
            <h3>Canadá</h3>
            <p>Maravíllate con los paisajes alpinos de Canadá.</p>

          </article>
    
          <article class="destination-card">
            <div class="icon-container">
              <img src="../assets/images/img banderas/nueva zelanda.jpg" alt="Bandera de Nueva Zelanda" class="icon">
            </div>
            <h3>Nueva Zelanda</h3>
            <p>Vive la aventura en Nueva Zelanda.</p>

          </article>
    
          <article class="destination-card">
            <div class="icon-container">
              <img src="../assets/images/img banderas/estados unidos.jpg" alt="Bandera de Estados Unidos" class="icon">
            </div>
            <h3>Estados Unidos</h3>
            <p>Descubre grandes ciudades y parques nacionales.</p>

          </article>
    
          <article class="destination-card">
            <div class="icon-container">
              <img src="../assets/images/img banderas/bandera peru.jpg" alt="Bandera de Perú" class="icon">
            </div>
            <h3>Perú</h3>
            <p>Explora la historia milenaria de Perú.</p>

          </article>
    
          <article class="destination-card">
            <div class="icon-container">
              <img src="../assets/images/img banderas/italia.jpg" alt="Bandera de Italia" class="icon">
            </div>
            <h3>Italia</h3>
            <p>Sumérgete en el arte y la historia de Italia.</p>

          </article>
    
          <article class="destination-card">
            <div class="icon-container">
              <img src="../assets/images/img banderas/francia.jpg" alt="Bandera de Francia" class="icon">
            </div>
            <h3>Francia</h3>
            <p>Disfruta de la elegancia y cultura francesa.</p>

          </article>
    
          <article class="destination-card">
            <div class="icon-container">
              <img src="../assets/images/img banderas/brasil.jpg" alt="Bandera de Brasil" class="icon">
            </div>
            <h3>Brasil</h3>
            <p>Siente la alegría y diversidad de Brasil.</p>

          </article>
    
          <article class="destination-card">
            <div class="icon-container">
              <img src="../assets/images/img banderas/tanzania.jpg" alt="Bandera de Tanzania" class="icon">
            </div>
            <h3>Tanzania</h3>
            <p>Conquista el Kilimanjaro en Tanzania.</p>

          </article>
    
          <article class="destination-card">
            <div class="icon-container">
              <img src="../assets/images/img banderas/islandia.png" alt="Bandera de Islandia" class="icon">
            </div>
            <h3>Islandia</h3>
            <p>Relájate en paisajes geotermales únicos.</p>

          </article>
    
          <article class="destination-card">
            <div class="icon-container">
              <img src="../assets/images/img banderas/indonesia.jpg" alt="Bandera de Indonesia" class="icon">
            </div>
            <h3>Indonesia</h3>
            <p>Encuentra paz y belleza en Bali.</p>

          </article>
    
          <article class="destination-card">
            <div class="icon-container">
              <img src="../assets/images/img banderas/alemania.jpg" alt="Bandera de Alemania" class="icon">
            </div>
            <h3>Alemania</h3>
            <p>Vive la tradición y modernidad de Alemania.</p>

          </article>
    
          <article class="destination-card">
            <div class="icon-container">
              <img src="../assets/images/img banderas/argentina.jpg" alt="Bandera de Argentina" class="icon">
            </div>
            <h3>Argentina</h3>
            <p>Descubre los paisajes vibrantes de Argentina.</p>

          </article>
    
          <article class="destination-card">
            <div class="icon-container">
              <img src="../assets/images/img banderas/españa.jpg" alt="Bandera de España" class="icon">
            </div>
            <h3>España</h3>
            <p>Sumérgete en la cultura y pasión de España.</p>

          </article>
    
          <article class="destination-card">
            <div class="icon-container">
              <img src="../assets/images/img banderas/tailandia.jpg" alt="Bandera de Tailandia" class="icon">
            </div>
            <h3>Tailandia</h3>
            <p>Descubre los exóticos paisajes de Tailandia.</p>

          </article>
    
          <article class="destination-card">
            <div class="icon-container">
              <img src="../assets/images/img banderas/china.jpg" alt="Bandera de China" class="icon">
            </div>
            <h3>China</h3>
            <p>Explora la modernidad de Hong Kong y más.</p>

          </article>
    
          <article class="destination-card">
            <div class="icon-container">
              <img src="../assets/images/img banderas/japon.jpg" alt="Bandera de Japón" class="icon">
            </div>
            <h3>Japón</h3>
            <p>Vive la tecnología y tradición en Japón.</p>

          </article>
    
        </div>
      </section>
    </main>
    
    

    <footer>
      <div class="footer-container">
        <div class="footer-section">
          <h2>Nuestros contactos</h2>
          <p>WhatsApp: +591 60933137</p>
          <p>Correo: agenciasdeviajes@gmail.com</p>
          <p>Programe una reunión y cotización con nosotros</p>
          <p class="certification">
            Rapido y al instante <span>Bolivia Corp.</span>
          </p>
        </div>
    
        <div class="footer-section">
          <h2>Redes sociales</h2>
          <p>Facebook: Agencia de Viajes Bolivia Corp</p>
          <p>Tik Tok: Bolivia_Corp</p>
          <p>Instagram: Agencia_BoliviaCorp</p>
        </div>
      </div>
    
      <div class="footer-bottom">
        <p>&copy; 2024 Agencia de Viajes. Todos los derechos reservados.</p>
      </div>
    </footer>

    <script>
      window.addEventListener('scroll', function() {
        var header = document.querySelector('.header');
        header.classList.toggle('scrolled', window.scrollY > 0);
      });
      </script>
      
  </body>
</html>
