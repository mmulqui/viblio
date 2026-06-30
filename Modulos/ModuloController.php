<?php
require_once dirname(__DIR__) . '/Core/AuthGuard.php';
require_once dirname(__DIR__) . '/Modulos/ModuloRepository.php';

/**
 * ModuloController — gestiona módulos por perfil y por usuario.
 */
class ModuloController
{
    private ModuloRepository $repo;

    public function __construct()
    {
        AuthGuard::verificarRol(['bibliotecario']);
        $this->repo = new ModuloRepository();
    }

    // ─── Por perfil ───────────────────────────────────────────────────────────

    public function obtenerPorPerfil(): void
    {
        header('Content-Type: application/json');
        $idPerfil = (int) ($_GET['id_perfil'] ?? 0);
        if ($idPerfil <= 0) {
            echo json_encode(['success' => false, 'message' => 'id_perfil no proporcionado']);
            return;
        }
        $modulos = $this->repo->obtenerPorPerfil($idPerfil);
        echo json_encode(['success' => true, 'modulos' => $modulos]);
    }

    public function guardarPorPerfil(): void
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        $idPerfil  = (int) ($_POST['id_perfil'] ?? 0);
        $idsActivos = $_POST['modulos'] ?? [];

        if ($idPerfil <= 0) {
            echo json_encode(['success' => false, 'message' => 'Perfil inválido']);
            return;
        }
        $this->repo->guardarPorPerfil($idPerfil, $idsActivos);
        echo json_encode(['success' => true, 'message' => 'Módulos del perfil actualizados correctamente']);
    }

    // ─── Por usuario ──────────────────────────────────────────────────────────

    public function obtenerPorUsuario(): void
    {
        header('Content-Type: application/json');
        $idUsuario = (int) ($_GET['id_usuario'] ?? 0);
        if ($idUsuario <= 0) {
            echo json_encode(['success' => false, 'message' => 'id_usuario no proporcionado']);
            return;
        }
        $modulos = $this->repo->obtenerPorUsuario($idUsuario);
        echo json_encode(['success' => true, 'modulos' => $modulos]);
    }

    public function guardarPorUsuario(): void
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        $idUsuario  = (int) ($_POST['id_usuario'] ?? 0);
        $idsActivos = $_POST['modulos'] ?? [];

        if ($idUsuario <= 0) {
            echo json_encode(['success' => false, 'message' => 'Usuario inválido']);
            return;
        }
        $this->repo->guardarPorUsuario($idUsuario, $idsActivos);
        echo json_encode(['success' => true, 'message' => 'Módulos actualizados correctamente']);
    }
}
