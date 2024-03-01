<?php
    include("Sesion.php");
    include("Usuario/funciones/conexion.php");

    // Obtener el ID del usuario de la sesión
    $id_usuario = $_SESSION["id"];
    $id_producto = $_POST["id"];
    $stock = $_POST["stock"];
    $cantidad = $_POST["cantidad"];
    $precio = $_POST["costo"];

    // Conectar a la base de datos
    $conexion = conectar();

    // Verificar la conexión
    if ($conexion) {
        // Verificar si hay un pedido abierto para el usuario
        $sql_verificar_pedido_abierto = "SELECT id FROM pedidos WHERE id_usuario = '$id_usuario' AND status = 0";
        $resultado_verificar_pedido = mysqli_query($conexion, $sql_verificar_pedido_abierto);

        if (!$resultado_verificar_pedido) {
            die("Error al verificar el pedido abierto: " . mysqli_error($conexion));
        }

        if (mysqli_num_rows($resultado_verificar_pedido) == 0) {
            // No hay un pedido abierto, crear uno nuevo
            $fecha = date('Y-m-d');
            $sql_nuevo_pedido = "INSERT INTO pedidos (fecha, id_usuario, status) VALUES ('$fecha', '$id_usuario', 0)";

            if (!mysqli_query($conexion, $sql_nuevo_pedido)) {
                die("Error al crear un nuevo pedido: " . mysqli_error($conexion));
            }

            // Obtener el ID del pedido recién creado
            $id_pedido = mysqli_insert_id($conexion);
        } else {
            // Hay un pedido abierto, obtener su ID
            $row = mysqli_fetch_assoc($resultado_verificar_pedido);
            $id_pedido = $row['id'];
        }

        // Verificar si el producto ya está en el pedido
        $sql_verificar_producto_en_pedido = "SELECT id, cantidad FROM pedidos_productos WHERE id_pedido = '$id_pedido' AND id_producto = '$id_producto'";
        $resultado_verificar_producto = mysqli_query($conexion, $sql_verificar_producto_en_pedido);

        if (!$resultado_verificar_producto) {
            die("Error al verificar el producto en el pedido: " . mysqli_error($conexion));
        }

        if (mysqli_num_rows($resultado_verificar_producto) == 0) {
            // El producto no está en el pedido, agregarlo
            $sql_agregar_producto = "INSERT INTO pedidos_productos (id_pedido, id_producto, cantidad, precio) VALUES ('$id_pedido', '$id_producto', '$cantidad', '$precio')";

            if (!mysqli_query($conexion, $sql_agregar_producto)) {
                die("Error al agregar el producto al pedido: " . mysqli_error($conexion));
            }
        } else {
            // El producto ya está en el pedido, actualizar la cantidad
            $row_producto = mysqli_fetch_assoc($resultado_verificar_producto);
            $nueva_cantidad = $cantidad;

            $sql_actualizar_cantidad = "UPDATE pedidos_productos SET cantidad = '$cantidad' WHERE id_pedido = '$id_pedido' AND id_producto = '$id_producto'";
            if (!mysqli_query($conexion, $sql_actualizar_cantidad)) {
                die("Error al actualizar la cantidad del producto en el pedido: " . mysqli_error($conexion));
            }
        }

        echo "Producto agregado al pedido con éxito";

        // Puedes redirigir al usuario a una página de confirmación u otra según tus necesidades
        header("Location: bienvenido.php");
        
    } else {
        echo "Error al conectar a la base de datos";
    }
?>
