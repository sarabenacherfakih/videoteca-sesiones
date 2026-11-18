<?php
declare(strict_types=1);

namespace Controllers;

use Core\Pages;
use Core\Session;
use Models\UserModel;

/**
 * Autenticación: comprueba credenciales y abre/cierra la sesión.
 * NO es el controlador de UserModel (para eso está UserController).
 */
final class AuthController
{
    public function showLogin(): void
    {
        (new Pages())->render('auth/login', ['error' => null, 'info' => null]);
    }

    public function login(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = (new UserModel())->findByEmail($email);

        // ¡Ojo aquí! Usamos '$user['hash']' porque esa es la clave correcta que viene del modelo
        if ($user && password_verify($password, $user['hash'])) {
            Session::regenerar(); 
            Session::set('user_id', $user['id']);
            redirect('perfil');
        }
        
        // Si falla, redirige al login
        redirect('login');
    }

    public function logout(): void
    {
        Session::destruir();
        redirect('login');
    }
}