<?php

    $dsn = 'mysql:dbname=php_book_app;host=localhost;carset=utf8mb4';
    $user = 'root';
    $password = '';


//POST操作
if(isset($_POST['submit'])){
  try  {
    $pdo = new PDO($dsn, $user, $password);

    $sql_update = 'UPDATE books SET
     book_code = :book_code,
     book_name = :book_name,
     price = :price,
     stock_quantity = :stock_quantity,
     genre_code = :genre_code,
     updated_at = now()
     WHERE id = :id
     ';



    $stmt_update = $pdo->prepare($sql_update);

    $stmt_update->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $stmt_update->bindValue(':book_code', $_POST['book_code'], PDO::PARAM_STR);
    $stmt_update->bindValue(':book_name', $_POST['book_name'], PDO::PARAM_STR);
    $stmt_update->bindValue(':price', $_POST['price'], PDO::PARAM_INT);
    $stmt_update->bindValue(':stock_quantity', $_POST['stock_quantity'], PDO::PARAM_INT);
    $stmt_update->bindValue(':genre_code', $_POST['genre_code'], PDO::PARAM_INT);


    $stmt_update->execute();

    $book = $stmt_update->fetchAll(PDO::FETCH_ASSOC);

    $count = $stmt_update->rowCount();
    $message = "{$count}件を<span style='color:green'>変更</span>しました。";

    header ("Location: read.php?message={$message}");

  } catch (PDOException $e) {
    exit($e->getMessage());
  }
}


//idパラメータがある場合のDB接続
if(isset($_GET['id'])){
    try {
      $pdo = new PDO($dsn, $user, $password);

      $sql = 'SELECT * FROM books WHERE id = :id';
      $stmt = $pdo->prepare($sql);

      $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

      $stmt->execute();

      $book = $stmt->fetch(PDO::FETCH_ASSOC);



      if ($book === FALSE){
       echo 'パラメータの値が不正です';
      }

      //genreコードを取得

      $sql_genre = 'SELECT genre_code FROM genres';
      $stmt_genre = $pdo->query($sql_genre);

      $stmt_genre->execute();

      $genres = $stmt_genre->fetchAll(PDO::FETCH_COLUMN);
      

      } catch (PDOException $e){
        exit($e->getMessage());
      }
} else {
  echo 'NULL';
}
?>

<!-- HTML開始 -->
<!DOCTYPE html>
<html>
  <title>書籍管理アプリ</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic:wght@300&display=swap" rel="stylesheet">
  <body>
    <header>
      <nav class="nav"><a href="php_book_app" class="navtext">書籍管理アプリ</a></nav>
    </header>
    <article class="main">
<h1>「<?= $book['book_name']?>」を編集</h1>



  <div>
  <p><a href='read.php' class='return-btn'>戻る</a></p>
  </div>


  <form class='form' acrion="register.php" method="post">

  <label>書籍コード</label><br>
  <input type="number" name="book_code" value="<?= $book['book_code'] ?>" required><br>

  <label>書籍名</label><br>
  <input type="text" name="book_name" value="<?= $book['book_name'] ?>" required><br>

  <label>単価</label><br>
  <input type="number" name="price" min="0" max="999999" required value="<?= $book['price']?>"><br>

  <label>在庫数</label><br>
  <input type="number" name="stock_quantity" required value="<?= $book['stock_quantity']?>"?><br>

  <label>ジャンルコード</label><br>
  <select name="genre_code">
   <option selected><?= $book['genre_code'] ?></option>  
   <?php
  foreach ($genres as $genre) {
   echo "<option value='{$genre}'>{$genre}</option>";
   }
   ?>
 
  </select><br>

  <button action="edit.php" method="post" type="submit" name="submit" value="変更">変更</button>
 </form>







</article>
    <footer>
      <p class="copyright">&copy;書籍管理アプリ All right reserved.</p>
    </footer>

  </body>
</html>