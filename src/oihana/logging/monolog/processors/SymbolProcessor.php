<?php

namespace oihana\logging\monolog\processors;

use Monolog\Level;
use Monolog\LogRecord;

class SymbolProcessor
{
    private array $symbolMap =
    [
        Level::Debug->value     => '→' , // 100
        Level::Info->value      => 'i' , // 200
        Level::Notice->value    => '※' , // 250
        Level::Warning->value   => '▲' , // 300
        Level::Error->value     => '✘' , // 400
        Level::Critical->value  => '⚡' , // 500
        Level::Alert->value     => '‼' , // 550
        Level::Emergency->value => '☢' , // 600
    ];

    /**
     * Invoke the processor.
     * @param LogRecord $record The log record reference.
     * @return LogRecord
     */
    public function __invoke( LogRecord $record ): LogRecord
    {
        $logLevelValue = $record['level'] ?? Level::Debug->value;

        if( isset( $this->symbolMap[ $logLevelValue ] ) )
        {
            $emoji = $this->symbolMap[ $logLevelValue ] ;
        }
        else
        {
            $emoji = $record['level_name'] ?? '' ;
        }

        $record['extra']['level_emoji'] = $emoji;

        return $record;
    }
}