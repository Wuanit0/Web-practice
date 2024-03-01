<?php include('funciones/Sesion.php');?>

<html>
<head>
    <title>Finalizar Compra</title>
    <script src="js/jquery-3.3.1.min.js" ></script>
    <link rel="stylesheet" href="css/style_carrito.css">

</head>
<body>
    <?php include('templates/header.php');
    include("funciones/conexion.php");
    $conexion = conectar();
?>
<form name="formulario" method="post" action="funciones/finalizar.php" >
    <?php
    $id_usuario = $_SESSION["id"];
    $id_pedido="";
    $total=0;

    $sql = "SELECT * FROM pedidos WHERE id_usuario = '$id_usuario' AND status = 0";
    $res=$conexion->query($sql);
    $num= $res-> num_rows;
    if ($num===1){
        $row = $res->fetch_array();
        $id_pedido = $row['id'];
    }

    ?>
    <input type="hidden" name="id_pedido" value="<?php echo $id_pedido; ?>">

    <table border="1px">
        <tr class="encabezado">
            <td>Productos</td>
            <td>Cantidad</td>
            <td>Costo Unico</td>
            <td>Subtotal</td> 
            <td>Total</td>                          
        </tr>
        <?php
        if ($id_pedido != "") {
            $total = 0; // Inicializar el total

            $sql = "SELECT * FROM pedidos_productos WHERE id_pedido = '$id_pedido'";
            $res = $conexion->query($sql);
            $num = $res->num_rows;

            while ($row = $res->fetch_array()) {
                $id_producto = $row['id_producto'];
                
                $sql_producto = "SELECT * FROM productos WHERE id = '$id_producto'";
                $res_producto = $conexion->query($sql_producto);
                $num_producto = $res_producto->num_rows;

                if ($num_producto === 1) {
                    $row_producto = $res_producto->fetch_array();
                    $producto = $row_producto['nombre'];
                    $stock = $row_producto['stock'];
                }

                $cantidad = $row['cantidad'];
                $precio = $row['precio'];

                if ($cantidad > 0) {
                    ?>
                    <input type="hidden" name="cantidad" value="<?php echo $cantidad; ?>">

                    <input type="hidden" name="id_producto" value="<?php echo $id_producto; ?>">
                    <tr id="Fila<?php echo $id_producto; ?>">
                        <td><?php echo $producto ?></td>
                        <td> <?php echo $cantidad ?> </td>
                        <td><?php echo $precio ?>$</td>
                        <td><?php echo $precio * $cantidad ?>$</td>
                        <td class="encabezado"></td>
                    <?php
                    $subtotal = $precio * $cantidad;
                    $total += $subtotal; // Agregar al total
                }
            }
        }
        ?>
        <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
            <td><?php echo $total ?>$ total</td>
        </tr>
        </table>
        <input type="submit" value="Finalizar">

        <a href="usuario_carrito.php">Regresar</a>
    </form>
    <?php
    include("templates/footer.php")
?>
</body>



</html>
