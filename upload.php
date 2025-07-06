<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  die("ログインしていません");
}

$text = $_POST["text"];
$user_id = $_SESSION["user_id"];
$image_path = "";

if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
  $filename = uniqid() . "_" . basename($_FILES["image"]["name"]);
  $target = "uploads/" . $filename;

  if (move_uploaded_file($_FILES["image"]["tmp_name"], $target)) {
    $image_path = $target;
  }
}

$conn = new mysqli("localhost", "root", "", "learning_app");
$stmt = $conn->prepare("INSERT INTO posts (user_id, text, image_path) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $text, $image_path);
$stmt->execute();

echo "✅ 投稿完了！ <a href='feed.php'>投稿一覧を見る</a>";
?>
