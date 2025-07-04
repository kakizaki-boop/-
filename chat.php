<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION["user_id"])) {
  die("ログインが必要です");
}

$from_user_id = $_SESSION["user_id"];
$to_user_id = $_GET["id"] ?? null;

if (!$to_user_id || $from_user_id == $to_user_id) {
  die("無効なチャットIDです");
}

// ✅ DB接続
$conn = new mysqli("localhost", "root", "", "learning_app");
if ($conn->connect_error) {
  die("接続失敗: " . $conn->connect_error);
}

// ✅ メッセージ取得
$stmt = $conn->prepare("
  SELECT * FROM messages
  WHERE (from_user_id = ? AND to_user_id = ?)
     OR (from_user_id = ? AND to_user_id = ?)
  ORDER BY created_at ASC
");
$stmt->bind_param("iiii", $from_user_id, $to_user_id, $to_user_id, $from_user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>チャット</title>
  <style>
    body { font-family: sans-serif; margin: 20px; }
    .message { margin: 10px 0; padding: 10px; border-radius: 8px; width: fit-content; max-width: 60%; }
    .me { background-color: #dcf8c6; margin-left: auto; }
    .you { background-color: #eee; }
    form { margin-top: 20px; }
    textarea { width: 100%; height: 80px; font-size: 16px; }
    button { padding: 10px 20px; font-size: 16px; }
  </style>
</head>
<body>
  <h2>💬 チャット画面</h2>

  <?php while ($row = $result->fetch_assoc()): ?>
    <div class="message <?= ($row["from_user_id"] == $from_user_id) ? 'me' : 'you' ?>">
      <?= htmlspecialchars($row["message"]) ?>
      <small><?= htmlspecialchars($row["created_at"]) ?></small>
    </div>
  <?php endwhile; ?>

  <form method="POST" action="send_message.php">
    <input type="hidden" name="to_user_id" value="<?= $to_user_id ?>">
    <textarea name="message" required></textarea>
    <button type="submit">📩 送信</button>
  </form>
</body>
</html>
