<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>プロフィール入力</title>
</head>
<body>
  <h2>プロフィール登録</h2>

  <form action="save_profile.php" method="POST" enctype="multipart/form-data">
    
    <label>本名（非公開）<span style="color:red;">※必須</span>：</label><br>
    <input type="text" name="real_name" required><br><br>

    <label>表示名（ニックネーム）<span style="color:red;">※必須</span>：</label><br>
    <input type="text" name="display_name" required><br><br>

    <label>生年月日<span style="color:red;">※必須</span>：</label><br>
    <input type="date" name="birthday" required><br><br>

    <label>年齢<span style="color:red;">※必須</span>：</label><br>
    <input type="number" name="age" min="0" required><br><br>

    <label>文理タイプ<span style="color:red;">※必須</span>：</label><br>
    <select name="major_type" required>
      <option value="">--選択してください--</option>
      <option value="文系">文系</option>
      <option value="理系">理系</option>
      <option value="その他">その他</option>
    </select><br><br>

    <label>興味のある分野<span style="color:red;">※必須</span>：</label><br>
    <input type="text" name="interest_area" required><br><br>

    <label>学校名（任意）：</label><br>
    <input type="text" name="school_name"><br><br>

    <label>アイコン画像アップロード（任意）：</label><br>
    <input type="file" name="icon" accept="image/*"><br><br>

    <button type="submit">プロフィールを登録する</button>

  </form>
</body>
</html>
