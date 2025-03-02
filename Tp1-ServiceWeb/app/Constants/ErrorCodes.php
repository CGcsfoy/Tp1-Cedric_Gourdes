<?php

namespace App\Constants;

class ErrorCodes
{
    // Erreurs authentification
    public const UNAUTHORIZED = 401;
    public const FORBIDDEN = 403;

    // Erreurs utilisateur
    public const USER_NOT_FOUND = 404;
    public const USER_ALREADY_EXISTS = 409;

    // Erreurs validation
    public const VALIDATION_FAILED = 422;

    // Erreurs serveur
    public const SERVER_ERROR = 500;
    public const DATABASE_ERROR = 503;
}
