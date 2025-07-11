<?php

namespace oihana\date\traits;

use DateInvalidTimeZoneException;
use DateMalformedStringException;
use DateTime;
use DateTimeZone;

use function oihana\core\date\isDate;

/**
 * The command to manage an ArangoDB database.
 */
trait DateTrait
{
    /**
     * The default date format of the dump files.
     */
    public const string DEFAULT_DATE_FORMAT = 'Y-m-d\TH:i:s' ;

    /**
     * The default 'now' constant to defines the current date.
     */
    public const string NOW = 'now' ;

    /**
     * The date format of the dates.
     * @var string
     */
    public string $dateFormat = 'Y-m-d\TH:i:s' ;

    /**
     * The timezone of the date to backup the database.
     * @var ?string
     */
    public ?string $timezone = 'Europe/Paris' ;

    /**
     * Returns a timestamped string
     * @param string|null $date
     * @param string|null $timezone
     * @param string|null $format
     * @return string|null
     * @throws DateInvalidTimeZoneException
     * @throws DateMalformedStringException
     */
    public function getTimestampedString( ?string $date = null , ?string $timezone = null , ?string $format = null ): ?string
    {
        $format    = $format ?? $this->dateFormat ;
        $date      = isDate( $date , $format ) ? $date : self::NOW ;
        $timezone  = new DateTimeZone( $timezone ?? $this->timezone ) ;
        $dateTime  = new DateTime( $date , $timezone ) ;
        return $dateTime->format( $format ) ;
    }
}