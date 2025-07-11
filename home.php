<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Interfaz de Facturación</title>
    <link rel="stylesheet" href="styleforms.css"> </head>
  <body>
    <header>
      <h1 class="page-title">Interfaz Facturación</h1> </header>

    <div class="container"> <?php
      // Aquí puedes incluir cualquier mensaje PHP después de procesar el formulario
      // (como el de "Factura guardada exitosamente" o errores)
      ?>

      <form action="" method="post" class="invoice-form">
        <div class="form-section header-fields">
          <div class="form-group">
            <label for="num_factura">Número de factura:</label>
            <input type="text" name="num_factura" id="num_factura" placeholder="" class="input" required>
          </div>
          <div class="form-group">
            <label for="fecha">Fecha de emisión:</label>
            <input type="date" name="fecha" id="fecha" class="input" required>
          </div>
          <div class="form-group">
            <label for="cliente">Cliente:</label>
            <input type="text" name="cliente" id="cliente" placeholder="" class="input" required>
          </div>
        </div>

        <hr class="separator">

        <h3>Detalle de Artículos</h3>

        <div class="products-table-container">
          <table>
            <thead>
              <tr>
                <th>Código de Artículo</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Total</th>
                <th>Acción</th> </tr>
            </thead>
            <tbody>
              <?php for ($i = 1; $i <= 6; $i++): ?>
              <tr class="product-row">
                <td><input type="text" name="producto[<?php echo $i; ?>][codigo]" placeholder="" class="input-small"></td>
                <td><input type="text" name="producto[<?php echo $i; ?>][nombre]" placeholder="" class="input-medium"></td>
                <td><input type="number" name="producto[<?php echo $i; ?>][cantidad]" placeholder="0" class="input-xsmall" min="0"></td>
                <td><input type="number" name="producto[<?php echo $i; ?>][precio_unitario]" placeholder="0.00" step="0.01" class="input-small"></td>
                <td><input type="text" name="producto[<?php echo $i; ?>][total_linea]" placeholder="0.00" class="input-small" readonly></td>
                <td><button type="button" class="btn-delete">Borrar</button></td>
              </tr>
              <?php endfor; ?>
            </tbody>
          </table>
        </div>

        <div class="form-section total-field">
          <label for="total_factura">Total Factura:</label>
          <input type="number" name="total_factura" id="total_factura" step="0.01" placeholder="0.00" class="input" readonly>
        </div>

        <div class="form-actions">
          <input type="submit" value="GUARDAR FACTURA" class="btn" name="btnguardar_factura">
        </div>
      </form>
    </div> <script>
      // Opcional: Script para calcular el total de línea y el total de la factura
      document.addEventListener('DOMContentLoaded', function() {
        const productRows = document.querySelectorAll('.product-row');
        const totalFacturaInput = document.getElementById('total_factura');

        function calculateLineTotal(row) {
          const cantidadInput = row.querySelector('[name$="[cantidad]"]');
          const precioUnitarioInput = row.querySelector('[name$="[precio_unitario]"]');
          const totalLineaInput = row.querySelector('[name$="[total_linea]"]');

          const cantidad = parseFloat(cantidadInput.value) || 0;
          const precioUnitario = parseFloat(precioUnitarioInput.value) || 0;
          const totalLinea = cantidad * precioUnitario;
          totalLineaInput.value = totalLinea.toFixed(2); // Formato a 2 decimales
          return totalLinea;
        }

        function calculateInvoiceTotal() {
          let grandTotal = 0;
          productRows.forEach(row => {
            grandTotal += calculateLineTotal(row);
          });
          totalFacturaInput.value = grandTotal.toFixed(2);
        }

        productRows.forEach(row => {
          row.addEventListener('input', function(event) {
            // Solo recalcular si se modifica cantidad o precio_unitario
            if (event.target.matches('[name$="[cantidad]"]') || event.target.matches('[name$="[precio_unitario]"]')) {
              calculateInvoiceTotal();
            }
          });
        });

        // Calcular totales iniciales al cargar la página
        calculateInvoiceTotal();

        // Funcionalidad para el botón "Borrar" (limpia la fila)
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('.product-row');
                row.querySelectorAll('input').forEach(input => {
                    input.value = ''; // Limpiar todos los campos de la fila
                });
                calculateInvoiceTotal(); // Recalcular el total de la factura
            });
        });

      });
    </script>
    <?php
