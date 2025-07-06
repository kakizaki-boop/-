<?php
session_start();
$user_id = $_SESSION["user_id"] ?? null;

if (!$user_id) {
  die("ログインしていません");
}

// 入力データを取得
$summary = $_POST["summary"] ?? "";
$body = $_POST["body"] ?? "";
$hashtags = $_POST["hashtags"] ?? "";
$visibility = $_POST["visibility"] ?? "";

if (trim($body) === "" || $visibility === "") {
  die("本文と公開範囲は必須です");
}

// ファイルアップロード処理
$media_path = null;
if (isset($_FILES["media"]) && $_FILES["media"]["error"] === UPLOAD_ERR_OK) {
  $upload_dir = "uploads/media/";
  if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
  }

  $ext = pathinfo($_FILES["media"]["name"], PATHINFO_EXTENSION);
  $filename = "media_" . uniqid() . "." . $ext;
  move_uploaded_file($_FILES["media"]["tmp_name"], $upload_dir . $filename);
  $media_path = $filename;
}

// DB保存
$conn = new mysqli("localhost", "root", "", "learning_app");

$stmt = $conn->prepare("
  INSERT INTO posts (user_id, summary, body, media_path, hashtags, visibility)
  VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("isssss", $user_id, $summary, $body, $media_path, $hashtags, $visibility);
$stmt->execute();

echo "<p>投稿が完了しました！</p>";
echo "<a href='feed.php'>投稿一覧を見る</a>";
?>
