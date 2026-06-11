<!DOCTYPE html>
<html>
<head>
    <title>Select Organization</title>
</head>
<body>

<h2>Selecciona tu organización</h2>

<ul>
    <?php foreach ($organizations as $org): ?>
        <li>
            <a href="/set-organization/<?= $org['organization_id'] ?>">
                <?= esc($org['name']) ?>
                (<?= esc($org['role']) ?>)
            </a>
        </li>
    <?php endforeach; ?>
</ul>

</body>
</html>