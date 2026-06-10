<!DOCTYPE html>
<html>
<head>
    <title>System Health</title>
    <style>
        body { font-family: Arial; background: #0f172a; color: #fff; padding: 20px; }
        .card { background: #1e293b; padding: 15px; margin-bottom: 10px; border-radius: 8px; }
        .ok { color: #22c55e; }
        .fail { color: #ef4444; }
    </style>
</head>
<body>

<h1>System Health Dashboard</h1>

<div class="card">
    <h3>Status: 
        <span class="<?= $data['status'] === 'healthy' ? 'ok' : 'fail' ?>">
            <?= $data['status'] ?>
        </span>
    </h3>
</div>

<div class="card">
    <h3>Services</h3>
    <p>Database: <?= $data['services']['database'] ? 'OK' : 'FAIL' ?></p>
    <p>Redis: <?= $data['services']['redis'] ? 'OK' : 'FAIL' ?></p>
</div>

<div class="card">
    <h3>System</h3>
    <p>Uptime: <?= $data['uptime'] ?></p>
    <p>Memory: <?= $data['memory']['used_mb'] ?> MB</p>
</div>

<div class="card">
    <h3>Latency</h3>
    <p>Health check: <?= $data['latency']['health_ms'] ?> ms</p>
</div>

</body>
</html>