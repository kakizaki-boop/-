<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ✅ ログインフォームからのPOST取得
$email = $_POST["email"] ?? null;
$password = $_POST["password"] ?? null;

// ✅ 入力チェック
if (!$email || !$password) {
  die("メールアドレスとパスワードを入力してください");
}

// ✅ データベース接続
$conn = new mysqli("localhost", "root", "", "learning_app");
if ($conn->connect_error) {
  die("接続失敗: " . $conn->connect_error);
}

// ✅ ユーザー検索
$stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
  // ✅ パスワード確認
  if (password_verify($password, $user["password_hash"])) {
    $_SESSION["user_id"] = $user["id"];

    // ✅ ログイン成功 → ホーム画面に移動
    header("Location: top.php");
    exit();
  } else {
    echo "パスワードが違います";
  }
} else {
  echo "ユーザーが見つかりません";
}
?>
