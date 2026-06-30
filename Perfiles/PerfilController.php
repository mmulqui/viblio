<?php
require_once dirname(__DIR__) . '/Core/AuthGuard.php';
require_once dirname(__DIR__) . '/Perfiles/Perfil.php';
require_once dirname(__DIR__) . '/Perfiles/PerfilRepository.php';

/**
 * PerfilController — gestión de perfiles/roles del sistema.
 */
class PerfilController
{
    private PerfilRepository $repo;

    public function __construct()
    {
        AuthGuard::verificarRol(['bibliotecario']);
        $this->repo = new PerfilRepository();
    }

    /** Dispatcher: lee $_POST['accion']. */
    public function handle(): void
    {
        $accion = $_POST['accion'] ?? '';
        match ($accion) {
            'agregar'   => $this->agregar(),
            'modificar' => $this->modificar(),
            'eliminar'  => $this->eliminar(),
            default     => $this->redirigirConAlerta('warning', 'Ups', 'Acción no reconocida.'),
        };
    }

    public function agregar(): void
    {
        $tipoPerfil = trim($_POST['tipo_perfil'] ?? '');

        if ($tipoPerfil === '') {
            $this->redirigirConAlerta('warning', 'Ups', 'El nombre del perfil no puede estar vacío.');
            return;
        }

        if ($this->repo->nombreExiste($tipoPerfil)) {
            $this->redirigirConAlerta('warning', 'Ups', 'Ya existe un perfil con ese nombre.');
            return;
        }

        $ok = $this->repo->agregar($tipoPerfil);
        $ok
            ? $this->redirigirConAlerta('success', '¡Éxito!', 'Perfil agregado correctamente.')
            : $this->redirigirConAlerta('error', 'Error', 'No se pudo agregar el perfil.');
    }

    public function modificar(): void
    {
        $idPerfil   = (int) ($_POST['id_perfil']   ?? 0);
        $tipoPerfil = trim($_POST['tipo_perfil'] ?? '');

        if ($idPerfil <= 0 || $tipoPerfil === '') {
            $this->redirigirConAlerta('warning', 'Ups', 'Datos inválidos.');
            return;
        }

        if ($this->repo->nombreExiste($tipoPerfil, $idPerfil)) {
            $this->redirigirConAlerta('warning', 'Ups', 'Ya existe un perfil con ese nombre.');
            return;
        }

        $ok = $this->repo->modificar($idPerfil, $tipoPerfil);
        $ok
            ? $this->redirigirConAlerta('success', '¡Éxito!', 'Perfil modificado correctamente.')
            : $this->redirigirConAlerta('error', 'Error', 'No se pudo modificar el perfil.');
    }

    /** AJAX: devuelve JSON */
    public function eliminar(): void
    {
        header('Content-Type: application/json');
        $idPerfil = (int) ($_POST['id_perfil'] ?? 0);

        if ($idPerfil <= 0) {
            echo json_encode(['ok' => false, 'msg' => 'Perfil inválido.']);
            return;
        }

        if ($this->repo->esPerfilDelSistema($idPerfil)) {
            echo json_encode(['ok' => false, 'msg' => 'No se puede eliminar un perfil base del sistema.']);
            return;
        }

        $ok = $this->repo->darBajaLogica($idPerfil);
        echo json_encode($ok
            ? ['ok' => true,  'msg' => 'Perfil eliminado (baja lógica) correctamente.']
            : ['ok' => false, 'msg' => 'No se pudo eliminar el perfil.']
        );
    }

    private function redirigirConAlerta(string $tipo, string $titulo, string $msg): void
    {
        $_SESSION['alerta'] = compact('tipo', 'titulo', 'msg');
        header('Location: ../views/menu.php');
        exit;
    }
}
