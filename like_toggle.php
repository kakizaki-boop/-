<?php
session_start();
header("Content-Type: application/json");

$user_id = $_SESSION["user_id"] ?? null;
$post_id = $_POST["post_id"] ?? null;

if (!$user_id || !$post_id) {
  echo json_encode(["success" => false, "message" => "データが足りません"]);
  exit;
}

$conn = new mysqli("localhost", "root", "", "learning_app");

// すでにいいね済みか？
$stmt = $conn->prepare("SELECT id FROM likes WHERE user_id = ? AND post_id = ?");
$stmt->bind_param("ii", $user_id, $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  // いいね済 → 削除
  $stmt = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND post_id = ?");
  $stmt->bind_param("ii", $user_id, $post_id);
  $stmt->execute();
} else {
  // 未いいね → 追加
  $stmt = $conn->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
  $stmt->bind_param("ii", $user_id, $post_id);
  $stmt->execute();
}

// 最新のいいね件数を取得
$stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM likes WHERE post_id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$like_count = $result->fetch_assoc()["cnt"];

echo json_encode([
  "success" => true,
  "like_count" => $like_count
]);
