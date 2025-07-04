<?php
session_start();
$my_id = $_SESSION["user_id"];
$viewing_id = $_GET["id"] ?? null;  // 今見てるプロフィールのID

// すでにログイン中で、かつ自分自身ではないユーザーを見ている場合だけボタンを表示
if ($viewing_id && $my_id !== $viewing_id) {
?>
  <form method="POST" action="send_request.php">
    <input type="hidden" name="to_user_id" value="<?= htmlspecialchars($viewing_id) ?>">
    <button type="submit">👋 友達になる</button>
  </form>
<?php } ?>
