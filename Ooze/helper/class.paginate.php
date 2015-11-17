<?php
// Class Pager by Cooler_ 
//======================================
// -Example to call this Class
//$paginacao=new paginate(); 
//$page->conteudo=$paginacao->pag($Array,$MaxItems,$File_base);

class paginate {

 public function pag($NossoArray,$RegistrosPorPagina,$file, $nameget){ 
  $lista=" ";
   if (!isset($_GET["$nameget"])) { $PaginaAtual = 1; } else { $PaginaAtual = htmlentities($_GET["$nameget"]); }
  $TotalDeRegistros = (count($NossoArray) - 1);
  $TotalDePaginas = ceil($TotalDeRegistros/$RegistrosPorPagina);
  $PrimeiroRegistro = (($PaginaAtual * $RegistrosPorPagina) - $RegistrosPorPagina);
  for ($i = $PrimeiroRegistro; $i < ($RegistrosPorPagina + $PrimeiroRegistro); $i++) {
   if(isset($NossoArray[$i])) { $lista.=$NossoArray[$i]; }
  }
   $currentnumber=0;
  if(isset($_GET["$nameget"])) {
   $currentnumber=htmlentities($_GET["$nameget"]);
  } 
  $lista.=$this->CriarLinks($TotalDePaginas,$file,$nameget,$currentnumber);
  return $lista;
 }

 public function CriarLinks ($TotalDePaginas,$file,$num,$posicao) {
  $nameget=$num;
  $link="<div align=\"center\" class=\"pg\"><p>Pag ";
  for ($i = 1; $i <= $TotalDePaginas; $i++) {
    if($i == $posicao) { 
     $link .= "<b><a href=".$file."&".$nameget."=".$i." class=\"current\" >".$i."</a></b>  "; 
    } else {
     $link .= "<a href=".$file."&".$nameget."=".$i.">".$i."</a>  "; 
    }
  }
  $link.="</div></p>";
  return $link;
 }

}  
