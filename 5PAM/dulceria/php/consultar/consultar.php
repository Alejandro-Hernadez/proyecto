<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Dulcería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Consulta de Datos de la Dulcería</h2>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- Formulario de selección de tabla -->
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="tabla" class="form-label">Selecciona la tabla:</label>
                    <select class="form-select" id="tabla" name="tabla" required>
                        <option value="">Selecciona una tabla</option>
                        <option value="cliente">Clientes</option>
                        <option value="productos">Productos</option>
                        <option value="lotes">Lotes</option>
                        <option value="proveedor">Proveedores</option>
                        <option value="ventas">Ventas</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Consultar</button>
            </form>
        </div>
    </div>

    <!-- Área para mostrar resultados -->
    <div class="row mt-5">
        <div class="col-md-12">
            <div id="resultadoConsulta" class="table-responsive">
                <?php
                // Código PHP para manejar la consulta en el mismo documento
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tabla'])) {
                    include '../conexion/abrirCon.php'; // Asegúrate de tener esta conexión configurada
                    $tabla = $_POST['tabla'];
                    $query = "";

                    // Asignar consulta según la tabla seleccionada
                    switch ($tabla) {
                        case 'cliente':
                            $query = "SELECT * FROM cliente";
                            break;
                        case 'productos':
                            $query = "SELECT * FROM productos";
                            break;
                        case 'lotes':
                            $query = "SELECT * FROM lotes";
                            break;
                        case 'proveedor':
                            $query = "SELECT * FROM proveedor";
                            break;
                        case 'ventas':
                            $query = "SELECT * FROM ventas";
                            break;
                        default:
                            echo "<p class='text-danger'>Tabla no válida.</p>";
                            exit;
                    }

                    $result = $conectar->query($query);

                    if ($result->num_rows > 0) {
                        echo "<table class='table table-bordered'><thead><tr>";

                        // Obtener nombres de columnas
                        $fields = $result->fetch_fields();
                        foreach ($fields as $field) {
                            echo "<th>" . ucfirst($field->name) . "</th>";
                        }
                        echo "</tr></thead><tbody>";

                        // Mostrar datos de la tabla
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            foreach ($row as $value) {
                                echo "<td>" . htmlspecialchars($value) . "</td>";
                            }
                            echo "</tr>";
                        }
                        echo "</tbody></table>";
                    } else {
                        echo "<p class='text-center text-muted'>No se encontraron datos.</p>";
                    }
                    include '../conexion/cierraCon.php';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
