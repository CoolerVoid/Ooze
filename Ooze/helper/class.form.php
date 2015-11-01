<?php
/* class.form by Cooler_
     BSD license

Exemplo:

  require("./class.form.php");
  $form = new form();
      $values = array(
                  'titulo:text'=>'tituloadd:seu titulo', 
                  'nome:text'=>'nomeadd:seu nome'                              
                );
  $array = array(
                  "seu titulo", 
                  "seu nome"                              
                );
  $la.=$form->StartForm("index.php?ola");
  $la.=$form->SimpleForm($values);
  $la.=$form->SelectForm("escolha: ","ola.php",$array);
  $la.=$form->ExitForm("enviar");
  print $la;


*/
class form
{
        public function StartForm($action) {
            $start="<form id=\"form\" enctype=\"multipart/form-data\" action=\"".$action."\" method=\"POST\" class=\"form label-inline\">";
            return $start;
        }

        public function ExitForm($name) {
            $exit="<p><button><span>$name</span></button></form></p>";
            return $exit;
        }
      
        public function CkeditorForm($name,$action,$value) {
          $form="<p>".$name."<br><textarea name=\"".$action."\" id=\"editor_kama\" style=\"width:80%;height:200px;\" >
                ".$value."</textarea>".'<script type="text/javascript">CKEDITOR.replace( \'editor_kama\',
                 { skin : \'kama\' });</script>';
          return $form;
        }
                   
        public function TextForm($name,$action,$value) {
             $form="<p>".$name."<br><textarea name=\"".$action."\" style=\"width:80%;height:300px; \" >".$value."</textarea></p>";
          return $form;
        }

        public function SelectForm($name,$action,$values) {
          $select="<p>".$name."<select name=\"".$action."\">";
            foreach($values as $var) $select.="<option value=\"".$var."\" >".$var."</option>";          
            $select.="</select></p><br>";
            return $select;
        }
        public function SelectToEdit($name,$action,$values,$choice) {
          $select="<p>".$name."<select name=\"".$action."\">";
            foreach($values as $var) { 
             if($choice==$var) { 
               $select.="<option value=\"".$var."\" SELECTED>".$var."</option>";
             } else { 
               $select.="<option value=\"".$var."\" >".$var."</option>";
             }
            }          
            $select.="</select></p><br>";
            return $select;
        }
         public function CheckBox($name,$values,$check) {
            if(!$check) { }else { $check="CHECKED"; }
            $select=NULL;
            foreach($values as $var) 
             $select.="<input name=\"".$name."[]\" type=\"checkbox\" value=\"".$var."\" ".$check.">".$var."<br>";
          return $select;
         }
         public function CheckBoxOne($name,$value,$check) {
            if(!$check) { }else { $check="CHECKED"; }
            $select=NULL;
             $select.="<input name=\"".$name."[]\" type=\"checkbox\" value=\"".$value."\" ".$check.">".$value."<br>";
          return $select;
         }

        public function SimpleForm($values) {
          $form=NULL; $value=$namepost=$type=$name=NULL;
          if(isset($values)) {
          while (list($key, $val) = each($values)) {
           list($name,$type) = explode(":", $key, 2); 
           list($namepost,$value) = explode(":", $val, 2); 
           if($type != "hidden") {
            $form.= "<div class=\"field\"><p>".$name.": <br><input type=\"".$type."\" 
            id=\"textinput\" name=\"".$namepost."\" value=\"".$value."\" class=\"medium\"></div>";
           } else {
             $form.="<div class=\"field\"><input type=\"".$type."\" 
            id=\"textinput\" name=\"".$namepost."\" value=\"".$value."\" class=\"medium\"></div>";
           } 
          }
          }
          return $form;
        }
        public function TypeTable($elements) {
            
            $tb="<thead><tr>";
            foreach($elements as $var) {
              $tb.="<th>".$var."</th>";
            }
            $tb.="</tr></thead>";
            return $tb;
        }
        public function ElementTable($elements) {
            $tb="<tr class=\"even\">";
            foreach($elements as $var) {
              $tb.="<td>".$var."</td>";
            }
            $tb.="</tr>";
            return $tb;
        }
} 

?>
