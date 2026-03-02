
## Estructura de Carpetas

```
turismo/
├── admin/              # Archivos del panel de administrador
│   ├── administrador.php
│   ├── usuarios.php
│   ├── reporte.php
│   └── modificar MVC.php
│
├── auth/               # Archivos de autenticación
│   ├── login.php
│   ├── registro.php
│   └── logout.php
│
├── config/             # Archivos de configuración
│   └── conexion.php
│
├── css/                # Archivos de estilos CSS
│   ├── bitacora.css
│   ├── blog.css
│   ├── destinos.css
│   ├── estilo.css
│   ├── login.css
│   ├── mi-cuenta.css
│   ├── mis_reservas.css
│   ├── modificar_MVC.css
│   ├── pagos.css
│   ├── paises.css
│   ├── politica.css
│   └── reporte.css
│
├── assets/             # Recursos estáticos
│   └── images/         # Imágenes del proyecto
│       ├── icons/
│       ├── img banderas/
│       └── img operadores_turisticos/
│
├── operador/           # Archivos del panel de operador
│   ├── operador.php
│   ├── o_blog.php
│   ├── o_mi-cuenta.php
│   ├── o_mis_reservas.php
│   ├── o_modificar MVC.php
│   ├── o_pagos.php
│   ├── o_paises.php
│   └── o_politica.php
│
├── process/            # Archivos de procesamiento (backend)
│   ├── cancelar_reserva.php
│   ├── crear_reserva.php
│   ├── guardar_pago.php
│   └── procesar_pago.php
│
├── public/             # Páginas públicas
│   ├── bitacora.php
│   ├── blog.php
│   ├── destinos.php
│   ├── paises.php
│   └── politica.php
│
└── usuario/            # Archivos del panel de usuario
    ├── mi-cuenta.php
    ├── mis_reservas.php
    ├── pagos.php
    ├── u_blog.php
    ├── u_destinos.php
    ├── u_mi-cuenta.php
    ├── u_mis_reservas.php
    ├── u_pagos.php
    ├── u_paises.php
    └── u_politica.php
```


### Ejemplos de uso:

```php
// Incluir conexión desde cualquier subcarpeta
include '../config/conexion.php';

// Enlazar CSS
<link rel="stylesheet" href="../css/estilo.css">

// Referenciar imagen
<img src="../assets/images/images/intro.jpg">

// Redirigir a login
header("Location: ../auth/login.php");

// Formulario a procesamiento
<form action="../process/procesar_pago.php" method="POST">
```

## Notas Importantes


 Los archivos de procesamiento están centralizados en `/process`.
 Los recursos estáticos (CSS e imágenes) están organizados en carpetas dedicadas.
 La autenticación está separada en su propia carpeta `/auth`.
 Cada rol (admin, operador, usuario) tiene su propia carpeta con sus archivos específicos.


