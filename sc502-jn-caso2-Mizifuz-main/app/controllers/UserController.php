<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/User.php';

class UserController
{
    private $model;

    public function __construct()
    {
        $database = new Database();
        $db = $database->connect();
        $this->model = new User($db);
    }

    public function showLogin()
    {
        require __DIR__ . '/../views/login.php';
    }

    public function showRegistro()
    {
        require __DIR__ . '/../views/register.php';
    }

    public function login()
    {
        header('Content-Type: application/json');

        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';

        if ($username === '' || $password === '') {
            echo json_encode([
                'response' => '01',
                'message' => 'Debe completar todos los campos'
            ]);
            return;
        }

        $user = $this->model->login($username);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['user'] = $user['username'];
            $_SESSION['rol'] = $user['rol'];

            echo json_encode([
                'response' => '00',
                'rol' => $user['rol'],
                'message' => 'Login exitoso'
            ]);
        } else {
            echo json_encode([
                'response' => '01',
                'message' => 'Usuario o contraseña incorrectos'
            ]);
        }
    }

    public function registro()
    {
        header('Content-Type: application/json');

        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $passwordPlano = isset($_POST['password']) ? trim($_POST['password']) : '';

        if ($username === '' || $passwordPlano === '') {
            echo json_encode([
                'response' => '01',
                'message' => 'Debe completar todos los campos'
            ]);
            return;
        }

        if ($this->model->existsByUsername($username)) {
            echo json_encode([
                'response' => '01',
                'message' => 'Ese nombre de usuario ya existe'
            ]);
            return;
        }

        $password = password_hash($passwordPlano, PASSWORD_DEFAULT);
        $result = $this->model->create($username, $password);

        if ($result) {
            echo json_encode([
                'response' => '00',
                'message' => 'Registro exitoso'
            ]);
        } else {
            echo json_encode([
                'response' => '01',
                'message' => 'Error al registrar el usuario'
            ]);
        }
    }

    public function logout()
    {
        header('Content-Type: application/json');

        $_SESSION = [];

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        echo json_encode([
            'response' => '00',
            'message' => 'Sesión cerrada'
        ]);
    }
}