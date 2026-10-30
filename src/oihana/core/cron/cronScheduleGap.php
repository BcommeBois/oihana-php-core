<?php

namespace oihana\core\cron ;

/**
 * Measures the longest time a cron expression can leave between two runs.
 *
 * The number a "this job has not run for too long" check needs, and deliberately the **longest**
 * gap rather than the average or the nominal period. `30 2 * * 1-5` runs five times a week, which
 * averages to 33 hours; what matters is that it leaves **72 hours** across the weekend, because a
 * shorter threshold would alert every Sunday on a job behaving exactly as configured — and an
 * alert that cries on a working setup is an alert people turn off.
 *
 * Computed by laying out one cycle rather than by recognising presets: every run in a day, a week
 * or a month — depending on which fields are restricted — and the widest interval between two
 * consecutive runs, wrapping around the end of the cycle. A restriction on the day of the month is
 * read on a 31-day cycle: the pessimistic reading, since a job on the 31st skips the months that
 * have none.
 *
 * **Returns `null` for a schedule it will not guess at**:
 *
 * - a field {@see cronFieldValues()} cannot read, or not exactly five fields;
 * - a month restriction — a yearly schedule, whose gap depends on the year;
 * - a restricted day of the month **and** a restricted day of the week: cron then runs when
 *   **either** matches, which is not a cycle this measures.
 *
 * @param string $expression The five standard fields: minute, hour, day of month, month, day of week.
 *
 * @return int|null The longest interval between two runs, in seconds; null when it is not known.
 *
 * @example
 * ```php
 * use function oihana\core\cron\cronScheduleGap;
 *
 * cronScheduleGap( '17 2 * * *' ) ;   // 86400  — daily
 * cronScheduleGap( '30 2 * * 1-5' ) ; // 259200 — 72 hours, across the weekend
 * cronScheduleGap( '0 0 1 1 *' ) ;    // null   — yearly
 * ```
 *
 * @package oihana\core\cron
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.4.0
 */
function cronScheduleGap( string $expression ) :?int
{
    $fields = preg_split( '/\s+/' , trim( $expression ) ) ?: [] ;

    if ( count( $fields ) !== 5 )
    {
        return null ;
    }

    [ $rawMinute , $rawHour , $rawDayOfMonth , $rawMonth , $rawDayOfWeek ] = $fields ;

    $minutes  = cronFieldValues( $rawMinute     , 0 , 59 ) ;
    $hours    = cronFieldValues( $rawHour       , 0 , 23 ) ;
    $days     = cronFieldValues( $rawDayOfMonth , 1 , 31 ) ;
    $months   = cronFieldValues( $rawMonth      , 1 , 12 ) ;
    $weekdays = cronFieldValues( $rawDayOfWeek  , 0 , 7  ) ;

    if ( $minutes === null || $hours === null || $days === null || $months === null || $weekdays === null )
    {
        return null ;
    }

    // Sunday is both 0 and 7, so a field naming either is still one day.

    $weekdays = array_values( array_unique( array_map( static fn( int $day ) :int => $day === 7 ? 0 : $day , $weekdays ) ) ) ;

    sort( $weekdays ) ;

    $everyDayOfMonth = count( $days )     === 31 ;
    $everyWeekday    = count( $weekdays ) === 7 ;
    $everyMonth      = count( $months )   === 12 ;

    if ( !$everyMonth )
    {
        return null ;
    }

    if ( !$everyDayOfMonth && !$everyWeekday )
    {
        return null ;
    }

    if ( !$everyWeekday )
    {
        $cycleDays = 7 ;
        $runDays   = $weekdays ;
    }
    elseif ( !$everyDayOfMonth )
    {
        $cycleDays = 31 ;
        $runDays   = array_map( static fn( int $day ) :int => $day - 1 , $days ) ;
    }
    else
    {
        $cycleDays = 1 ;
        $runDays   = [ 0 ] ;
    }

    // Every run of the cycle, in minutes from its start.

    $instants = [] ;

    foreach ( $runDays as $day )
    {
        foreach ( $hours as $hour )
        {
            foreach ( $minutes as $minute )
            {
                $instants[] = ( $day * 1440 ) + ( $hour * 60 ) + $minute ;
            }
        }
    }

    sort( $instants ) ;

    $cycle   = $cycleDays * 1440 ;
    $largest = 0 ;
    $count   = count( $instants ) ;

    for ( $i = 0 ; $i < $count ; $i++ )
    {
        $gap = $i + 1 < $count
             ? $instants[ $i + 1 ] - $instants[ $i ]
             : $cycle - $instants[ $i ] + $instants[ 0 ] ;

        if ( $gap > $largest )
        {
            $largest = $gap ;
        }
    }

    return $largest * 60 ;
}
