<?php
include '../app/config.php';

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realiza tu Pedido</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-success"><i class="zmdi zmdi-male-alt zmdi-hc-fw"></i> Realiza tu Pedido</h2>
        <form id="form" method="POST" action="guardar_datos.php">
            <div class="mb-3">
                <label for="dni" class="form-label" style="color:red;">Número de DNI(Coloque un DNI Valido)</label>
                <input type="text" id="dni" name="dni" class="form-control" placeholder="Ingresa el DNI" maxlength="8" required>
            </div>
        </form>

        <div class="row">
            <!-- Fieldset 1: Datos del Cliente -->
            <div class="col-md-6">
                <fieldset class="border p-3 rounded">
                    <legend class="text-success">Datos del Cliente</legend>
                    <div id="resultado"></div>
                    <form action="proceso_guardar_cliente.php" method="POST">
                        <div class="mb-3">
                            <label for="number" class="form-label text-danger">Número de DNI</label>
                            <input type="text" id="number" name="dni" class="form-control" placeholder="Ingresa el DNI" maxlength="8" required value="<?php echo isset($data['numeroDocumento']) ? $data['numeroDocumento'] : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Nombre Completo</label>
                            <input type="text" id="full_name" name="full_name" class="form-control" placeholder="Nombre Completo">
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Nombre">
                        </div>
                        <div class="mb-3">
                            <label for="surname" class="form-label">Apellidos</label>
                            <input type="text" id="surname" name="surname" class="form-control" placeholder="Apellidos">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Dirección</label>
                            <input type="text" id="address" name="address" class="form-control" placeholder="Dirección">
                        </div>
                        <div class="mb-3">
                        <label for="correo-electronico-reg" class="form-label">Correo Electrónico</label>
                        <input type="email" id="correo-electronico-reg" name="correo-electronico-reg" class="form-control" maxlength="150" title="Ingrese el correo electrónico (hasta 150 caracteres).">
                    </div>
                        <div class="mb-3">
                            <label for="celular-reg" class="form-label">Celular</label>
                            <input type="text" id="celular-reg" name="celular-reg" class="form-control" placeholder="Celular" maxlength="9" pattern="[0-9]{9}" title="Ingrese el número de celular (9 caracteres)">
                        </div>


                </fieldset>
            </div>

            <!-- Fieldset 2: Pedido e Incidencias -->
            <div class="col-md-6">
                <fieldset class="border p-3 rounded">
                    <legend class="text-success">Pedido e Incidencias</legend>

                    <div class="mb-3">
                        <label for="id_servicio" class="form-label">Seleccione un servicio</label>
                        <select class="form-control" name="id_servicio" id="id_servicio">
                            <option value="">Seleccione un servicio</option>
                            <?php
                            $query = "SELECT ID_tipo_servicio, Nom_servicio FROM tipo_servicio ORDER BY Nom_servicio";
                            $stmt = $pdo->query($query);
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='{$row['ID_tipo_servicio']}'>{$row['Nom_servicio']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    
                    <div class="mb-3">
                        <label for="details" class="form-label">Detalles</label>
                        <textarea id="details" name="details" class="form-control" placeholder="Detalles"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="codigo-operacion-reg" class="form-label text-danger">Código de Operación</label>
                        <?php
                        $stmt = $pdo->prepare("SELECT MAX(Codigo_Operacion) AS Codigo_Operacion FROM atencion_cliente");
                        $stmt->execute();
                        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
                        $codigo = (int) $fila['Codigo_Operacion'] + 1;
                        ?>
                        <input type="text" id="codigo-operacion-reg" name="codigo-operacion-reg" class="form-control" value="<?php echo str_pad($codigo, 5, '0', STR_PAD_LEFT); ?>" maxlength="5" readonly>
                    </div>
                </fieldset>
                <!-- Submit Button -->



            </div>
        </div>


    </div>
    <div class="text-center mt-4">
        <button type="submit" id="guardarBtn" class="btn btn-info btn-raised btn-sm">
            <i class="zmdi zmdi-floppy"></i> Guardar
        </button>
    </div>

    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>


<script>
    document.getElementById('dni').addEventListener('input', () => {
        const dni = document.getElementById('dni').value.trim();

        if (dni.length === 8) {
            document.getElementById('resultado').innerHTML = `<div class="alert alert-primary mt-4">Cargando...</div>`;
            //API 1000 propiedad de www.sehuacho.com solo para usus academicos
            fetch('https://sehuacho.com/api_1000/', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        dni
                    })
                }).then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error('Error al obtener los datos');
                    }
                })
                .then(data => {
                    if (data.success) {
                        document.getElementById('number').value = data.data.numeroDocumento;
                        document.getElementById('full_name').value = `${data.data.nombres} ${data.data.apellidoPaterno} ${data.data.apellidoMaterno}`;
                        document.getElementById('name').value = data.data.nombres;
                        document.getElementById('surname').value = `${data.data.apellidoPaterno} ${data.data.apellidoMaterno}`;

                        document.getElementById('resultado').innerHTML = `
                            <div class="alert alert-success mt-4">
                                <h3>Datos obtenidos exitosamente</h3>
                            </div>
                        `;
                    } else {
                        document.getElementById('resultado').innerHTML = `
                            <div class="alert alert-danger mt-4">
                                Error al obtener los datos.
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error(error);
                    document.getElementById('resultado').innerHTML = `
                        <div class="alert alert-danger mt-4">
                            Error al obtener los datos.
                        </div>
                    `;
                });
        } else {
            // Clear form data if DNI is not 8 digits
            document.getElementById('number').value = '';
            document.getElementById('full_name').value = '';
            document.getElementById('name').value = '';
            document.getElementById('surname').value = '';


            document.getElementById('resultado').innerHTML = '';
        }
    });

</script>