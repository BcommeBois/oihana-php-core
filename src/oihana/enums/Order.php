<?php

namespace oihana\enums;

use oihana\reflections\traits\ConstantsTrait;

class Order
{
    use ConstantsTrait ;
    /**
     * The ascending order.
     */
    public const string ASC  = 'ASC' ;

    /**
     * The descending order.
     */
    public const string DESC = 'DESC' ;
}
