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

# 
