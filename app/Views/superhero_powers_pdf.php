<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .hero-info {
            margin-bottom: 20px;
        }
        .hero-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .hero-info td {
            padding: 5px;
            border: 1px solid #ddd;
        }
        .hero-info td:first-child {
            font-weight: bold;
            width: 30%;
            background-color: #f5f5f5;
        }
        .powers-section {
            margin-top: 20px;
        }
        .powers-list {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 10px;
        }
        .power-item {
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 3px;
            padding: 3px 6px;
            font-size: 10px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Poderes de Superhéroe</h1>
        <p>Generado el: <?= date('d/m/Y H:i:s') ?></p>
    </div>

    <div class="hero-info">
        <table>
            <tr>
                <td>Nombre de Superhéroe:</td>
                <td><?= $superhero->superhero_name ?></td>
            </tr>
            <tr>
                <td>Nombre Real:</td>
                <td><?= $superhero->full_name ?: 'Desconocido' ?></td>
            </tr>
            <tr>
                <td>Editorial:</td>
                <td><?= $superhero->publisher_name ?: 'Desconocido' ?></td>
            </tr>
            <tr>
                <td>Alineación:</td>
                <td><?= $superhero->alignment ?: 'Desconocido' ?></td>
            </tr>
            <tr>
                <td>Género:</td>
                <td><?= $superhero->gender ?: 'Desconocido' ?></td>
            </tr>
            <tr>
                <td>Raza:</td>
                <td><?= $superhero->race ?: 'Desconocido' ?></td>
            </tr>
        </table>
    </div>

    <div class="powers-section">
        <h2>Poderes y Habilidades</h2>
        <?php if (!empty($powers)): ?>
            <div class="powers-list">
                <?php foreach ($powers as $power): ?>
                    <span class="power-item"><?= $power->power_name ?></span>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Este superhéroe no tiene poderes registrados en la base de datos.</p>
        <?php endif; ?>
    </div>

    <div class="footer">
        <p>Sistema de Gestión de Superhéroes - <?= date('Y') ?></p>
    </div>
</body>
</html>