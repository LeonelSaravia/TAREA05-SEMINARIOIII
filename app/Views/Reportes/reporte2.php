<!DOCTYPE html>
< lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>reporte</title>
</head>
<body>
  <?= $estilos; ?>
  <h1>Reporte de productos de venta</h1>
  <hr>
  <h2 style="margin-top: 10px;"><?php echo $area; ?></h2>

  <table class="table">
    <colgroup>
      <col style="width: 10%;">
      <col style="width: 70%;">
      <col style="width: 20%;">
    </colgroup>
    <thead>
     <tr>
      <th>#</th>
      <th>Producto</th>
      <th>Precio</th>
    </tr>
    </thead>
    <tbody>
      <?php foreach ($productos as $producto): ?>
        <tr>
          <td><?php echo $producto['id']; ?></td>
          <td><?php echo $producto['descripcion']; ?></td>
          <td><?php echo $producto['precio']; ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <br>
  <strong>Reporte generado por: <?php echo $autor; ?></strong>
</body>
</html>