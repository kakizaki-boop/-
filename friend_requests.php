<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ✅ ログインチェック
if (!isset($_SESSION["user_id"])) {
  die("ログインしてください");
}

$user_id = $_SESSION["user_id"];

// ✅ DB接続
$conn = new mysqli("localhost", "root", "", "learning_app");
if ($conn->connect_error) {
  die("接続失敗: " . $conn->connect_error);
}

// ✅ 自分に届いてる「保留中の申請」を取得
$stmt = $conn->prepare("
  SELECT fr.id AS request_id, u.username, u.id AS from_user_id
  FROM friend_requests fr
  JOIN users u ON fr.from_user_id = u.id
  WHERE fr.to_user_id = ? AND fr.status = 'pending'
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// ✅ 申請が1件もない場合
if ($result->num_rows === 0) {
  echo "<p>現在、友達申請は届いていません。</p>";
} else {
  while ($row = $result->fetch_assoc()) {
    echo "<p><strong>" . htmlspecialchars($row["username"]) . "</strong> さんからフレンド申請があります。</p>";
    echo '<form method="POST" action="respond_request.php" style="margin-bottom: 10px;">';
    echo '<input type="hidden" name="request_id" value="' . $row["request_id"] . '">';
    echo '<button type="submit" name="action" value="accept">✅ 承認</button> ';
    echo '<button type="submit" name="action" value="reject">❌ 拒否</button>';
    echo '</form>';
  }
}
?>
