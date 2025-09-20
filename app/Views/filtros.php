<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>filtros</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <div class="container mt-5">
    <h1 class="mb-4">Filtros</h1>
    <form method="post" action="/filtros/generarPDF">
      <div class="mb-3">
        <label for="publisher" class="form-label">Publisher</label>
        <select class="form-select" id="publisher" name="publisher" required>
            <option value="">Seleccione un publisher</option>
            <?php foreach ($publishers as $publisher): ?>
            <option value="<?= esc($publisher->publisher_name) ?>"><?= esc($publisher->publisher_name) ?></option>
            <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Generar PDF</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>