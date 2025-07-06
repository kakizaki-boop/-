<?php
session_start();
$user_id = $_SESSION["user_id"] ?? null;

if (!$user_id) {
  die("ログインしていません");
}

// データ受け取り
$real_name = $_POST["real_name"] ?? "";
$display_name = $_POST["display_name"] ?? "";
$age = $_POST["age"] ?? "";
$birthday = $_POST["birthday"] ?? "";
$major_type = $_POST["major_type"] ?? "";
$interest_area = $_POST["interest_area"] ?? "";
$school_name = $_POST["school_name"] ?? "";

// ファイルアップロード処理
$icon_path = null;
if (isset($_FILES["icon"]) && $_FILES["icon"]["error"] === UPLOAD_ERR_OK) {
  $ext = pathinfo($_FILES["icon"]["name"], PATHINFO_EXTENSION);
  $filename = "icon_" . uniqid() . "." . $ext;
  $upload_dir = "uploads/icons/";
  if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
  }
  move_uploaded_file($_FILES["icon"]["tmp_name"], $upload_dir . $filename);
  $icon_path = $filename;
}

// データベース更新
$conn = new mysqli("localhost", "root", "", "learning_app");

// アイコンがあるかないかで処理を分岐
if ($icon_path) {
  $stmt = $conn->prepare("
    UPDATE users SET real_name = ?, display_name = ?, age = ?, birthday = ?, major_type = ?, interest_area = ?, school_name = ?, icon_path = ?
    WHERE id = ?
  ");
  $stmt->bind_param("ssisssssi", $real_name, $display_name, $age, $birthday, $major_type, $interest_area, $school_name, $icon_path, $user_id);
} else {
  $stmt = $conn->prepare("
    UPDATE users SET real_name = ?, display_name = ?, age = ?, birthday = ?, major_type = ?, interest_area = ?, school_name = ?
    WHERE id = ?
  ");
  $stmt->bind_param("ssissssi", $real_name, $display_name, $age, $birthday, $major_type, $interest_area, $school_name, $user_id);
}

$stmt->execute();

echo "<p>プロフィールを更新しました！</p>";
echo "<a href='my_profile.php'>戻る</a>";
?>
