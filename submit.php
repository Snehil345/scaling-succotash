<?php
// Telegram Bot setup
$token = "8183046818:AAFERd6yNEt86ohzCNzCCcAK_P00dmApI1Q"; 
$chat_id = "6011657948"; 

// Get user input
$username = trim($_POST['telegram_username']);
$file_count = intval($_POST['file_count']);

// Path to storage file
$storage_file = __DIR__ . "/orders.json";

// Load existing orders
$orders = [];
if (file_exists($storage_file)) {
    $orders = json_decode(file_get_contents($storage_file), true);
    if (!is_array($orders)) $orders = [];
}

// Check if this username already ordered
if (isset($orders[$username])) {
    $elapsed = time() - $orders[$username];
    if ($elapsed < 4 * 60 * 60) { // 4 hours = 14400 sec
        $remaining = ceil((4 * 60 * 60 - $elapsed) / 60);

        // Show nice centered message
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Order Cooldown</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background: #f5f6fa;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .message-box {
                    background: #fff;
                    padding: 30px;
                    border-radius: 12px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                    text-align: center;
                    width: 350px;
                }
                h2 {
                    color: #e11d48;
                    font-size: 20px;
                }
            </style>
        </head>
        <body>
            <div class='message-box'>
                <h2>⏳ Sorry @$username</h2>
                <p>You must wait <b>$remaining minutes</b> before placing another order.</p>
            </div>
        </body>
        </html>";
        exit;
    }
}

// Prepare Telegram message
$message = "📬 *New Request*\n\n👤 Telegram Username: @$username\n📁 Files Requested: $file_count";

// Send message to Telegram
file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=" . urlencode($message) . "&parse_mode=Markdown");

// Save timestamp for this username
$orders[$username] = time();
file_put_contents($storage_file, json_encode($orders));

// Success message
echo "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Order Sent</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .message-box {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
            width: 350px;
        }
        h2 {
            color: #16a34a;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class='message-box'>
        <h2>✅ Order Sent</h2>
        <p>Your request has been sent to Telegram!</p>
    </div>
</body>
</html>";
?>

