<?php include('Sesion.php');?>

<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <link rel="stylesheet" href="css/style_productos.css">
</head>

<body>
    <?php 
    include('templates/header_usuario.php');
    function conectar()
    {
    $server = "localhost:3307";
    $user = "root";
    $pass = "";
    $db = "empresa";

    // Crear conexión
    $conexion = mysqli_connect($server, $user, $pass, $db);

    // Verificar conexión
    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    return $conexion;
    }
    $conexion = conectar();
    ?>

        <div class="productos">
            <table>
                <?php
                    $contador=0;
                    for ($j = 0; $j < 15; $j++) {
                        echo "<tr>"; // Inicia una nueva fila en cada iteración

                        for ($i = 0; $i < 3; $i++) {
                            $contador++;  
                            $id = $contador + 7;

                            $sql = "SELECT * FROM productos WHERE id = '$id' AND status = 1 AND eliminado = 0";
                            $res=$conexion->query($sql);
                            $num= $res-> num_rows;

                            if ($num===1){
                                $row      =     $res->fetch_array();
                                $id       =     $row['id'];
                                $nombre   =     $row['nombre'];
                                $costo    =     $row['costo'];
                                $archivo_producto  =     $row['archivo_n'];
                            }

                            if ($contador <= 15) {
                                ?>
                                <td>
                                    <a href="ver_producto.php?id=<?php echo $row['id']; ?>">
                                        <img src="archivos/<?php echo $archivo_producto?>" alt="Descripción de la imagen"><br><br>
                                        <label for="">Producto: <?php echo $nombre?></label><br>
                                        <label for="">Precio: <?php echo $costo?>$</label>
                                    </a>
                                </td>
                                <?php
                            }
                        }
                        echo "</tr>"; // Cierra la fila después de dos columnas
                    }
                ?>
            </table>        
        </div>
    </div>
    <?php
    include("templates/footer.php")
?>
</body>
</html>