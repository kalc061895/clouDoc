<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\PasswordChangeException;
use CodeIgniter\Shield\Authentication\Passwords;
use CodeIgniter\Shield\Models\UserModel;

/** Changes only the actor's email/password identity, using Shield's native APIs. */
class PasswordChangeService
{
    public function __construct(private ?UserModel $users = null, private ?Passwords $passwords = null)
    {
    }

    public function cambiar(int $actorId, string $current, string $password, string $confirmation): void
    {
        if ($actorId < 1) {
            throw new \InvalidArgumentException('Se requiere un usuario autenticado.');
        }
        if ($current === '' || $password === '' || $confirmation === '') {
            throw new PasswordChangeException(['current_password' => 'Complete todos los campos.']);
        }
        if (strlen($current) > 255 || strlen($password) > 255 || strlen($confirmation) > 255) {
            throw new PasswordChangeException(['password' => 'La contraseña es demasiado larga.']);
        }
        if ($password !== $confirmation) {
            throw new PasswordChangeException(['password_confirm' => 'Las contraseñas no coinciden.']);
        }
        $users = $this->users ?? model(config('Auth')->userProvider);
        $passwords = $this->passwords ?? service('passwords');
        $user = $users->findById($actorId);
        if ($user === null || ! $user->password_hash || ! $passwords->verify($current, $user->password_hash)) {
            throw new PasswordChangeException(['current_password' => 'La contraseña actual no es correcta.']);
        }
        if ($passwords->verify($password, $user->password_hash)) {
            throw new PasswordChangeException(['password' => 'La nueva contraseña debe ser diferente de la actual.']);
        }
        if (config('Auth')->hashAlgorithm === PASSWORD_BCRYPT && strlen($password) > 72) {
            throw new PasswordChangeException(['password' => 'La contraseña no debe superar 72 bytes.']);
        }
        $result = $passwords->check($password, $user);
        if (! $result->isOK()) {
            throw new PasswordChangeException(['password' => $result->reason() ?? 'La contraseña no cumple la política de seguridad.']);
        }
        $user->password = $password;
        try {
            // One identity write: hashing and storage are owned by Shield, not the view/controller.
            if (! $user->saveEmailIdentity()) {
                throw new \RuntimeException('No se pudo actualizar la identidad.');
            }
        } finally {
            $user->password = '';
        }
    }
}
