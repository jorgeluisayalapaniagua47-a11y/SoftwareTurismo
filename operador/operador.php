<?php
session_start(); // Iniciar sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    // Si no está autenticado, redirigir a la página de inicio de sesión
    header("Location: ../auth/login.php");
    exit(); // Detener la ejecución si el usuario no está autenticado
}

// Aquí va el contenido de la página (ejemplo de usuarios.php)
echo "Bienvenido, " . $_SESSION['nombre']; // Mostrar el nombre del usuario
?>


<!DOCTYPE html>
<html lang="es">
  <head>
    <link rel="icon" href="icono.ico" type="image/x-.icon">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title> Agencia de Viajes</title>
    <link rel="stylesheet" href="../css/estilo.css" />
    <script src="index.js" defer></script>
    <link rel="icon" href="images/logo-favicon.png" type="image/x-icon">
  </head>
  <body>
  <header class="header">
    <div class="container">
        <div class="btn-menu">
            <label for="btn-menu">☰</label>
        </div>
        <nav class="menu">
            <a href="operador.php">Inicio</a>
            <a href="o_paises.php">Países</a>
            <a href="o_modificar MVC.php">Destinos</a>
            <a href="o_blog.php">Sugerencias</a>
        </nav>
    </div>
</header>


<div class="capa"></div>
<input type="checkbox" id="btn-menu" />
<div class="container-menu">
    <div class="cont-menu">
        <nav>
            <ul>
                <li><a href="o_mi-cuenta.php">Mi Cuenta</a></li>
                <li><a href="o_mis_reservas.php">Mis Reservas</a></li>
                <li><a href="o_pagos.php">Pagos y Boletos</a></li>
                <li><a href="o_politica.php">Politicas</a></li>
            </ul>
            <div class="logout-container">
             <a href="../auth/logout.php" id="logout-button" class="btn-logout">Cerrar Sesión</a>
            </div>

        </nav>
        <label for="btn-menu">✖️</label>
    </div>
</div>


    
    <main>
      <section class="hero">
        <h1>Descubre Nuevos Horizontes</h1>
        <p>Explora los mejores destinos con nuestra agencia de viajes.</p>
      </section>

      <section class="image-gallery">
      <img src="../assets/images/bali.jpg" alt="bali" />
<img src="../assets/images/CasaDeMoneda.jpg" alt="CasaDeMoneda" />
<img src="../assets/images/Urmiri.png" alt="Urmiri" />
<img src="../assets/images/yelowstone.jpg" alt="yelowstone" />
      </section>
      <section class="funciona">
        <h2>¡Cómo <span>funciona</span>!</h2>
        <div class="steps">
            <div class="step">
                <img src="../assets/images/icons/icono1.png" alt="Icono 1">
                <h3>Cuéntanos sobre<br><span>tu aventura soñada</span></h3>
                <p>Llena un sencillo formulario para contarnos tus intereses, estilo de viaje y expectativas. ¡Queremos diseñar algo único para ti!</p>
            </div>
            <div class="step">
                <img src="../assets/images/icons/icono2.png" alt="Icono 2">
                <h3>Conéctate con<br><span>tu asesor experto</span></h3>
                <p>Te pondremos en contacto con un especialista que entiende tu visión y te ayudará a construir el mejor plan de viaje posible.</p>
            </div>
            <div class="step">
                <img src="../assets/images/icons/icono3.png" alt="Icono 3">
                <h3>Diseña<br><span>una experiencia inolvidable</span></h3>
                <p>Trabaja junto a tu asesor para pulir todos los detalles y crear un itinerario perfecto, pensado solo para ti.</p>
            </div>
        </div>
    </section>
    <section class="impacto">
      <div class="impacto-content">
        <h2>Generando <span>impacto</span> en cada paso del <span>camino</span></h2>
        <p>Conoce a las comunidades locales. Creemos oportunidades reales a través del turismo social y sostenible.</p>
        <p class="certification">
          Somos la primera empresa de turismo en Bolivia certificada como <span>Bolivia Corp.</span>
        </p>
        <img src="../assets/images/logo-light-transparent.png" alt="Logo B Corp" class="bcorp-logo">
        <p>                                            </p>
        <div class="image-container">
  <img src="../assets/images/peru.jpg" alt="Comunidades locales">
  <div class="image-info">Perú</div>
