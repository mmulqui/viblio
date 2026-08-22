<?php
// controlers/ConfirmarCompraController.php
require_once __DIR__ . '../config.php';
// Asegúrate de requerir el archivo de tu clase Database si no tiene autoloader
require_once __DIR__ . '../Database.php'; 

if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header("Location: /viblio/views/carrito.php");
    exit;
}

try {
    // 1. Obtenemos tu conexión única de mysqli
    $db = Database::getConexion();

    // 2. EMPEZAMOS LA TRANSACCIÓN (Se activa el borrador)
    $db->begin_transaction();

    $usuario_id = $_SESSION['usuario_id'] ?? 1; 
    $total_carrito = 0;

    foreach ($_SESSION['carrito'] as $item) {
        $total_carrito += $item['precio'] * $item['cantidad'];
    }

    // --- PASO A: Crear la cabecera de la Orden ---
    $sql_orden = "INSERT INTO ordenes (usuario_id, total, fecha) VALUES (?, ?, NOW())";
    $stmt_orden = $db->prepare($sql_orden);
    $stmt_orden->bind_param("id", $usuario_id, $total_carrito);
    $stmt_orden->execute();
    
    // Obtenemos el ID de la orden que se acaba de insertar
    $orden_id = $db->insert_id;

    // --- PASO B: Recorrer el carrito para restar stock y registrar detalles ---
    foreach ($_SESSION['carrito'] as $id_libro => $item) {
        
        // 1. Validamos stock disponible antes de restar
        $sql_check = "SELECT stock FROM libros WHERE id = ?";
        $stmt_check = $db->prepare($sql_check);
        $stmt_check->bind_param("i", $id_libro);
        $stmt_check->execute();
        $resultado = $stmt_check->get_result();
        $libro = $resultado->fetch_assoc();

        if (!$libro || $libro['stock'] < $item['cantidad']) {
            // Si falta stock de algún libro, tiramos una excepción para ir directo al catch
            throw new Exception("No hay suficiente stock para el libro: " . $item['nombre']);
        }

        // 2. Restamos el stock
        $sql_stock = "UPDATE libros SET stock = stock - ? WHERE id = ?";
        $stmt_stock = $db->prepare($sql_stock);
        $stmt_stock->bind_param("ii", $item['cantidad'], $id_libro);
        $stmt_stock->execute();

        // 3. Guardamos el detalle
        $sql_detalle = "INSERT INTO detalles_orden (orden_id, libro_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
        $stmt_detalle = $db->prepare($sql_detalle);
        $stmt_detalle->bind_param("iiid", $orden_id, $id_libro, $item['cantidad'], $item['precio']);
        $stmt_detalle->execute();
    }

    // 3. SI TODO SALIÓ BIEN: Guardamos los cambios permanentemente en MySQL
    $db->commit();

    // Vaciamos el carrito de la sesión
    unset($_SESSION['carrito']);

    header("Location: /viblio/views/compra_exitosa.php");
    exit;

} catch (Exception $e) {
    // 4. ¡EL ROLLBACK!: Si falló cualquier consulta o saltó la alerta de stock,
    // mysqli deshace absolutamente todo lo ejecutado en esta petición.
    if (isset($db)) {
        $db->rollback();
    }

    $_SESSION['error_compra'] = "Error al procesar la compra: " . $e->getMessage();
    header("Location: /viblio/views/carrito.php");
    exit;
}