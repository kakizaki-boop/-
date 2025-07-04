<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = $_POST["username"];
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

  $conn = new mysqli("localhost", "root", "", "learning_app");

  if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
  }

  $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();

  $success = "✅ 登録が完了しました！";
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ユーザー登録</title>
</head>
<body>
  <h2>新規アカウント登録</h2>
  <?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>
  <form method="POST" action="">
    <label>ユーザー名: <input type="text" name="username" required></label><br>
    <label>パスワード: <input type="password" name="password" required></label><br>
    <input type="submit" value="登録">
  </form>
</body>
</html>
