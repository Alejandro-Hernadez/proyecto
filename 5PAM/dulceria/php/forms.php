<?php
// Incluir el archivo de conexión
include 'conexion/abrirCon.php';

// Procesamiento del formulario
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['tipo_formulario'])) {
        $tipo_formulario = $_POST['tipo_formulario'];

        if ($tipo_formulario == 'cliente') {
            // Inserción de cliente
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $telefono = $_POST['telefono'];
            $email = $_POST['email'];

            $sql = "INSERT INTO Cliente (nombre, apellido, telefono, email) VALUES ('$nombre', '$apellido', '$telefono', '$email')";
            if ($conectar->query($sql) === TRUE) {
                $cliente_id = $conectar->insert_id; // Obtiene el ID del cliente registrado
                $message = "Cliente registrado exitosamente. Tu ID es: " . $cliente_id;

                // Generar ticket
                $ticket = "
                <div>
                    <h1>Ticket de Registro</h1>
                    <p><strong>ID del Cliente:</strong> $cliente_id</p>
                    <p><strong>Nombre:</strong> $nombre</p>
                    <p><strong>Apellido:</strong> $apellido</p>
                    <p><strong>Teléfono:</strong> $telefono</p>
                    <p><strong>Email:</strong> $email</p>
                </div>
                ";
            } else {
                $message = "Error al registrar cliente: " . $conectar->error;
            }
        } elseif ($tipo_formulario == 'producto') {
            // Inserción de producto
            $id_producto = $_POST['id_producto'];
            $nombre_producto = $_POST['nombre_producto'];
            $precio = $_POST['precio'];
            $stock = $_POST['stock'];
            $id_proveedor = $_POST['id_proveedor'];

            $sql = "INSERT INTO Productos (id_producto, nombre_producto, precio, stock, id_proveedor) VALUES ('$id_producto','$nombre_producto', $precio, $stock, $id_proveedor)";
            if ($conectar->query($sql) === TRUE) {
                $message = "Producto registrado exitosamente.";
            } else {
                $message = "Error al registrar producto: " . $conectar->error;
            }
        } elseif ($tipo_formulario == 'proveedor') {
            // Inserción de proveedor
            $nombre_proveedor = $_POST['nombre_proveedor'];
            $telefono_proveedor = $_POST['telefono_proveedor'];
            $direccion_proveedor = $_POST['direccion_proveedor'];
            $id_proveedor = $_POST['id_proveedor'];
            $sql = "INSERT INTO `proveedor`(`id_proveedor`, `nombre_proveedor`, `telefono`, `direccion`) VALUES ('$id_proveedor','$nombre_proveedor','$telefono_proveedor','$direccion_proveedor')";
            if ($conectar->query($sql) === TRUE) {
                $message = "Proveedor registrado exitosamente.";
            } else {
                $message = "Error al registrar proveedor: " . $conectar->error;
            }
        } elseif ($tipo_formulario == 'venta') {
            // Inserción de venta
            $id_cliente = $_POST['id_cliente'];
            $id_producto = $_POST['id_producto'];
            $cantidad = $_POST['cantidad'];
            $total = $_POST['total'];
            $fecha_venta = date('Y-m-d H:i:s');

            // Primero, inserta la venta
            $sql_venta = "INSERT INTO Ventas (id_cliente, id_producto, fecha_venta, cantidad, total) VALUES ($id_cliente, $id_producto, '$fecha_venta', $cantidad, $total)";

            if ($conectar->query($sql_venta) === TRUE) {
                // Actualiza el stock del producto
                $sql_stock = "UPDATE Productos SET stock = stock - $cantidad WHERE id_producto = $id_producto";

                if ($conectar->query($sql_stock) === TRUE) {
                    $message = "Venta registrada y stock actualizado exitosamente.";
                } else {
                    $message = "Venta registrada, pero error al actualizar el stock: " . $conectar->error;
                }
            } else {
                $message = "Error al registrar venta: " . $conectar->error;
            }
        } elseif ($tipo_formulario == 'lote') {
            // Formulario del lote
            $id_lote = $_POST['id_lote'];
            $id_producto = $_POST['id_producto'];
            $cantidad = $_POST['cantidad'];
            $fecha_fabricacion = date('Y-m-d H:i:s'); // Fecha actual
            $fecha_vencimiento = $_POST['fecha_vencimiento']; // Asumiendo que se manda desde el formulario

            $sql = "INSERT INTO `lotes`(`id_lote`, `id_producto`, `fecha_fabricacion`, `fecha_vencimiento`, `cantidad`) 
                    VALUES ('$id_lote','$id_producto','$fecha_fabricacion','$fecha_vencimiento','$cantidad')";

            if ($conectar->query($sql) === TRUE) {
                $message = "Lote registrado exitosamente.";
            } else {
                $message = "Error al registrar lote: " . $conectar->error;
            }
        }
    }
}

