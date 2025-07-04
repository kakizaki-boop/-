<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: login.html");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $post_id = $_POST["post_id"];
  $reaction = $_POST["reaction"]; // "like" または "fire"

  // データベースに接続
  $conn = new mysqli("localhost", "root", "", "learning_app");

  // reaction が likes または fire なら処理
  if ($reaction === "like" || $reaction === "fire") {
    $field = ($reaction === "like") ? "likes" : "fire";

    $stmt = $conn->prepare("UPDATE posts SET $field = $field + 1 WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
  }
}

header("Location: feed.php");
exit();
?>
