<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PDF | Listado de Empresas</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; margin: 30px; }
        h1 { color: #226fbb; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #226fbb; color: white; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8pt; color: #999; }
    </style>
</head>
<body>
    <h1>Listado de Empresas</h1>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($empresas as $empresa): ?>
                <tr>
                    <td><?= $empresa->nombre ?></td>
                    <td><?= $empresa->email ?></td>
                    <td><?= $empresa->telefono ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <p>Documento generado el: <?= $fecha ?></p>

    <div class="footer">
        Página generada por el sistema ProyectaFP.
    </div>
</body>
</html>