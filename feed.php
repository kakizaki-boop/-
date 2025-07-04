<?php
session_start();
$user_id = $_SESSION["user_id"] ?? null;

if (!$user_id) {
  die("ログインしてください");
}

$conn = new mysqli("localhost", "root", "", "learning_app");

// 投稿一覧＋ユーザー情報
$sql = "SELECT posts.*, users.display_name, users.school_name, users.icon_path, users.id as user_id
        FROM posts
        JOIN users ON posts.user_id = users.id
        ORDER BY posts.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>投稿一覧</title>
  <style>
    .like-btn {
      cursor: pointer;
      font-size: 18px;
      color: #aaa;
    }
    .liked {
      color: red;
    }
  </style>
</head>
<body>
  <h2>投稿一覧（新着順）</h2>

  <?php while ($row = $result->fetch_assoc()): ?>
    <?php
      if ($row["visibility"] === "friends" && $row["user_id"] != $user_id) {
        continue;
      }

      // 投稿ごとのID
      $post_id = $row["id"];

      // この投稿のいいね件数
      $like_count_result = $conn->query("SELECT COUNT(*) as cnt FROM likes WHERE post_id = $post_id");
      $like_count = $like_count_result->fetch_assoc()["cnt"];

      // このユーザーがこの投稿にいいね済みかチェック
      $liked_result = $conn->query("SELECT * FROM likes WHERE user_id = $user_id AND post_id = $post_id");
      $is_liked = $liked_result->num_rows > 0;
    ?>

    <div style="border:1px solid #ccc; margin: 20px; padding: 10px;">
      <!-- 投稿者情報 -->
      <div>
        <?php if ($row["icon_path"]): ?>
          <img src="uploads/icons/<?= htmlspecialchars($row["icon_path"]) ?>" width="40" style="vertical-align:middle;">
        <?php endif; ?>

        <strong>
          <a href="user_profile.php?id=<?= $row["user_id"] ?>">
            <?= htmlspecialchars($row["display_name"]) ?>
          </a>
        </strong>

        <?php if ($row["school_name"]): ?>
          (<?= htmlspecialchars($row["school_name"]) ?>)
        <?php endif; ?>
      </div>

      <!-- 概要 -->
      <?php if ($row["summary"]): ?>
        <p><strong>概要：</strong><?= nl2br(htmlspecialchars($row["summary"])) ?></p>
      <?php endif; ?>

      <!-- 本文 -->
      <p><?= nl2br(htmlspecialchars($row["body"])) ?></p>

      <!-- メディア表示 -->
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

      <!-- ハッシュタグ -->
      <?php if ($row["hashtags"]): ?>
        <p style="color: #3366cc;">タグ：<?= htmlspecialchars($row["hashtags"]) ?></p>
      <?php endif; ?>

      <!-- ❤️ いいね機能 -->
      <p>
        <span class="like-btn <?= $is_liked ? 'liked' : '' ?>" data-post-id="<?= $post_id ?>">❤️</span>
        <span class="like-count" id="like-count-<?= $post_id ?>"><?= $like_count ?></span> 件のいいね
      </p>

      <!-- 投稿日時 -->
      <small>投稿日時：<?= $row["created_at"] ?></small>
    </div>
  <?php endwhile; ?>

  <!-- jQuery（CDN） -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    $(document).ready(function() {
      $(".like-btn").click(function() {
        const btn = $(this);
        const postId = btn.data("post-id");

        $.post("like_toggle.php", { post_id: postId }, function(response) {
          const data = JSON.parse(response);
          if (data.success) {
            const countEl = $("#like-count-" + postId);
            countEl.text(data.like_count);
            btn.toggleClass("liked");
          } else {
            alert("エラー：" + data.message);
          }
        });
      });
    });
  </script>
</body>
</html>