// Este bloque PHP se ejecuta cuando el formulario es enviado (método POST)
if (isset($_POST['btnguardar_factura'])) {
    // 1. Validar que los campos principales de la factura NO estén vacíos
   if (!empty($_POST['num_factura']) && !empty($_POST['fecha']) && !empty($_POST['cliente']) && isset($_POST['total_factura'])) {

        // 2. Sanitizar los datos principales de la factura para seguridad
        $num_factura = htmlspecialchars($_POST['num_factura'], ENT_QUOTES, 'UTF-8');
        $fecha = htmlspecialchars($_POST['fecha'], ENT_QUOTES, 'UTF-8');
        $cliente = htmlspecialchars($_POST['cliente'], ENT_QUOTES, 'UTF-8');
        // El 'total_factura' se supone que es calculado por JavaScript en el cliente
        $total_factura = htmlspecialchars($_POST['total_factura'], ENT_QUOTES, 'UTF-8');

        // 3. Procesar y formatear los detalles de los productos
        $productos_str = ""; // Esta cadena almacenará todos los productos de la factura
        
        // Verificar si se ha enviado el array 'producto' y si es realmente un array
        if (isset($_POST['producto']) && is_array($_POST['producto'])) {
            foreach ($_POST['producto'] as $index => $producto_item) {
                $codigo_prod = isset($producto_item['codigo']) ? htmlspecialchars($producto_item['codigo'], ENT_QUOTES, 'UTF-8') : '';
                $nombre_prod = isset($producto_item['nombre']) ? htmlspecialchars($producto_item['nombre'], ENT_QUOTES, 'UTF-8') : '';
                $cantidad_prod = isset($producto_item['cantidad']) ? htmlspecialchars($producto_item['cantidad'], ENT_QUOTES, 'UTF-8') : '';
                $precio_unitario_prod = isset($producto_item['precio_unitario']) ? htmlspecialchars($producto_item['precio_unitario'], ENT_QUOTES, 'UTF-8') : '';
                $total_linea_prod = isset($producto_item['total_linea']) ? htmlspecialchars($producto_item['total_linea'], ENT_QUOTES, 'UTF-8') : '';

                // Solo si el producto tiene un código o un nombre, lo incluimos
                if (!empty($codigo_prod) || !empty($nombre_prod)) {
                    // Usamos corchetes para delimitar cada producto y punto y coma para los campos internos
                    $productos_str .= "[$codigo_prod;$nombre_prod;$cantidad_prod;$precio_unitario_prod;$total_linea_prod]";
                }
            }
        }

        // 4. Definir la ruta del archivo de texto donde se guardarán las facturas
        $ruta_archivo_facturas = 'facturas.txt';

        // 5. Formatear la línea completa de la factura para guardarla en el archivo
        $linea_a_guardar = "$num_factura;$fecha;$cliente;$total_factura;$productos_str\n";

        // 6. Escribir la línea formateada en el archivo
        if (file_put_contents($ruta_archivo_facturas, $linea_a_guardar, FILE_APPEND | LOCK_EX) !== false) {
            // Mensaje de éxito si la factura se guardó correctamente
            echo '<div class="mensaje-exito">Factura guardada exitosamente en ' . $ruta_archivo_facturas . '</div>';
        } else {
            // Mensaje de error si hubo un problema al escribir el archivo
            echo '<div class="mensaje-error">Error al guardar la factura. Por favor, verifica los permisos de escritura del directorio: ' . dirname($ruta_archivo_facturas) . '</div>';
        }

    } else {
        // Mensaje de advertencia si los campos principales obligatorios no están llenos
        echo '<div class="mensaje-advertencia">Por favor, asegúrate de llenar todos los campos principales de la factura (Número, Fecha, Cliente y el Total).</div>';
    }
}
?>
  </body>
</html>
