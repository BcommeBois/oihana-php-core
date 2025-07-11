<?php

namespace oihana\logging;

use oihana\reflections\traits\ConstantsTrait;

class MonoLogConfig
{
    use ConstantsTrait ;

    public const string BUBBLES              = 'bubbles' ;
    public const string DATE_FORMAT          = 'dateFormat' ;
    public const string DIR_PERMISSIONS      = 'dirPermissions' ;
    public const string FILE_PERMISSIONS     = 'filePermissions' ;
    public const string INCLUDE_STACK_TRACES = 'includeStackTraces' ;
    public const string LEVEL                = 'level' ;
    public const string MAX_FILES            = 'maxFiles' ;
    public const string PATTERN              = 'pattern' ;
}