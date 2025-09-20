<?= $estilos ?>
<page backtop="20mm" backbottom="20mm" backleft="10mm" backright="10mm">
    <page_header >
      <h1>Superhéroes - <?= htmlspecialchars($editorial) ?></h1>
      <div class="info">
          <p>Total de superhéroes encontrados: <?= $count ?></p>
          <p>Fecha de generación: <?= date('d/m/Y H:i:s') ?></p>
      </div>
    </page_header>
    <page_footer>
      <hr>
      <p class="pie">Reporte generado por: Sistema de Superhéroes - Página [[page_cu]]/[[page_nb]]</p>
    </page_footer>
    
    <table class="table">
        <colgroup>
            <col style="width: 10%;">
            <col style="width: 30%;">
            <col style="width: 20%;">
            <col style="width: 20%;">
            <col style="width: 20%;">
        </colgroup>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Superhéroe</th>
                <th>Nombre Completo</th>
                <th>Editorial</th>
                <th>Alineación</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($superheroes)): ?>
                <?php foreach ($superheroes as $hero): ?>
                    <tr>
                        <td><?= htmlspecialchars($hero->id) ?></td>
                        <td><?= htmlspecialchars($hero->superhero_name) ?></td>
                        <td><?= htmlspecialchars($hero->full_name ?: 'N/A') ?></td>
                        <td><?= htmlspecialchars($hero->publisher_name) ?></td>
                        <td><?= htmlspecialchars($hero->alignment ?: 'N/A') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="no-data">
                        No se encontraron superhéroes para la editorial "<?= htmlspecialchars($editorial) ?>"
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</page>
