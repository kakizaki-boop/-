<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ✅ ログインチェック
if (!isset($_SESSION["user_id"])) {
  die("ログインが必要です");
}

$my_id = $_SESSION["user_id"];
$viewing_id = $_GET["id"] ?? null;

if (!$viewing_id) {
  die("ユーザーIDが指定されていません");
}

// ✅ DB接続
$conn = new mysqli("localhost", "root", "", "learning_app");
if ($conn->connect_error) {
  die("接続失敗: " . $conn->connect_error);
}

// ✅ 表示対象のユーザー情報を取得
$stmt = $conn->prepare("SELECT id, username FROM users WHERE id = ?");
$stmt->bind_param("i", $viewing_id);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
  // 表示スタート
  echo "<h2>" . htmlspecialchars($user["username"]) . " さんのプロフィール</h2>";

  // ✅ 自分以外だったら「友達になる」ボタンを表示
  if ($user["id"] != $my_id) {
    echo '<form method="POST" action="send_request.php">';
    echo '<input type="hidden" name="to_user_id" value="' . htmlspecialchars($user["id"]) . '">';
    echo '<button type="submit">👋 友達になる</button>';
    echo '</form>';
  } else {
    echo "<p>これはあなた自身のプロフィールです。</p>";
  }
} else {
  echo "ユーザーが見つかりませんでした。";
}
?>
