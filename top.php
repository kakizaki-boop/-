<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ✅ ログインチェック
if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION["user_id"];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ホーム</title>
  <style>
    body { font-family: sans-serif; text-align: center; margin-top: 50px; }
    .button { display: inline-block; margin: 20px; padding: 15px 30px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 8px; font-size: 18px; }
    .button:hover { background-color: #45a049; }
  </style>
</head>
<body>
  <h2>ようこそ！</h2>

  <a class="button" href="post.php">📝 投稿する</a>
  <a class="button" href="feed.php">📖 他の人の投稿を見る</a>

  <!-- 🔍 今後追加予定の機能 -->
  <br><br>
  <a class="button" href="hashtag_search.php">🔎 ハッシュタグで投稿を探す（準備中）</a>

  <br><br>
  <a class="button" href="profile.php">🧑‍💼 自分のプロフィール編集</a>
  <a class="button" href="friends.php">👥 友達一覧へ</a>
</body>
</html>
