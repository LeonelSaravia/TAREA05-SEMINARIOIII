<?= $estilos ?>
<page backtop="15mm" backbottom="15mm" backleft="10mm" backright="10mm">
  <page_header>
    <div class="header">
      <h1>Reporte de Superhéroes</h1>
    </div>
    </page_header>
    <page_footer>
      <hr>
      <p class="pie">Reporte generado por: Sistema de Superhéroes - Página [[page_cu]]/[[page_nb]]</p>
    </page_footer>
 
  <table class="table">
    <colgroup>
      <col style="width: 5%;">
      <col style="width: 30%;">
      <col style="width: 30%;">
      <col style="width: 20%;">
      <col style="width: 15%;">
    </colgroup>
    <thead>
      <tr>
        <th>#</th>
        <th>Nombre</th>
        <th>Alias</th>
        <th>Casa</th>
        <th>Bando</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($row as $r): ?>
      <tr>
        <td><?= $r['id'] ?></td>
        <td><?= $r['superhero_name'] ?></td>
        <td><?= $r['full_name'] ?></td>
        <td><?= $r['publisher_name'] ?></td>
        <td><?= $r['alignment'] ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</page>