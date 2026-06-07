<?php

namespace tests\oihana\core\documents\mocks;

/**
 * A named class whose constructor requires an argument, so `new $class()`
 * (called with no argument) throws — used to exercise the constructor-failure
 * fallback in formatDocument()/formatDocumentWith().
 */
class MockRequiresConstructor
{
    public string $label;

    public function __construct( string $required )
    {
        $this->label = $required;
    }
}
