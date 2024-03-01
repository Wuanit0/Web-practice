<?php
include("conexion.php");

// actualizar_cantidad.php

$id = $_POST['id_pedido'];
$cantidad = $_POST['cantidad'];

$conexion = conectar();

// Actualiza la cantidad en la base de datos
$sql = "UPDATE pedidos SET status = 1 WHERE id = $id ";
$res = $conexion->query($sql);

if ($res) {
    echo "Compra finalizada";
} else {
    echo "Error al finalizar la compra";
}

$sql = "SELECT * FROM pedidos_productos WHERE id_pedido = $id ";
$res_id = $conexion->query($sql);
$num = $res_id->num_rows;


while ($row = $res_id->fetch_array()) {

    $id_producto = $row['id_producto'];
    $cantidad=$row['cantidad'];
    echo "<br>";
    echo "ID SQL: " . $id_producto;
    echo "<br>";

    $sql_producto = "SELECT stock FROM productos WHERE id = $id_producto";

    $res_producto = $conexion->query($sql_producto);
    $num_producto = $res_producto->num_rows;

    if ($num_producto === 1) {
        $row = $res_producto->fetch_array();
        $stock = $row['stock'];
        $actualiza_stock = intval($stock) - intval($cantidad);
        echo "<br>";
        echo "Cantidad SQL: " . $cantidad;
        echo "<br>";
        echo "Stock SQL: " . $stock;
        echo "<br>";

        echo "Consulta SQL: " . $actualiza_stock;
        echo "<br>";

        $sql_stock = "UPDATE productos SET stock = $actualiza_stock WHERE id = $id_producto";
        echo "<br>";
        echo "Cantidad SQL_stock: " . $sql_stock;
        echo "<br>";
        $res = $conexion->query($sql_stock);

        if ($res) {
            echo " Stock actualizado";
            header("Location: ../../bienvenido.php");
        } else {
            echo "Error al actualizar el stock";
        }
    }}
// Actualiza el stock en la base de datos


// Cierra la conexión
$conexion->close();
?>