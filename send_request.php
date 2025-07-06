<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ✅ ログインユーザーの取得
$from_user_id = $_SESSION["user_id"] ?? null;
$to_user_id = $_POST["to_user_id"] ?? null;

// ✅ 入力チェック
if (!$from_user_id || !$to_user_id || $from_user_id == $to_user_id) {
  die("不正なリクエストです");
}

// ✅ DB接続
$conn = new mysqli("localhost", "root", "", "learning_app");
if ($conn->connect_error) {
  die("接続失敗: " . $conn->connect_error);
}

// ✅ 重複申請チェック（すでに申請済みならスキップ）
$check = $conn->prepare("
  SELECT * FROM friend_requests
  WHERE from_user_id = ? AND to_user_id = ? AND status = 'pending'
");
$check->bind_param("ii", $from_user_id, $to_user_id);
$check->execute();
$result = $check->get_result();

if ($result && $result->num_rows > 0) {
  // すでに申請済み
  header("Location: view_profile.php?id=$to_user_id");
  exit();
}

// ✅ 新規フレンド申請を登録
$stmt = $conn->prepare("
  INSERT INTO friend_requests (from_user_id, to_user_id)
  VALUES (?, ?)
");
$stmt->bind_param("ii", $from_user_id, $to_user_id);
$stmt->execute();

// ✅ 完了後に元のプロフィールへ戻る
header("Location: view_profile.php?id=$to_user_id");
exit();
?>
