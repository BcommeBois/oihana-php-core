<?php

namespace oihana\core\strings ;

/**
 * Indicates if the passed-in string is a valid luhn code.
 * @param array $params The array of parameters
 * @param bool $useNow Indicates if the from property must be override with the value "now".
 * @return string
 */
function formatRequestArgs( array $params, bool $useNow = false ): string
{
    $str = "" ;

    if( count( $params ) > 0 )
    {
        $first = true;

        foreach( $params as $key => $value )
        {
            if( ( $key == "from" ) && $useNow )
            {
                $value = "now" ;
            }

            if( $first )
            {
                $str  .= "?" ;
                $first = false;
            }
            else
            {
                $str .= "&" ;
            }

            $str .= $key . "=" . $value ;
        }
    }

    return $str;
}