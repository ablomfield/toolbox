<?php
// ---------------- CONFIG ----------------
$MAX_WORKERS = 10;
$TIMEOUT = 10;
$MAX_RETRIES = 5;
$BACKOFF_BASE = 1.5;
$BACKOFF_MAX = 30;
set_time_limit(600);
// ----------------------------------------

function request_with_retry($method, $url, $headers, $payload = null) {
    global $TIMEOUT, $MAX_RETRIES, $BACKOFF_BASE, $BACKOFF_MAX;

    for ($attempt = 1; $attempt <= $MAX_RETRIES; $attempt++) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_TIMEOUT        => $TIMEOUT,
            CURLOPT_HEADER         => true
        ]);

        if ($payload) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        }

        $response = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerText = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        curl_close($ch);

        if ($status < 400) {
            return json_decode($body, true);
        }

        if ($status == 429 || in_array($status, [500, 502, 503, 504])) {
            preg_match('/Retry-After:\s*(\d+)/i', $headerText, $m);
            $sleep = $m[1] ?? min(pow($BACKOFF_BASE, $attempt) + rand(0, 1000) / 1000, $BACKOFF_MAX);
            sleep((int)$sleep);
            continue;
        }

        throw new Exception("HTTP $status error on $url");
    }

    throw new Exception("Max retries exceeded for $url");
}

$rows = [];
$startTime = microtime(true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = trim($_POST['token']);
    $orgId = trim($_POST['orgid']);

    $headers = [
        "Authorization: Bearer $token",
        "Accept: application/json",
        "Content-Type: application/json"
    ];

    // ---------------- DEVICE LIST ----------------
    $url = "https://webexapis.com/v1/devices?max=500&type=roomdesk";
    if ($orgId) {
        $url .= "&orgId=$orgId";
    }

    $devices = [];

    while ($url) {
        $data = request_with_retry("GET", $url, $headers);
        $devices = array_merge($devices, $data['items'] ?? []);
        $url = $data['links']['next']['href'] ?? null;
    }

    // ---------------- PARALLEL PROCESSING ----------------
    $mh = curl_multi_init();
    $handles = [];
    $queue = $devices;
    $active = null;

    while ($queue || $handles) {
        while (count($handles) < $MAX_WORKERS && $queue) {
            $device = array_shift($queue);

            $payload = [
                "deviceId" => $device['id'],
                "arguments" => ["Type" => "TouchPanel"]
            ];

            $ch = curl_init("https://webexapis.com/v1/xapi/command/Peripherals.List");
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_HTTPHEADER     => $headers,
                CURLOPT_POSTFIELDS     => json_encode($payload)
            ]);

            curl_multi_add_handle($mh, $ch);
            $handles[(int)$ch] = [$ch, $device];
        }

        do {
            curl_multi_exec($mh, $active);
        } while ($active);

        foreach ($handles as $key => [$ch, $device]) {
            if (curl_multi_info_read($mh)) {
                $result = json_decode(curl_multi_getcontent($ch), true);
                curl_multi_remove_handle($mh, $ch);
                unset($handles[$key]);

                foreach ($result['result']['Device'] ?? [] as $nav) {
                    $dramUrl =
                        "https://webexapis.com/v1/xapi/status" .
                        "?deviceId={$device['id']}" .
                        "&name=Peripherals.ConnectedDevice[{$nav['id']}].DRAM";

                    $dram = 0;
                    try {
                        $dramData = request_with_retry("GET", $dramUrl, $headers);
                        $dram = $dramData['result']['Peripherals']['ConnectedDevice'][0]['DRAM'] ?? 0;
                    } catch (Exception $e) {}

                    $rows[] = [
                        $device['displayName'],
                        $device['product'],
                        $nav['Name'] ?? '',
                        $nav['SerialNumber'] ?? '',
                        $dram
                    ];
                }
            }
        }
    }

    curl_multi_close($mh);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Webex Room Devices</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 6px; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>

<h2>Webex Room Device Inventory</h2>

<form method="post">
    <label>Access Token:</label><br>
    <input type="password" name="token" required style="width: 400px;"><br><br>

    <label>Org ID (optional):</label><br>
    <input type="text" name="orgid" style="width: 400px;"><br><br>

    <button type="submit">Run</button>
</form>

<?php if ($rows): ?>
    <h3>Results (<?= count($rows) ?> rows)</h3>
    <table>
        <tr>
            <th>Room Name</th>
            <th>Room Device</th>
            <th>Control Device</th>
            <th>Serial</th>
            <th>DRAM (GB)</th>
        </tr>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r[0]) ?></td>
                <td><?= htmlspecialchars($r[1]) ?></td>
                <td><?= htmlspecialchars($r[2]) ?></td>
                <td><?= htmlspecialchars($r[3]) ?></td>
                <td><?= htmlspecialchars($r[4]) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p><strong>Execution time:</strong> <?= round(microtime(true) - $startTime, 2) ?> seconds</p>
<?php endif; ?>

</body>
</html>
