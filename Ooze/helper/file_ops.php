<?php
function texto ($local) {
 $file_lines = file("files/$local");
 foreach($file_lines as $atual ) { $escrito.=$atual; }
 return $escrito;
}

function apagar($dir) {
 if(is_dir($dir)) {
  if($handle = opendir($dir)) {
  while(false !== ($file = readdir($handle))) {
  if(($file == ".") or ($file == "..")) {
   continue;
  }
  if(is_dir($dir . $file)) {
   apagar($dir . $file . "/");
  } else {
   unlink($dir . $file);
  }
  }
  } else {
  print("you dont can open this file.");
  return false;
 }

// fecha a pasta aberta
 closedir($handle);

// apaga a pasta, que agora esta vazia
 rmdir($dir);
 } else {
 print("invalid directory");
 return false;
 }
}

?>
