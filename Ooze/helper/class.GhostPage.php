<?php
// Ghost template by Cooler_
// coolerlair@gmail.com
// Mais rapido que o smarty ;)
class GhostPage {
	/**
	 * Vai guardar as variáveis criadas on-the-fly
	 * @var 	array	$properties
	 */
	public $properties = array();
	
   public function display_page() {
      $vararray = explode(",",trim($this->varnamelist));
      $templatearray = file($this->templatefile);
      $template = join("",$templatearray);
      foreach ($vararray as $varname) {
         $template = str_replace("<!--$varname-->",$this->$varname,$template);
      }
      print $template;
   }
   
   /**
    * Esse método nunca deve ser chamado diretamente
	* ele é um método mágico e é trabalho do php chamá-lo.
	* 
	* Quando você for ajustar uma propriedade da classe, por exemplo:
	* $page->titulo = "Meu Portal de Videos";
	* mas não existe a propriedade pública $titulo na classe, o PHP
	* automaticamente irá chamar essa função e passará os valores da seguinte forma:
	* __set("titulo", "Meu Portal de Videos");
	* isso irá ser colocado no hashtable interno $properties.
	*
	*/
   public function __set($var, $value)
   {
		$this->properties[$var] = $value;
   }
   
   /**
    * Esse método será chamado sempre que você chamar uma
	* propriedade inexistente na classe, como última alternativa,
	* antes de lançar o warning, o PHP irá chamar esse método com
	* o nome da propriedade não encontrada.
	* Ali dentro irá retornar o valor da propriedade já ajustada com __set
	*/
   public function __get($var)
   {    
		return $this->properties[$var];
   }
}
?>

