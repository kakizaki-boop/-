<?php
session_start();

// フォームからの入力を受け取る
$identifier = $_POST["identifier"];  // メールアドレス or 電話番号
$password = $_POST["password"];
$password_confirm = $_POST["password_confirm"];

// パスワードの一致チェック
if ($password !== $password_confirm) {
  die("パスワードが一致していません。戻ってもう一度確認してください。");
}

// 認証コードを6桁で生成
$code = rand(100000, 999999);

// 入力内容とコードをセッションに一時保存（本登録はまだしない）
$_SESSION["register_data"] = [
  "identifier" => $identifier,
  "password" => password_hash($password, PASSWORD_DEFAULT),
  "code" => $code,
  "created" => time()
];

// デモ用表示：実際はメールやSMSで送信
echo "<p>認証コードを送信しました！（デモコード：<strong>$code</strong>）</p>";
?>

<!-- 認証コードの入力フォーム -->
<form action="verify_register.php" method="POST">
  <label>届いた認証コードを入力：</label><br>
  <input type="text" name="code" required><br><br>
  <button type="submit">認証して登録完了</button>
</form>
