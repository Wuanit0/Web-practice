<?php include('funciones/Sesion.php');?>

<html>
<head>
    <title>Editar</title>
    <script src="js/jquery-3.3.1.min.js" ></script>
    <link rel="stylesheet" href="css/style_carrito.css">

</head>
<body>
    <?php include('templates/header.php');
    include("funciones/conexion.php");
    $conexion = conectar();
?>
<form name="formulario" method="post" action="usuario_carrito.php" >
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
    <table border="1px">
        <tr class="encabezado">
            <td>Productos</td>
            <td>Cantidad</td>
            <td>Costo Unico</td>
            <td>Subtotal</td> 
            <td>Total</td>              
            <td></td>              
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
                    <tr id="Fila<?php echo $id_producto; ?>">
                        <td><?php echo $producto ?></td>
                        <td>
                        <select name="cantidad" id="cantidad<?php echo $id_producto; ?>" onchange="actualizar(<?php echo $id_pedido; ?>, <?php echo $id_producto; ?>)">
                            <option value="<?php echo $cantidad ?>"><?php echo $cantidad ?></option>
                                <?php
                                for ($i = 1; $i <= $stock; $i++) {
                                    echo "<option value=\"$i\">$i</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td><?php echo $precio ?>$</td>
                        <td><?php echo $precio * $cantidad ?>$</td>
                        <td class="encabezado"></td>
                        <td><a href="javascript:void(0);" onclick="eliminar(<?php echo $id_pedido; ?>, <?php echo $id_producto; ?>)">Eliminar</a></td>
                    </tr>
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
        <a href="usuario_carrito2.php">Continuar</a>
        <a href="../bienvenido.php">Regresar</a>
    </form>
    <?php
    include("templates/footer.php")
?>
</body>



<script>
    function actualizar(id_pedido, id_producto){
        var cantidad = $('#cantidad' + id_producto).val();
        console.log(cantidad +'Pedido' +id_pedido+ 'Producto'+ id_producto);
        $.ajax({
                url: 'funciones/actualizar.php',
                type: 'post',
                data: {
                    cantidad:cantidad,
                    id_pedido: id_pedido,
                    id_producto: id_producto
                },
                success: function (res) {
                    console.log(res);
                    // Puedes hacer algo más con la respuesta si es necesario
                    location.reload();
                },
                error: function () {
                    alert('Error: archivo no encontrado...');
                }
            });
}   
    function eliminar(id_pedido, id_producto) {
        var opcion = confirm("¿Deseas borrar el producto con el id " + id_producto + "?");
        console.log("Entró");
        if (opcion) {
            $.ajax({
                url: 'funciones/elimina_producto.php',
                type: 'post',
                data: {
                    id_pedido: id_pedido,
                    id_producto: id_producto
                },
                success: function (res) {
                    console.log(res);
                    // Puedes hacer algo más con la respuesta si es necesario
                    $('#Fila' + id_producto).hide();
                    $('#mensaje').html('Producto eliminado con éxito');
                    setTimeout(function () {
                        $('#mensaje').html('');
                    }, 5000);
                },
                error: function () {
                    alert('Error: archivo no encontrado...');
                }
            });
        }
    }
    </script>
</html>
