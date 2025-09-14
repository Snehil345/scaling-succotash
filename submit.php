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
        echo "⏳ Sorry @$username, you must wait $remaining minutes before placing another order.";
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

echo "✅ Your request has been sent to Telegram!";
?>

