<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ✅ ログインチェック
if (!isset($_SESSION["user_id"])) {
  die("ログインが必要です");
}

$user_id = $_SESSION["user_id"];
$request_id = $_POST["request_id"] ?? null;
$action = $_POST["action"] ?? null;

// ✅ 入力チェック
if (!$request_id || !in_array($action, ["accept", "reject"])) {
  die("不正なリクエストです");
}

$status = ($action === "accept") ? "accepted" : "rejected";

// ✅ データベース接続
$conn = new mysqli("localhost", "root", "", "learning_app");
if ($conn->connect_error) {
  die("接続失敗: " . $conn->connect_error);
}

// ✅ 該当リクエストのステータスを更新（自分宛ての申請に限る）
$stmt = $conn->prepare("
  UPDATE friend_requests
  SET status = ?
  WHERE id = ? AND to_user_id = ?
");
$stmt->bind_param("sii", $status, $request_id, $user_id);
$stmt->execute();

// ✅ 更新完了後、申請一覧に戻る
header("Location: friend_requests.php");
exit();
?>
