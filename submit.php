$ip = $_SERVER['REMOTE_ADDR'];
$logFile = 'request_log.json';
$logData = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];

$currentTime = time();
$lastRequestTime = $logData[$ip] ?? 0;

if ($currentTime - $lastRequestTime < 14400) { // 4 hours = 14400 seconds
    echo "⏳ You can only order once every 4 hours.";
    exit;
}

// Save new timestamp
$logData[$ip] = $currentTime;
file_put_contents($logFile, json_encode($logData));
