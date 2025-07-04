<?php
session_start();
$user_id = $_SESSION["user_id"] ?? null;

if (!$user_id) {
  die("ログインしていません");
}

// データベース接続
$conn = new mysqli("localhost", "root", "", "learning_app");

// 現在のプロフィール情報を取得
$stmt = $conn->prepare("SELECT display_name, real_name, age, birthday, major_type, interest_area, school_name, icon_path FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();

if (!$profile) {
  die("プロフィールが見つかりませんでした。");
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>プロフィールを編集する</title>
</head>
<body>
  <h2>プロフィール編集</h2>

  <form action="update_profile.php" method="POST" enctype="multipart/form-data">

    <label>本名（非公開）：</label><br>
    <input type="text" name="real_name" value="<?= htmlspecialchars($profile['real_name']) ?>"><br><br>

    <label>表示名（ニックネーム）：</label><br>
    <input type="text" name="display_name" value="<?= htmlspecialchars($profile['display_name']) ?>" required><br><br>

    <label>年齢：</label><br>
    <input type="number" name="age" value="<?= htmlspecialchars($profile['age']) ?>" required><br><br>

    <label>生年月日：</label><br>
    <input type="date" name="birthday" value="<?= htmlspecialchars($profile['birthday']) ?>"><br><br>

    <label>文理ジャンル：</label><br>
    <select name="major_type" required>
      <option value="文系" <?= $profile["major_type"] === "文系" ? "selected" : "" ?>>文系</option>
      <option value="理系" <?= $profile["major_type"] === "理系" ? "selected" : "" ?>>理系</option>
      <option value="その他" <?= $profile["major_type"] === "その他" ? "selected" : "" ?>>その他</option>
    </select><br><br>

    <label>興味ある分野（例：数学、心理学など）：</label><br>
    <input type="text" name="interest_area" value="<?= htmlspecialchars($profile['interest_area']) ?>"><br><br>

    <label>学校名（任意）：</label><br>
    <input type="text" name="school_name" value="<?= htmlspecialchars($profile['school_name']) ?>"><br><br>

    <label>アイコン画像の変更（任意）：</label><br>
    <input type="file" name="icon"><br>
    <?php if ($profile["icon_path"]): ?>
      <img src="uploads/icons/<?= htmlspecialchars($profile["icon_path"]) ?>" width="100" alt="現在のアイコン"><br>
    <?php endif; ?>
    <br>

    <button type="submit">変更を保存する</button>
  </form>
</body>
</html>