include 'conexion/cierraCon.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Registro de Dulcería</title>
    <style>
        body {
            background-color: #f0f8ff;
            display: flex;
            flex-direction: column;
        }

        .tab-content {
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            background-color: white;
        }

        .img-left {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .align-btn{
            margin: auto;
            width: 20%;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Registro de Dulcería</h2>

        <?php if ($message): ?>
            <div class="alert alert-info" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="cliente-tab" data-toggle="tab" href="#cliente" role="tab" aria-controls="cliente" aria-selected="true">Cliente</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="proveedor-tab" data-toggle="tab" href="#proveedor" role="tab" aria-controls="proveedor" aria-selected="false">Proveedor</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="producto-tab" data-toggle="tab" href="#producto" role="tab" aria-controls="producto" aria-selected="false">Producto</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="lote-tab" data-toggle="tab" href="#lote" role="tab" aria-controls="lote" aria-selected="false">Lote</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="venta-tab" data-toggle="tab" href="#venta" role="tab" aria-controls="venta" aria-selected="false">Venta</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <!-- Formulario Cliente -->
            <div class="tab-pane fade show active" id="cliente" role="tabpanel" aria-labelledby="cliente-tab">
                <div class="row">
                    <div class="col-md-6">
                        <img src="../recursos/img_form1.jpg" alt="Cliente" class="img-left">
                    </div>
                    <div class="col-md-6">
                        <h4 class="form-title">Registrar Cliente</h4>
                        <form action="" method="POST">
                            <input type="hidden" name="tipo_formulario" value="cliente">
                            <div class="form-group">
                                <label for="nombre">Nombre:</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="apellido">Apellido:</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" required>
                            </div>
                            <div class="form-group">
                                <label for="telefono">Teléfono:</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono">
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Registrar</button>
                            <hr>
                        </form>
                    </div>
                </div>
                <?php if (isset($ticket)): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $ticket; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Formulario Proveedor -->
            <div class="tab-pane fade" id="proveedor" role="tabpanel" aria-labelledby="proveedor-tab">
                <div class="row">
                    <div class="col-md-6">
                        <img src="../recursos/img_form3.jpeg" alt="Proveedor" class="img-left">
                    </div>
                    <div class="col-md-6">
                        <h4 class="form-title">Registrar Proveedor</h4>
                        <form action="" method="POST">
                            <input type="hidden" name="tipo_formulario" value="proveedor">
                            <label for="id_proveedor">ID Proveedor:</label>
                            <input type="number" class="form-control" id="id_proveedor" name="id_proveedor" required>

                            <div class="form-group">
                                <label for="nombre_proveedor">Nombre del Proveedor:</label>
                                <input type="text" class="form-control" id="nombre_proveedor" name="nombre_proveedor" required>
                            </div>
                            <div class="form-group">
                                <label for="telefono_proveedor">Teléfono:</label>
                                <input type="tel" class="form-control" id="telefono_proveedor" name="telefono_proveedor">
                            </div>
                            <div class="form-group">
                                <label for="direccion_proveedor">Dirección:</label>
                                <input type="text" class="form-control" id="direccion_proveedor" name="direccion_proveedor" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Formulario Producto -->
            <div class="tab-pane fade" id="producto" role="tabpanel" aria-labelledby="producto-tab">
                <div class="row">
                    <div class="col-md-6">
                        <img src="../recursos/img_form2.webp" alt="Producto" class="img-left">
                    </div>
                    <div class="col-md-6">
                        <h4 class="form-title">Registrar Producto</h4>
                        <form action="" method="POST">
                            <input type="hidden" name="tipo_formulario" value="producto">
                            <div class="form-group">
                                <label for="id_producto">ID del Producto:</label>
                                <input type="text" class="form-control" id="id_producto" name="id_producto" required>
                            </div>
                            <div class="form-group">
                                <label for="nombre_producto">Nombre del Producto:</label>
                                <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" required>
                            </div>
                            <div class="form-group">
                                <label for="precio">Precio:</label>
                                <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label for="stock">Stock:</label>
                                <input type="number" class="form-control" id="stock" name="stock" required>
                            </div>
                            <div class="form-group">
                                <label for="id_proveedor">ID Proveedor:</label>
                                <input type="number" class="form-control" id="id_proveedor" name="id_proveedor">
                            </div>
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </form>
                    </div>
                </div>
            </div>



            <!-- Formulario Venta -->
            <div class="tab-pane fade" id="venta" role="tabpanel" aria-labelledby="venta-tab">
                <div class="row">
                    <div class="col-md-6">
                        <img src="../recursos/img_form4.jpg" alt="Venta" class="img-left">
                    </div>
                    <div class="col-md-6">
                        <h4 class="form-title">Registrar Venta</h4>
                        <form action="" method="POST">
                            <input type="hidden" name="tipo_formulario" value="venta">
                            <div class="form-group">
                                <label for="id_cliente">ID Cliente:</label>
                                <input type="number" class="form-control" id="id_cliente" name="id_cliente" required>
                            </div>
                            <div class="form-group">
                                <label for="id_producto">ID Producto:</label>
                                <input type="number" class="form-control" id="id_producto" name="id_producto" required>
                            </div>
                            <div class="form-group">
                                <label for="cantidad">Cantidad:</label>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                            </div>
                            <div class="form-group">
                                <label for="total">Total:</label>
                                <input type="number" class="form-control" id="total" name="total" step="0.01" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Formulario Lote -->
            <div class="tab-pane fade" id="lote" role="tabpanel" aria-labelledby="lote-tab">
                <div class="row">
                    <div class="col-md-6">
                        <img src="../recursos/img_form5.png" alt="Lote" class="img-left">
                    </div>
                    <div class="col-md-6">
                        <h4 class="form-title">Registrar Lotes</h4>
                        <form action="" method="POST">
                            <input type="hidden" name="tipo_formulario" value="lote">
                            <div class="form-group">
                                <label for="id_lote">ID Lote:</label>
                                <input type="number" class="form-control" id="id_lote" name="id_lote" required>
                            </div>
                            <div class="form-group">
                                <label for="id_producto">ID Producto:</label>
                                <input type="number" class="form-control" id="id_producto" name="id_producto" required>
                            </div>
                            <div class="form-group">
                                <label for="cantidad">Cantidad:</label>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                            </div>
                            <div class="form-group">
                                <label for="fecha_fabricacion">Fecha de Fabricación:</label>
                                <input type="date" class="form-control" id="fecha_fabricacion" name="fecha_fabricacion" required>
                            </div>
                            <div class="form-group">
                                <label for="fecha_vencimiento">Fecha de Vencimiento:</label>
                                <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <a class="btn btn-primary align-btn" href="../index.html" role="button">Regresar a Inicio</a>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>