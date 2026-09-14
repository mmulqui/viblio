<?php
require_once dirname(__DIR__) . '/Core/Database.php';

class EstadisticaRepository
{
    private mysqli $conexion;

    public function __construct()
    {
        $this->conexion = Database::getConexion();
    }

    public function prestamosPorEstado(): array
    {
        $sql = "SELECT ep.descripcion AS estado, COUNT(*) AS cantidad
                FROM prestamo p
                INNER JOIN estado_prestamo ep ON p.id_estado = ep.id_estado
                GROUP BY ep.descripcion";
        $resultado = $this->conexion->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function prestamosPorMes(): array
    {
        $sql = "SELECT DATE_FORMAT(fecha_prestamo, '%Y-%m') AS mes, COUNT(*) AS cantidad
                FROM prestamo
                WHERE fecha_prestamo >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY mes
                ORDER BY mes ASC";
        $resultado = $this->conexion->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function topLibrosMasPrestados(int $limite = 5): array
    {
        $sql = "SELECT l.titulo, COUNT(p.id_libro) AS cantidad
                FROM prestamo p
                INNER JOIN libro l ON p.id_libro = l.id_libro
                GROUP BY p.id_libro, l.titulo
                ORDER BY cantidad DESC
                LIMIT ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $limite);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $data = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $data;
    }

    public function reservasPorEstado(): array
    {
        $sql = "SELECT er.descripcion AS estado, COUNT(*) AS cantidad
                FROM reserva r
                INNER JOIN estado_reserva er ON r.id_estado = er.id_estado
                GROUP BY er.descripcion";
        $resultado = $this->conexion->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function usuariosPorRol(): array
    {
        $sql = "SELECT pf.tipo_perfil AS rol, COUNT(*) AS cantidad
                FROM usuario u
                INNER JOIN perfil pf ON u.id_perfil = pf.id_perfil
                WHERE u.activo = 1
                GROUP BY pf.tipo_perfil";
        $resultado = $this->conexion->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerTodo(): array
    {
        return [
            'prestamosPorEstado' => $this->prestamosPorEstado(),
            'prestamosPorMes'    => $this->prestamosPorMes(),
            'topLibros'          => $this->topLibrosMasPrestados(5),
            'reservasPorEstado'  => $this->reservasPorEstado(),
            'usuariosPorRol'     => $this->usuariosPorRol(),
        ];
    }
}