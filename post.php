<?php session_start(); ?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <title>投稿する</title>
</head>
<body>
  <h2>新しい投稿を作成</h2>

  <form action="save_post.php" method="POST" enctype="multipart/form-data">
    
    <label>概要（任意）：</label><br>
    <input type="text" name="summary" maxlength="255"><br><br>

    <label>投稿本文<span style="color:red;">※必須</span>：</label><br>
    <textarea name="body" required rows="5" cols="40"></textarea><br><br>

    <label>画像または動画ファイル（任意）：</label><br>
    <input type="file" name="media" accept="image/*,video/mp4,video/webm"><br><br>

    <label>ハッシュタグ（任意）：</label><br>
    <input type="text" name="hashtags" placeholder="例：#進路, #受験"><br><br>

    <label>公開範囲<span style="color:red;">※必須</span>：</label><br>
    <select name="visibility" required>
      <option value="">選択してください</option>
      <option value="public">全体に公開</option>
      <option value="friends">友達のみに公開</option>
    </select><br><br>

    <button type="submit">投稿する</button>
  </form>
</body>
</html>
