<?php
session_start();
$user_id = $_SESSION["user_id"] ?? null;

if (!$user_id) {
  die("ログインしてください");
}

$conn = new mysqli("localhost", "root", "", "learning_app");

// 自分の投稿だけ取得（新しい順）
$stmt = $conn->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>自分の投稿一覧</title>
</head>
<body>
  <h2>あなたの投稿一覧</h2>

  <?php while ($row = $result->fetch_assoc()): ?>
    <div style="border:1px solid #ccc; margin:20px; padding:10px;">
      
      <?php if ($row["summary"]): ?>
        <p><strong>概要：</strong><?= nl2br(htmlspecialchars($row["summary"])) ?></p>
      <?php endif; ?>

      <p><?= nl2br(htmlspecialchars($row["body"])) ?></p>

      <?php if ($row["media_path"]): ?>
        <?php
          $ext = pathinfo($row["media_path"], PATHINFO_EXTENSION);
          $media_url = "uploads/media/" . htmlspecialchars($row["media_path"]);
        ?>
        <?php if (in_array(strtolower($ext), ["jpg", "jpeg", "png", "gif"])): ?>
          <img src="<?= $media_url ?>" width="300"><br>
        <?php elseif (in_array(strtolower($ext), ["mp4", "webm"])): ?>
          <video src="<?= $media_url ?>" width="300" controls></video><br>
        <?php endif; ?>
      <?php endif; ?>

      <?php if ($row["hashtags"]): ?>
        <p style="color:#3366cc;">タグ：<?= htmlspecialchars($row["hashtags"]) ?></p>
      <?php endif; ?>

      <small>投稿日時：<?= $row["created_at"] ?></small>
    </div>
  <?php endwhile; ?>
</body>
</html>
