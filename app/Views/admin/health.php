<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <title>System Health</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: #ffffff;
            padding: 20px;
        }

        .card {
            background: #1e293b;
            padding: 16px;
            margin-bottom: 16px;
            border-radius: 8px;
        }

        .ok {
            color: #22c55e;
            font-weight: bold;
        }

        .fail {
            color: #ef4444;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #334155;
        }
    </style>
</head>

<body>

    <h1>System Health Dashboard</h1>

    <div class="card">
        <h3>Application</h3>

        <table>
            <tr>
                <td>App</td>
                <td class="<?= $data['app'] === 'ok' ? 'ok' : 'fail' ?>">
                    <?= esc($data['app']) ?>
                </td>
            </tr>

            <tr>
                <td>Request ID</td>
                <td><?= esc($data['request_id']) ?></td>
            </tr>

            <tr>
                <td>Total Time</td>
                <td><?= esc($data['total_ms']) ?> ms</td>
            </tr>
        </table>
    </div>

    <div class="card">
        <h3>Database</h3>

        <p class="<?= $data['database']['status'] === 'ok' ? 'ok' : 'fail' ?>">
            <?= esc($data['database']['status']) ?>
        </p>

        <p>
            Latency:
            <?= esc($data['database']['ms']) ?> ms
        </p>

        <?php if (isset($data['database']['error'])) : ?>
            <p><?= esc($data['database']['error']) ?></p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h3>Redis</h3>

        <p class="<?= $data['redis']['status'] === 'ok' ? 'ok' : 'fail' ?>">
            <?= esc($data['redis']['status']) ?>
        </p>

        <p>
            Latency:
            <?= esc($data['redis']['ms']) ?> ms
        </p>

        <?php if (isset($data['redis']['error'])) : ?>
            <p><?= esc($data['redis']['error']) ?></p>
        <?php endif; ?>
    </div>

</body>

</html>