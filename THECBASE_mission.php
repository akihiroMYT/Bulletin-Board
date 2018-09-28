<html>
<body>
<?php

//SQL接続
$dsn='database name';
$user='user name';
$password='password';
$pdo=new PDO($dsn,$user,$password);


//テーブル作成
$judge=TRUE;
$sql="SHOW TABLES";
$result=$pdo->query($sql);
foreach($result as $row){
  if($row[0]=='textdata'){
    $judge=FALSE;
  }
}
if($judge){
$sql="CREATE TABLE textdata"."("."id INT,"."name char(32),"."comment TEXT,"."date TEXT,"."password TEXT".");";
$stmt=$pdo->query($sql);
}


//編集判定
if (empty($_POST['edit_number'])){
  $edit_value="";
  $edit_name="";
  $edit_comment="";
} else {
  $edit_value="";
  $sql="SELECT*FROM textdata";
  $results=$pdo->query($sql);
  foreach($results as $row){
    if($_POST['edit_number'] == $row['id'] AND  $_POST['password_3'] == $row['password']){
      $edit_value=$_POST['edit_number'];
      $edit_name=$row['name'];
      $edit_comment=$row['comment'];
    }
  }
}


//変数作成
$name=$_POST['name'];
$comment=$_POST['comment'];
$deletion_number=$_POST['deletion_number'];
$password1=$_POST['password_1'];
$edit=$_POST['edit'];
$number=0;
$date=date("Y年m月d日 H時i分s秒");


if (empty($edit)){
    if (!empty($name) AND !empty($comment) AND !empty($password1)){
       $sql="SELECT*FROM textdata";
       $results=$pdo->query($sql);
         foreach($results as $row){
           if($row['id']>$number){
             $number=$row['id'];
           }
         }
      $number=$number+1;
      $pdo=new PDO($dsn,$user,$password);
      $sql=$pdo->prepare("INSERT INTO textdata (id,name,comment,date,password) VALUES (:id,:name,:comment,:date,:password)");
      $sql->bindParam(':id',$number,PDO::PARAM_STR);
      $sql->bindParam(':name',$name,PDO::PARAM_STR);
      $sql->bindParam(':comment',$comment,PDO::PARAM_STR);
      $sql->bindParam(':date',$date,PDO::PARAM_STR);
      $sql->bindParam(':password',$password1,PDO::PARAM_STR);
      $sql->execute();
      
      if(empty($deletion_number) AND empty($edit_value)){
        header('location:mission_4.php');
        exit();  
      }
      
    }

    if (!empty($deletion_number)){
      $id=intval($deletion_number);
      $password2=$_POST['password_2'];
      $sql="delete from textdata where id=$id AND password='$password2'";
      $result=$pdo->query($sql);

      if (!empty($name) AND !empty($comment) AND !empty($password1)){
        if(empty($edit_value)){
          header('location:mission_4.php');
          exit();
        }
      }
    }

}


if (!empty($edit)){
  if (!empty($name) AND !empty($comment)){
    $id=intval($edit);
    $sql="update textdata set name='$name', comment='$comment', date='$date' where id=$id ";
    $result=$pdo->query($sql);
    header('location:mission_4.php');
    exit();
  }
}

?>
<form method="POST" action="mission_4.php">
<input type="text" name="name" value="<?php print $edit_name; ?>" placeholder="名前"><br/>
<input type="text" name="comment" value="<?php print $edit_comment; ?>" placeholder="コメント"><br/>
<input type="text" name="password_1" value="" placeholder="パスワード">
<input type="hidden" name="edit" value="<?php print $edit_value; ?>">
<input type="submit" value="送信"><br/>
<br/>
<input type="text" name="deletion_number" value="" placeholder="削除対象番号"><br/>
<input type="text" name="password_2" value="" placeholder="パスワード">
<input type="submit" value="削除"><br/>
<br/>
<input type="text" name="edit_number" value="" placeholder="編集対象番号"><br/>
<input type="text" name="password_3" value="" placeholder="パスワード">
<input type="submit" value="編集">
</form>
<?php

//掲示板内容表示

echo "<br>";
$sql="SELECT*FROM textdata ORDER BY id ASC";
$results=$pdo->query($sql);
foreach($results as $row){
  echo $row['id'].' ';
  echo $row['name'].' ';
  echo $row['comment'].' ';
  echo $row['date'];
  echo "<br>";
}

?>
</body>
</html>