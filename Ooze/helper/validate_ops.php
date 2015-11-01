<?php

function test_csrf () {
      try
      {
        NoCSRF::check( 'csrf_token', $_POST, true, 60*10, false );
      }
      catch ( Exception $e )
      {
        $result = $e->getMessage() . ' Form ignored.';
	echo $result;
	exit;
      }
}


?>
