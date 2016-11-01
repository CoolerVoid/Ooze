<?php

function sanitize($input) {
 	if (preg_match('/select|union|delete|insert|version|drop|update|where|[0-9]=[0-9]|%|\/\*|\*\//',strtolower($input))) {
		echo "don't try SQLi here! ;-) \n";
		exit(0);
		
	}

	$output=filter_var($input, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
	return $output;
}

function sanitizecmd($input) {
 	if (preg_match('/select|union|delete|insert|version|drop|update|where|[0-9]=[0-9]|%|\/\*|\*\//',strtolower($input))) {
		echo "don't try SQLi here! ;-) \n";
		exit(0);	
	}

	return $input;
}

?>
