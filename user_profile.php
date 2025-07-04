<?php
session_start();
$viewer_id = $_SESSION["user_id"] ?? null;

if (!$viewer_id) {
  die("ログインしていません");
}

$user_id = $_GET["id"] ?? null;

if (!$user_id) {
  die("ユーザーIDが指定されていません");
}

$conn = new mysqli("localhost", "root", "", "learning_app");

// プロフィール取得
$stmt = $conn->prepare("SELECT display_name, real_name, birthday, age, major_type, interest_area, school_name, icon_path FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();

if (!$profile) {
  die("指定されたユーザーは存在しません");
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($profile["display_name"]) ?>さんのプロフィール</title>
</head>
<body>
  <h2><?= htmlspecialchars($profile["display_name"]) ?> さんのプロフィール</h2>

  <?php if ($profile["icon_path"]): ?>
    <img src="uploads/icons/<?= htmlspecialchars($profile["icon_path"]) ?>" width="100"><br>
  <?php endif; ?>

  <p><strong>学校：</strong> <?= htmlspecialchars($profile["school_name"] ?? "") ?></p>
  <p><strong>年齢：</strong> <?= htmlspecialchars($profile["age"]) ?>歳</p>
  <p><strong>文理：</strong> <?= htmlspecialchars($profile["major_type"]) ?></p>
  <p><strong>興味分野：</strong> <?= htmlspecialchars($profile["interest_area"]) ?></p>

  <hr>

  <h3>このユーザーの投稿</h3>

  <?php
  // 投稿取得（公開範囲制御あり）
  $stmt = $conn->prepare("
    SELECT * FROM posts 
    WHERE user_id = ? AND (visibility = 'public' OR user_id = ?)
    ORDER BY created_at DESC
  ");
  $stmt->bind_param("ii", $user_id, $viewer_id);
  $stmt->execute();
  $posts = $stmt->get_result();

  while ($post = $posts->fetch_assoc()):
  ?>
    <div style="border:1px solid #ccc; margin:10px; padding:10px;">
      <?php if ($post["summary"]): ?>
        <strong>概要：</strong> <?= nl2br(htmlspecialchars($post["summary"])) ?><br>
      <?php endif; ?>

      <p><?= nl2br(htmlspecialchars($post["body"])) ?></p>

      <?php if ($post["media_path"]): ?>
        <?php
          $ext = pathinfo($post["media_path"], PATHINFO_EXTENSION);
          $media_url = "uploads/media/" . htmlspecialchars($post["media_path"]);
        ?>
        <?php if (in_array(strtolower($ext), ["jpg", "jpeg", "png", "gif"])): ?>
          <img src="<?= $media_url ?>" width="300"><br>
        <?php elseif (in_array(strtolower($ext), ["mp4", "webm"])): ?>
          <video src="<?= $media_url ?>" width="300" controls></video><br>
        <?php endif; ?>
      <?php endif; ?>

      <?php if ($post["hashtags"]): ?>
        <p style="color:#3366cc;">タグ：<?= htmlspecialchars($post["hashtags"]) ?></p>
      <?php endif; ?>

      <small>投稿日：<?= $post["created_at"] ?></small>
    </div>
  <?php endwhile; ?>
</body>
</html>
