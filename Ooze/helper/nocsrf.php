<?php

class NoCSRF
{

    protected static $doOriginCheck = false;


    public static function check( $key, $origin, $throwException=false, $timespan=null, $multiple=false )
    {
        if ( !isset( $_SESSION[ 'csrf_' . $key ] ) )
            if($throwException)
                throw new Exception( 'Missing CSRF session token.' );
            else
                return false;
            
        if ( !isset( $origin[ $key ] ) )
            if($throwException)
                throw new Exception( 'Missing CSRF form token.' );
            else
                return false;


        $hash = $_SESSION[ 'csrf_' . $key ];
		

		if(!$multiple)
			$_SESSION[ 'csrf_' . $key ] = null;


        if( self::$doOriginCheck && hash_hmac('sha256', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] ) != substr( base64_decode( $hash ), 10, 40 ) )
        {
            if($throwException)
                throw new Exception( 'Form origin does not match token origin.' );
            else
                return false;
        }
        

        if ( $origin[ $key ] != $hash )
            if($throwException)
                throw new Exception( 'Invalid CSRF token.' );
            else
                return false;

        // Check for token expiration
        if ( $timespan != null && is_int( $timespan ) && intval( substr( base64_decode( $hash ), 0, 10 ) ) + $timespan < time() )
            if($throwException)
                throw new Exception( 'CSRF token has expired.' );
            else
                return false;

        return true;
    }


    public static function enableOriginCheck()
    {
        self::$doOriginCheck = true;
    }


    public static function generate( $key )
    {
        $extra = self::$doOriginCheck ? hash_hmac('sha256', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] ) : '';
        // token generation (basically base64_encode any random complex string, time() is used for token expiration) 
        $token = base64_encode( time() . $extra . self::randomString( 32 ) );
        // store the one-time token in session
        $_SESSION[ 'csrf_' . $key ] = $token;

        return $token;
    }


    protected static function randomString( $length )
    {
        $seed = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijqlmnopqrtsuvwxyz0123456789';
        $max = strlen( $seed ) - 1;

        $string = '';
        for ( $i = 0; $i < $length; ++$i )
            $string .= $seed{intval( mt_rand( 0.0, $max ) )};

        return $string;
    }

}
?>

