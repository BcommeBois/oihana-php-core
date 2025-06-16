<?php

namespace oihana\core\strings ;

use oihana\enums\Char;

/**
 * Indicates if the passed-in string is a valid luhn code.
 * @param array $params The array of parameters
 * @param bool $useNow Indicates if the from property must be override with the value "now".
 * @return string
 */
function formatRequestArgs( array $params, bool $useNow = false ): string
{
    $str = Char::EMPTY ;

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
                $str  .= Char::QUESTION_MARK ;
                $first = false;
            }
            else
            {
                $str .= Char::AMPERSAND ;
            }

            $str .= $key . Char::EQUAL . $value ;
        }
    }

    return $str;
}