</div>
<div class="image-container">
  <img src="../assets/images/Rio.jpeg" alt="Comunidades locales">
  <div class="image-info">Río amazonico</div>
</div>
<div class="image-container">
  <img src="../assets/images/Salar_Uyuni.jpg" alt="Comunidades locales">
  <div class="image-info">El cielo en la tierra en el salar de Uyuni</div>
</div>

<div class="image-container">
  <img src="../assets/images/Parque_Nac_Noel.jpg" alt="Comunidades locales">
  <div class="image-info">Descubre la Magia de la Naturaleza en el parque Noel Keempf Mercado</div>
</div>

<div class="image-container">
  <img src="../assets/images/Copacabana.jpg" alt="Comunidades locales">
  <div class="image-info">disfruta de Copacabana junto a su gastronomia maritima juto al lago mas alto del mundo</div>
</div>

<div class="image-container">
  <img src="../assets/images/Rio.jpeg" alt="Comunidades locales">
  <div class="image-info">Río amazonico</div>
</div>

      <section class="blog center">
        <h2 id="blog-titulo">Nuestros Operadores Turísticos</h2>
        <p id="blog-parrafo">Conoce al equipo que hace posible tus aventuras inolvidables.</p>
        
        <div class="carousel-container">
          <button class="carousel-btn prev">&#10094;</button>
          
          <div class="blog-content-carousel">
            <!-- Cada operador turístico como una tarjeta -->
            <div class="blog-card">
              <img src="../assets/images/img operadores_turisticos/2.jpg" alt="Carlos Méndez">
              <h3>Coordinador de Rutas</h3>
              <p><strong>Carlos Méndez</strong></p>
              <p>Diseña recorridos a medida para conectar culturas y paisajes en viajes memorables por Bolivia.</p>
            </div>
      
            <div class="blog-card">
              <img src="../assets/images/img operadores_turisticos/1.jpg" alt="Lucía Fernández">
              <h3>Guía Patrimonial</h3>
              <p><strong>Lucía Fernández</strong></p>
              <p>Experta en tradiciones andinas y sitios históricos, ofrece visitas con sentido cultural profundo.</p>
            </div>
      
            <div class="blog-card">
              <img src="../assets/images/img operadores_turisticos/4.jpg" alt="Miguel Rojas">
              <h3>Especialista en Ecoturismo</h3>
              <p><strong>Miguel Rojas</strong></p>
              <p>Lidera experiencias responsables en reservas naturales y comunidades amazónicas.</p>
            </div>
      
            <div class="blog-card">
              <img src="../assets/images/img operadores_turisticos/5.jpg" alt="Ana Gutiérrez">
              <h3>Coordinadora de Experiencias</h3>
              <p><strong>Ana Gutiérrez</strong></p>
              <p>Fusiona naturaleza, gastronomía y arte en recorridos personalizados por pueblos mágicos.</p>
            </div>
      
            <div class="blog-card">
              <img src="../assets/images/img operadores_turisticos/3.jpg" alt="Javier Vargas">
              <h3>Guía de Aventura</h3>
              <p><strong>Javier Vargas</strong></p>
              <p>Conduce expediciones de trekking y ciclismo por los Valles y el Altiplano boliviano.</p>
            </div>
      
            <div class="blog-card">
              <img src="../assets/images/img operadores_turisticos/6.jpg" alt="María López">
              <h3>Guía Naturalista</h3>
              <p><strong>María López</strong></p>
              <p>Apasionada por la fauna y flora, interpreta ecosistemas en visitas educativas y amigables.</p>
            </div>
      
            <div class="blog-card">
              <img src="../assets/images/img operadores_turisticos/7.jpg" alt="Pedro Aguilar">
              <h3>Asesor de Viajes</h3>
              <p><strong>Pedro Aguilar</strong></p>
              <p>Brinda acompañamiento a cada cliente para elegir su ruta ideal y planificar sin estrés.</p>
            </div>
      
            <div class="blog-card">
              <img src="../assets/images/img operadores_turisticos/9.jpg" alt="Sofía Romero">
              <h3>Experta en Turismo Comunitario</h3>
              <p><strong>Sofía Romero</strong></p>
              <p>Promueve la conexión con comunidades locales para un turismo justo y enriquecedor.</p>
            </div>
      
            <div class="blog-card">
              <img src="../assets/images/img operadores_turisticos/8.jpg" alt="Andrés Castillo">
              <h3>Logística de Operaciones</h3>
              <p><strong>Andrés Castillo</strong></p>
              <p>Coordina transporte, alojamientos y servicios para asegurar una experiencia impecable.</p>
            </div>
      
            <div class="blog-card">
              <img src="../assets/images/img operadores_turisticos/10.jpeg" alt="Valeria Campos">
              <h3>Guía Gastronómica</h3>
              <p><strong>Valeria Campos</strong></p>
              <p>Encabeza recorridos culinarios para descubrir los sabores auténticos de cada región.</p>
            </div>
      
            <div class="blog-card">
              <img src="../assets/images/img operadores_turisticos/10.jpg" alt="Diego Salazar">
              <h3>Productor de Experiencias</h3>
              <p><strong>Diego Salazar</strong></p>
              <p>Crea eventos y vivencias exclusivas, desde cenas privadas hasta retiros eco-luxury.</p>
            </div>
      
            <div class="blog-card">
              <img src="../assets/images/img operadores_turisticos/12.jpg" alt="Camila Pinto">
              <h3>Promotora de Destinos</h3>
              <p><strong>Camila Pinto</strong></p>
              <p>Especialista en marketing turístico, difunde destinos bolivianos en medios digitales.</p>
            </div>
          </div>
      
          <button class="carousel-btn next">&#10095;</button>
        </div>
      </section>
      
    </section>
    
    <section class="inmercion-section">
      <div class="inmercion-content">
        <h2>Tu destino te espera.<br>Experimenta la <span>magia.</span></h2>
        <div class="magic-gallery">
          <div class="gallery-item">
            <img src="../assets/images/chalalan.jpg" alt="Chiapas">
            <p>Chalalan</p>
          </div>
          <div class="gallery-item">
            <img src="../assets/images/ruta del vino.jpg" alt="Yucatán">
            <p>Ruta del Vino</p>
          </div>
          <div class="gallery-item">
            <img src="../assets/images/salar.avif" alt="Campeche">
            <p>Salar</p>
          </div>
          <div class="gallery-item">
            <img src="../assets/images/saturnia.jpg" alt="Quintana Roo">
            <p>Saturnia</p>
          </div>
          <div class="gallery-item">
            <img src="../assets/images/selva amazonica.jpg" alt="Ciudad de México">
            <p>El Amazonas</p>
          </div>
          <div class="gallery-item">
            <img src="../assets/images/lagocolorado.jpg" alt="Oaxaca">
            <p>Lago Colorado</p>
          </div>
        </div>
      </div>
    </section>
    </main>

    <section class="gusto-viaje">
      <h2><span>Por qué a los viajeros les encanta</span><br> nuestros destinos</h2>
      <div class="gusto-viaje-content">
        <div class="viaje-card">
          <img src="../assets/images/Copacabana.jpg" >
          <p>Todas las experiencias se pueden adaptar según tus necesidades, para que puedas tener el viaje de tus sueños. Déjanos mostrarte la verdadera magia de</p>
        </div>
        <div class="viaje-card">
          <img src="../assets/images/ruta del vino.jpg" >
          <p>Deja la planeación en nuestras manos y solo disfruta de tu tiempo. Nuestros experimentados especialistas de viajes.</p>
        </div>
        <div class="viaje-card">
          <img src="../assets/images/Parque_Nac_Noel.jpg" >
          <p>Seguro en cada punto de tu viaje mientras disfrutas de hermosos lugares como nuestros parques</p>
        </div>
      </div>
    </section>
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
      const carousel = document.querySelector('.blog-content-carousel');
  const nextBtn = document.querySelector('.carousel-btn.next');
  const prevBtn = document.querySelector('.carousel-btn.prev');
  const cards = document.querySelectorAll('.blog-card');
  const itemsPerView = 3;
  let index = 0;

  function updateCarousel() {
    const cardWidth = cards[0].offsetWidth + 20; // ancho + gap
    carousel.style.transform = `translateX(-${index * cardWidth}px)`;
  }

  nextBtn.addEventListener('click', () => {
    if (index < cards.length - itemsPerView) {
      index++;
      updateCarousel();
    }
  });

  prevBtn.addEventListener('click', () => {
    if (index > 0) {
      index--;
      updateCarousel();
    }
  });

  window.addEventListener('resize', updateCarousel); // Recalcula si se redimensiona

      </script>
      
  </body>

</html>