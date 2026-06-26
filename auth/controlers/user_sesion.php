<?php
function mismoUsuario(int $id_actual, int $id_fila): bool {
    return $id_actual === $id_fila;
}