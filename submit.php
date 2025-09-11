<?php
$token = "8183046818:AAFERd6yNEt86ohzCNzCCcAK_P00dmApI1Q"; // your bot token
$chat_id = "6011657948"; // your Telegram ID

$username = $_POST['telegram_username'];
$file_count = $_POST['file_count'];

$message = "📬 *New Request*\n\n👤 Telegram Username: @$username\n📁 Files Requested: $file_count";

file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=" . urlencode($message) . "&parse_mode=Markdown");

echo "✅ Your request has been sent to Telegram!";
?>
