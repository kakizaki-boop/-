<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$from_user_id = $_SESSION["user_id"] ?? null;
$to_user_id = $_POST["to_user_id"] ?? null;
$message = $_POST["message"] ?? null;

if (!$from_user_id || !$to_user_id || !$message || $from_user_id == $to_user_id) {
  die("不正な送信です");
}

// ✅ DB接続
$conn = new mysqli("localhost", "root", "", "learning_app");
if ($conn->connect_error) {
  die("接続失敗: " . $conn->connect_error);
}

// ✅ メッセージ保存
$stmt = $conn->prepare("
  INSERT INTO messages (from_user_id, to_user_id, message)
  VALUES (?, ?, ?)
");
$stmt->bind_param("iis", $from_user_id, $to_user_id, $message);
$stmt->execute();

// ✅ 元のチャット画面に戻る
header("Location: chat.php?id=$to_user_id");
exit();
?>
