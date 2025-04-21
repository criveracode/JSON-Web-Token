<h1 align="center">JSON Web Token</h1>

# Creación Backend

    % laravel new backend

# Instalación API

    % php artisan install:api

# Instalación JWT

    % composer require tymon/jwt-auth

# Publicar configuración
 
    %  php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

# Generar secret key

    % php artisan jwt:secret

# Agregar -> /config/auth.php

    'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
    ],

# Modificar /app/Models/User.php

    % Agregamos la clase User.php adjunta.

# Creación controlador Auth

    % php artisan make:controller AuthController

# Modificar /app/Http/Controllers/AuthController.php

    % Agregamos la clase AuthController.php adjunta.

# Modificar /routes/api.php

    % Agregamos la clase api.php adjunta.

# Correr servidor

    % php artisan serve