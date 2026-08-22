<?php

//Mostrar tabla con productos acumulados, totales y boton de pagar






// Incluimos la configuración para asegurarnos de tener acceso a $_SESSION['carrito']
require_once __DIR__ . '/../config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tu Carrito de Compras</title>
    <link rel="stylesheet" href="style_carrito.css">
    <script src="carrito.js"></script>
    
</head>
<body>




    <div class="titulo"><h1>Carrito de Compras</h1></div>

    <?php if (empty($_SESSION['carrito'])): ?>
        <p>Tu carrito está vacío. <a href="menu_usuario.php">Ir a la tienda</a></p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio Unitario</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                foreach ($_SESSION['carrito'] as $id => $item): 
                    $subtotal = $item['precio'] * $item['cantidad'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                        <td>$<?php echo number_format($item['precio'], 2); ?></td>
                        <td><?php echo $item['cantidad']; ?></td>
                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                        <td>
                            <!-- Enlace para eliminar este producto específico -->
                            <a href="../controlers/carritoController.php?accion=eliminar&id=<?php echo $id; ?>>Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td>Total a Pagar:</td>
                    <td>$<?php echo number_format($total, 2); ?></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <br>
        <!-- Opciones del carrito -->
        <a href="../controlers/carritoController.php?accion=vaciar">Vaciar Carrito</a>
        <a href="checkout.php">Proceder al Pago</a>
    <?php endif; ?>

</body>
</html>


