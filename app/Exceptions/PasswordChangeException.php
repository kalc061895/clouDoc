<?php

declare(strict_types=1);

namespace App\Exceptions;

final class PasswordChangeException extends \DomainException
{
    public function __construct(public readonly array $errors)
    {
        parent::__construct('Revise los datos para cambiar su contraseña.');
    }
}
