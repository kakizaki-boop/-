<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: login.html");
  exit();
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>ダッシュボード</title></head>
<body>
  <h2>ログイン成功！ようこそ🎉</h2>
  <p><a href="logout.php">ログアウトする</a></p>
</body>
</html>
