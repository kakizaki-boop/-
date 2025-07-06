<?php
session_start();

if (!isset($_POST["code"]) || !isset($_SESSION["register_data"])) {
  die("不正なアクセスです。");
}

$input_code = $_POST["code"];
$stored = $_SESSION["register_data"];

// コードが一致しない or 時間切れ
if ($input_code != $stored["code"] || time() - $stored["created"] > 600) {
  die("認証コードが間違っているか、有効期限が切れています。");
}

// DBに登録処理
$conn = new mysqli("localhost", "root", "", "learning_app");

// identifier がメールか電話か判断（自動判定）
$identifier = $stored["identifier"];
$email = (str_contains($identifier, "@")) ? $identifier : null;
$phone = (str_contains($identifier, "@")) ? null : $identifier;

$stmt = $conn->prepare("INSERT INTO users (email, phone, password, verified) VALUES (?, ?, ?, 1)");
$stmt->bind_param("sss", $email, $phone, $stored["password"]);
$stmt->execute();

// セッション初期化
unset($_SESSION["register_data"]);
echo "登録が完了しました！<a href='login.html'>ログインはこちら</a>";
?>
