<?php
session_start();
$user_id = $_SESSION["user_id"] ?? null;

if (!$user_id) {
  die("ログインしていません");
}

// 入力データの取得
$real_name = $_POST["real_name"];
$display_name = $_POST["display_name"];
$birthday = $_POST["birthday"];
$age = $_POST["age"];
$major_type = $_POST["major_type"];
$interest_area = $_POST["interest_area"];
$school_name = $_POST["school_name"] ?? "";

// アップロード処理
$icon_filename = null;

if (isset($_FILES["icon"]) && $_FILES["icon"]["error"] === UPLOAD_ERR_OK) {
  $upload_dir = "uploads/icons/";
  if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
  }

  $tmp = $_FILES["icon"]["tmp_name"];
  $name = basename($_FILES["icon"]["name"]);
  $ext = pathinfo($name, PATHINFO_EXTENSION);
  $icon_filename = "icon_user{$user_id}." . $ext;
  move_uploaded_file($tmp, $upload_dir . $icon_filename);
}

// データベース更新
$conn = new mysqli("localhost", "root", "", "learning_app");

$stmt = $conn->prepare("
  UPDATE users SET
    real_name = ?, display_name = ?, birthday = ?, age = ?, major_type = ?,
    interest_area = ?, school_name = ?, icon_path = ?
  WHERE id = ?
");

$stmt->bind_param(
  "sssissssi",
  $real_name,
  $display_name,
  $birthday,
  $age,
  $major_type,
  $interest_area,
  $school_name,
  $icon_filename,
  $user_id
);

$stmt->execute();

// 完了画面（または次のページへ）
echo "<p>プロフィールが登録されました！</p>";
echo "<a href='feed.php'>投稿一覧へ進む</a>";
?>
