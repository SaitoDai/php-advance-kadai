<?php

if(isset($_GET['id'])){

  try{
    $dsn = 'mysql:dbname=php_book_app;host=localhost;carset=utf8mb4';
    $user = 'root';
    $password = '';

$pdo = new PDO($dsn, $user, $password);

$sql = 'DELETE FROM books WHERE id = :id';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

$stmt->execute();

$count = $stmt->rowCount();
$message = "{$count}件を<span style='color:red'>削除</span>しました。";


header("Location: read.php?message={$message}");

  } catch (PDOexception $e){
    exit($e->getMessage());
  }
} else {
  NULL;
}