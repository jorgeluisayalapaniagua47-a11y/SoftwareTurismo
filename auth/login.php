
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Iniciar Sesión - Agencia de Viajes</title>
    <link rel="stylesheet" href="../css/login.css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <a href="../index.html" aria-label="Ir a la página de inicio" class="titulo">
                    Agencia de turismo
                </a>
            </div>
        </nav>
    </header>

    <main>
    <section id="login-section">
            <div class="login-container">
                <figure class="login-image">
                    <img src="../assets/images/intro.jpg" 
                         alt="" />
                </figure>

                <div class="login-form">
                    <!-- Formulario de Inicio de Sesión -->
                    <form id="login-form" action="registro.php" method="POST" aria-labelledby="login-form-heading">
                        <h2 id="login-form-heading">Iniciar Sesión</h2>

                        <input type="hidden" name="accion" value="login" />

                        <label for="login-nombre">Nombre Completo:</label>
                        <input type="text" id="login-nombre" name="nombre" required aria-describedby="nombre-help" />
                        <span id="nombre-help">Introduce tu nombre completo registrado.</span>

                        <label for="login-correo">Correo Electrónico:</label>
                        <input type="email" id="login-correo" name="correo" required aria-describedby="correo-help" />
                        <span id="correo-help">Introduce tu correo registrado.</span>

                        <label for="login-password">Contraseña:</label>
                        <div class="password-container">
                            <input type="password" id="login-password" name="password" required aria-describedby="password-help" />
                            <button type="button" class="toggle-password" data-target="login-password" aria-label="Mostrar u ocultar contraseña">👁️</button>
                        </div>
                        <span id="password-help">Introduce tu contraseña para acceder.</span>

                        <button type="submit" id="login-button">Ingresar</button>

                        <p>
                            ¿No tienes una cuenta?
                            <a href="#" id="create-account-link" role="button">Crea una cuenta</a>
                        </p>
                    </form>

                    <!-- Formulario de Registro -->
                    <form id="create-account-form" action="registro.php" method="POST" style="display: none" aria-labelledby="create-account-form-heading">
                        <h2 id="create-account-form-heading">Crear Cuenta</h2>

                        <input type="hidden" name="accion" value="registro" />

                        <label for="registro-nombre">Nombre Completo:</label>
                        <input type="text" id="registro-nombre" name="nombre" required />

                        <label for="registro-correo">Correo Electrónico:</label>
                        <input type="email" id="registro-correo" name="correo" required />

                        <label for="telefono">Número de Teléfono:</label>
                        <input type="tel" id="telefono" name="telefono" />

                        <label for="direccion">Dirección:</label>
                        <input type="text" id="direccion" name="direccion" />

                        <label for="registro-password">Contraseña:</label>
                        <div class="password-container">
                            <input type="password" id="registro-password" name="password" required />
                            <button type="button" class="toggle-password" data-target="registro-password" aria-label="Mostrar u ocultar contraseña">👁️</button>
                        </div>

                        <label for="confirm-password">Confirmar Contraseña:</label>
                        <div class="password-container">
                            <input type="password" id="confirm-password" name="confirm-password" required />
                            <button type="button" class="toggle-password" data-target="confirm-password" aria-label="Mostrar u ocultar contraseña">👁️</button>
                        </div>

                        <button type="submit">Crear Cuenta</button>

                        <p>
                            ¿Ya tienes una cuenta?
                            <a href="#" id="login-link" role="button">Inicia sesión</a>
                        </p>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Tu Compañía. Todos los derechos reservados.</p>
    </footer>

    <script>
        // Referencias a los formularios y enlaces
        const loginForm = document.getElementById("login-form");
        const createAccountForm = document.getElementById("create-account-form");
        const createAccountLink = document.getElementById("create-account-link");
        const loginLink = document.getElementById("login-link");

        // Mostrar el formulario de registro y ocultar el de inicio de sesión
        createAccountLink.addEventListener("click", function (event) {
            event.preventDefault(); // Evita el comportamiento predeterminado del enlace
            loginForm.style.display = "none"; // Oculta el formulario de inicio de sesión
            createAccountForm.style.display = "block"; // Muestra el formulario de registro
        });

        // Mostrar el formulario de inicio de sesión y ocultar el de registro
        loginLink.addEventListener("click", function (event) {
            event.preventDefault(); // Evita el comportamiento predeterminado del enlace
            createAccountForm.style.display = "none"; // Oculta el formulario de registro
            loginForm.style.display = "block"; // Muestra el formulario de inicio de sesión
        });
    // Alternar visibilidad de contraseña
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const isPassword = input.getAttribute('type') === 'password';

            input.setAttribute('type', isPassword ? 'text' : 'password');
            button.textContent = isPassword ? '⚫' : '🔘';
        });
    });
    </script>
</body>
</html>
