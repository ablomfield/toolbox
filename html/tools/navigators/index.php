<?php
$sitesec = "tools";
session_start();
date_default_timezone_set("America/Chicago");

// Import Settings
include($_SERVER['DOCUMENT_ROOT'] . "/includes/settings.php");

// Get Login Details
include($_SERVER['DOCUMENT_ROOT'] . "/includes/checklogin.php");

$orgid = $_SESSION["orgid"];

?>
<!DOCTYPE HTML>
<html>

<head>
    <title><?php echo ($sitetitle); ?></title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="/assets/css/main.css" />
    <link rel="icon" type="image/x-icon" href="/images/icononly_transparent_nobuffer.png">
    <script src="https://kit.fontawesome.com/5a918f4f0f.js" crossorigin="anonymous"></script>
</head>

<body class="is-preload">
    <script>
        function myFunction(fieldid) {
            // Get the text field
            var copyText = document.getElementById(fieldid);

            // Select the text field
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.value);

            // Unselect the text field
            window.getSelection().removeAllRanges();
        }
    </script>
    <div id="page-wrapper">
        <!-- Header -->
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <!-- Main -->
        <section class="wrapper style1">
            <div class="container">
                <div id="content">
                    <!-- Content -->
                    <article>
                        <?php
                        // ---------------- CONFIG ----------------
                        const MAX_WORKERS  = 10;
                        const TIMEOUT      = 10;
                        const MAX_RETRIES  = 5;
                        const BACKOFF_BASE = 1.5;
                        const BACKOFF_MAX  = 30;
                        // ----------------------------------------

                        $startTime = microtime(true);
                        $timestamp = date("Ymd_His");

                        $headers = [
                            "Authorization: Bearer $authtoken",
                            "Accept: application/json",
                            "Content-Type: application/json"
                        ];

                        // ---------------- CURL REQUEST WITH RETRY ----------------
                        function requestWithRetry(string $method, string $url, array $headers, ?array $payload = null): array
                        {
                            for ($attempt = 1; $attempt <= MAX_RETRIES; $attempt++) {
                                $ch = curl_init($url);

                                curl_setopt_array($ch, [
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_CUSTOMREQUEST  => $method,
                                    CURLOPT_HTTPHEADER     => $headers,
                                    CURLOPT_TIMEOUT        => TIMEOUT,
                                    CURLOPT_HEADER         => true
                                ]);

                                if ($payload) {
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                                }

                                $response = curl_exec($ch);
                                $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                $headerSz = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

                                $headerStr = substr($response, 0, $headerSz);
                                $body      = substr($response, $headerSz);

                                curl_close($ch);

                                if ($status < 400) {
                                    return [
                                        'status'  => $status,
                                        'headers' => $headerStr,
                                        'body'    => json_decode($body, true)
                                    ];
                                }

                                // ---- 429 Handling ----
                                if ($status === 429) {
                                    preg_match('/Retry-After:\s*(\d+)/i', $headerStr, $m);
                                    $sleep = $m[1] ?? min(pow(BACKOFF_BASE, $attempt) + mt_rand(0, 1000) / 1000, BACKOFF_MAX);
                                    echo "[429] Rate limited. Sleeping {$sleep}s (attempt $attempt)\n";
                                    sleep((int)$sleep);
                                    continue;
                                }

                                // ---- Retry server errors ----
                                if (in_array($status, [500, 502, 503, 504])) {
                                    $sleep = min(pow(BACKOFF_BASE, $attempt) + mt_rand(0, 1000) / 1000, BACKOFF_MAX);
                                    sleep((int)$sleep);
                                    continue;
                                }

                                throw new RuntimeException("HTTP $status calling $url");
                            }

                            throw new RuntimeException("Max retries exceeded for $url");
                        }

                        // ---------------- FETCH DEVICE LIST ----------------
                        $url = $orgid
                            ? "https://webexapis.com/v1/devices?max=500&type=roomdesk&orgId=$orgid"
                            : "https://webexapis.com/v1/devices?max=500&type=roomdesk";

                        $devices = [];

                        while ($url) {
                            $resp = requestWithRetry("GET", $url, $headers);
                            $devices = array_merge($devices, $resp['body']['items'] ?? []);

                            if (preg_match('/<([^>]+)>;\s*rel="next"/', $resp['headers'], $m)) {
                                $url = $m[1];
                            } else {
                                $url = null;
                            }
                        }

                        echo "Total rooms retrieved: " . count($devices) . "\n";

                        // ---------------- OPEN FILE ----------------
                        echo ("                      <table>\n");
                        echo ("                        <thead>\n");
                        echo ("                          <tr>\n");
                        echo ("                            <th>Room Name</th>\n");
                        echo (".                           <th>Room Device</th>\n");
                        echo ("                            <th>Control Device</th>\n");
                        echo ("                            <th>Serial</th>\n");
                        echo ("                            <th>DRAM</th>\n");
                        echo ("                          </tr>\n");
                        echo ("                        </thead>\n");
                        echo ("                        <tbody>\n");

                        // ---------------- PARALLEL PROCESSING ----------------
                        $multi = curl_multi_init();
                        $queue = array_chunk($devices, MAX_WORKERS);

                        foreach ($queue as $batch) {
                            $handles = [];

                            foreach ($batch as $device) {
                                $payload = json_encode([
                                    "deviceId"  => $device['id'],
                                    "arguments" => ["Type" => "TouchPanel"]
                                ]);

                                $ch = curl_init("https://webexapis.com/v1/xapi/command/Peripherals.List");
                                curl_setopt_array($ch, [
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_POST           => true,
                                    CURLOPT_HTTPHEADER     => $headers,
                                    CURLOPT_POSTFIELDS     => $payload,
                                    CURLOPT_TIMEOUT        => TIMEOUT
                                ]);

                                curl_multi_add_handle($multi, $ch);
                                $handles[(int)$ch] = [$ch, $device];
                            }

                            do {
                                curl_multi_exec($multi, $running);
                                curl_multi_select($multi);
                            } while ($running);

                            foreach ($handles as [$ch, $device]) {
                                $resp = json_decode(curl_multi_getcontent($ch), true);
                                curl_multi_remove_handle($multi, $ch);
                                curl_close($ch);

                                foreach ($resp['result']['Device'] ?? [] as $nav) {
                                    $dramUrl =
                                        "https://webexapis.com/v1/xapi/status" .
                                        "?deviceId={$device['id']}" .
                                        "&name=Peripherals.ConnectedDevice[{$nav['id']}].DRAM";

                                    try {
                                        $dramResp = requestWithRetry("GET", $dramUrl, $headers);
                                        $dram = $dramResp['body']['result']['Peripherals']['ConnectedDevice'][0]['DRAM'] ?? 0;
                                    } catch (Exception) {
                                        $dram = 0;
                                    }
                                    echo ("                          <tr>\n");
                                    echo ("                            <td>{$device['displayName']}</td>\n");
                                    echo (".                           <td>{$device['product']}</td>\n");
                                    echo ("                            <td>{$device['Name']}</td>\n");
                                    echo ("                            <td>{$device['SerialNumber']}</td>\n");
                                    echo ("                            <td>{$dram}</td>\n");
                                    echo ("                          </tr>\n");
                                }
                            }
                        }

                        curl_multi_close($multi);
                        echo ("                        </tbody>\n");
                        echo ("                      </table>\n");
                        echo ("                      <h1>--- " . round(microtime(true) - $startTime, 2) . " seconds ---</h1>\n");
                        ?>
                    </article>
                </div>
            </div>
        </section>
        <!-- Footer -->
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </div>
    <!-- Scripts -->
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/jquery.dropotron.min.js"></script>
    <script src="/assets/js/browser.min.js"></script>
    <script src="/assets/js/breakpoints.min.js"></script>
    <script src="/assets/js/util.js"></script>
    <script src="/assets/js/main.js"></script>
</body>

</html>