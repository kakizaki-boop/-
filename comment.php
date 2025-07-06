<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: login.html");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $post_id = $_POST["post_id"];
  $comment = $_POST["comment"];
  $user_id = $_SESSION["user_id"];

  $conn = new mysqli("localhost", "root", "", "learning_app");
  $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)");
  $stmt->bind_param("iis", $post_id, $user_id, $comment);
  $stmt->execute();
}

header("Location: feed.php");
exit();
?>
