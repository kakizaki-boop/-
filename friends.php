<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ✅ ログインチェック
if (!isset($_SESSION["user_id"])) {
  die("ログインしてください");
}
$user_id = $_SESSION["user_id"];

// ✅ データベース接続
$conn = new mysqli("localhost", "root", "", "learning_app");
if ($conn->connect_error) {
  die("接続失敗: " . $conn->connect_error);
}

// ✅ 「友達」になったユーザーを取得（自分が from でも to でもOK）
$stmt = $conn->prepare("
  SELECT u.id, u.username
  FROM friend_requests fr
  JOIN users u ON (
    (fr.from_user_id = u.id AND fr.to_user_id = ?) OR
    (fr.to_user_id = u.id AND fr.from_user_id = ?)
  )
  WHERE fr.status = 'accepted'
");
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>👥 あなたの友達一覧</h2>";

if ($result->num_rows === 0) {
  echo "<p>まだ友達がいません。</p>";
} else {
  while ($row = $result->fetch_assoc()) {
    echo "<p>👤 " . htmlspecialchars($row["username"]) . "</p>";
    // チャットや削除ボタン追加もここに書ける
  }
}
?>
