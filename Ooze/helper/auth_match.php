<?php
if($url!="auth" && $url != "login") {
 
 if($_SESSION['userronin']!=NULL) {
  $pdo2 = new crud(); 
  $pdo2->conn();

  $stmt = $pdo2->db->prepare("select * FROM userronin WHERE login = ? ");
  $stmt->bindParam(1, $_SESSION['userronin'] , PDO::PARAM_STR );    
  $stmt->execute();
  $res=$stmt->fetchAll();
      $secret=$_SESSION['passronin'];
      $bcrypt_hash=$secret; 
       $bcrypt=new Bcrypt(12);

      $cont=0;
	if($bcrypt->verify($bcrypt_hash, $res[0]['pass'])=="false") {
          print "<img src=\"../view/images/alerta.png\">
           <h1>ERRor! at auth</h1> 
           <meta HTTP-EQUIV='refresh' CONTENT='2; URL=../view/login.php'>"; 
          exit;
       }
 } else {

       print "<img src=\"../view/images/alerta.png\">
         <h1>ERRor! at auth 033</h1> 
         <meta HTTP-EQUIV='refresh' CONTENT='4; URL=../view/login.php'>"; 

    exit; 
 }
} 
?>
