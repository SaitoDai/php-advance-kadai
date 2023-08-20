<?php

$dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = '';


try {
  $pdo = new PDO($dsn, $user, $password);

  if (isset($_GET['order'])) {
    $order = $_GET['order'];

  }else{
    $order = NULL;
  }

  if (isset($_GET['keyword'])){
    $keyword = $_GET['keyword'];

  } else {
    $keyword = NULL;
  }

    if($order === 'desc') {
      $sql = 'SELECT * FROM books WHERE book_name LIKE :keyword ORDER BY id DESC';

    } else {

      $sql = 'SELECT * FROM books WHERE book_name LIKE :keyword ORDER BY id ASC';
  }
  $stmt = $pdo->prepare($sql);
  $partial_match = "%{$keyword}%";

  $stmt->bindValue(':keyword', $partial_match, PDO::PARAM_STR);

  $stmt->execute();

  $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e){
    exit($e->getMessage());
    
}

?>

<!-- HTML開始 -->
<!DOCTYPE html>
<html>
  <title>書籍管理アプリ</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href='css/style.css'>
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic:wght@300&display=swap" rel="stylesheet">
  <body>
    <header>
      <nav class="nav"><a href="php_book_app" class="navtext">書籍管理アプリ</a></nav>
    </header>
    <article class="main">
<h1>書籍一覧</h1>
<?php 
   if(isset($_GET['message'])){
    echo "<p class='top-message'>{$_GET['message']}</p>";
  }

  ?>
<div class="products-ui">

  <div class='uis'>
  <a href='read.php?order=desc'><img src='images/desc.png' class='sort-btn'></a> 
  <a href='read.php?order=asc'><img src='images/asc.png' class='sort-btn'></a> 
<form  class='search-box' action='read.php' method='get'>
  <input type='textbox' placeholder='検索' name='keyword'>
</form>
</div>
  <p><a href="register.php" class="btn">商品登録</a></p>
</div>
<table class="products-table">
  <tr>
    <th>書籍コード</th>
    <th>書籍名</th>
    <th>単価</th>
    <th>在庫数</th>
    <th>ジャンルコード</th>
    <th>更新日</th>
    <th>編集</th>
    <th>削除</th>
  </tr>
    
  <?php
foreach ($books as $book) {
  $book_row = "
  <tr>
    <th>{$book['book_code']}</th>
    <th class='left'>{$book['book_name']}</th>
    <th>{$book['price']}</th>
    <th>{$book['stock_quantity']}</th>
    <th>{$book['genre_code']}</th>
    <th>{$book['updated_at']}</th>
    <th><a href='edit.php?id={$book['id']}'><img src='images/edit.png' class='icon'></a></th>
    <th><a href='delete.php?id={$book['id']}'><img src='images/delete.png' class='icon'></a></th>
  </tr>";

  echo $book_row;

}
 ?>
</table>


</article>
    <footer>
      <p class="copyright">&copy;書籍管理アプリ All right reserved.</p>
    </footer>

  </body>
</html>