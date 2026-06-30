<?php
require_once dirname(__DIR__) . '/Core/AuthGuard.php';
require_once dirname(__DIR__) . '/Usuarios/Usuario.php';
require_once dirname(__DIR__) . '/Usuarios/UsuarioRepository.php';
require_once dirname(__DIR__) . '/Usuarios/UsuarioService.php';
require_once dirname(__DIR__) . '/Perfiles/PerfilRepository.php';

/**
 * UsuarioController — maneja los endpoints HTTP de gestión de usuarios.
 *
 * Acciones disponibles:
 *  - obtener($dni)  GET  → JSON con datos del usuario
 *  - modificar()    POST → redirige con alerta SweetAlert
 *  - eliminar($dni) GET  → redirige con alerta SweetAlert
 */
class UsuarioController
{
    private UsuarioRepository $repo;
    private UsuarioService    $service;

    public function __construct()
    {
        AuthGuard::verificarRol(['bibliotecario']);
        $this->repo    = new UsuarioRepository();
        $this->service = new UsuarioService();
    }

    // ─── Entrada principal ────────────────────────────────────────────────────

    /** Dispatcher: lee ?accion= o la ruta que llama directamente al método. */
    public function handle(): void
    {
        $accion = $_POST['accion'] ?? $_GET['accion'] ?? '';
        match ($accion) {
            'obtener'   => $this->obtener(),
            'modificar' => $this->modificar(),
            'eliminar'  => $this->eliminar(),
            default     => $this->redirigir(),
        };
    }

    // ─── Acciones públicas (llamadas desde los entry points) ─────────────────

    public function obtener(): void
    {
        header('Content-Type: application/json');
        $dni = $_GET['dni'] ?? '';
        if (!$dni) {
            echo json_encode(['success' => false, 'message' => 'DNI no proporcionado']);
            return;
        }
        $usuario = $this->repo->obtenerPorDni($dni);
        echo json_encode($usuario
            ? ['success' => true,  'usuario' => $usuario]
            : ['success' => false, 'message' => 'Usuario no encontrado']
        );
    }

    public function modificar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir();
            return;
        }

        $idLogueado = (int) $_SESSION['id_usuario'];
        $idEditar   = (int) ($_POST['id_usuario'] ?? 0);

        if (AuthGuard::esElMismoUsuario($idLogueado, $idEditar)) {
            $this->alerta('warning', 'Ups', 'No podés editar tu propio usuario.');
            return;
        }

        // Verificar que el rol/perfil exista
        $perfilRepo = new PerfilRepository();
        $perfil     = $perfilRepo->obtenerPorTipoPerfil($_POST['rol'] ?? '');
        if (!$perfil) {
            $this->alerta('warning', 'Ups', 'Perfil no encontrado.');
            return;
        }

        // Contraseña (opcional)
        $hashContrasenia = null;
        if (!empty($_POST['contrasenia'])) {
            if (!$this->service->contrasenasCoinciden($_POST['contrasenia'], $_POST['confirmar_contrasenia'] ?? '')) {
                $this->alerta('warning', 'Ups', 'Las contraseñas no coinciden.');
                return;
            }
            $hashContrasenia = $this->service->hashContrasenia($_POST['contrasenia']);
        }

        // Verificar duplicados
        if ($this->repo->emailExisteExcluyendo($_POST['email'], $idEditar)) {
            $this->alerta('warning', 'Ups', 'El email ya está registrado en otro usuario.');
            return;
        }
        if ($this->repo->dniExisteExcluyendo($_POST['dni'], $idEditar)) {
            $this->alerta('warning', 'Ups', 'El DNI ya está registrado en otro usuario.');
            return;
        }

        $idPersona = $this->repo->obtenerIdPersona($idEditar);
        if (!$idPersona) {
            $this->alerta('warning', 'Ups', 'Usuario no encontrado.');
            return;
        }

        $ok = $this->repo->modificar($idEditar, $idPersona, (int) $perfil['id_perfil'], $_POST, $hashContrasenia);
        $ok
            ? $this->alerta('success', '¡Éxito!', 'Usuario modificado correctamente.')
            : $this->alerta('error', 'Error', 'No se pudo modificar el usuario.');
    }

    public function eliminar(): void
    {
        $dni = $_GET['dni'] ?? '';
        if (!$dni) {
            $this->alerta('warning', 'Ups', 'DNI no proporcionado.');
            return;
        }
        $ok = $this->repo->darBajaLogica($dni);
        $ok
            ? $this->alerta('success', '¡Éxito!', 'Usuario eliminado correctamente.')
            : $this->alerta('warning', 'Ups', 'No se encontró una persona con ese DNI.');
    }

    // ─── Helpers privados ────────────────────────────────────────────────────

    private function alerta(string $tipo, string $titulo, string $msg): void
    {
        $_SESSION['alerta'] = compact('tipo', 'titulo', 'msg');
        $this->redirigir();
    }

    private function redirigir(): never
    {
        header('Location: ../views/menu.php');
        exit;
    }
}
