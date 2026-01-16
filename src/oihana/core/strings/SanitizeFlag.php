<?php

namespace oihana\core\strings ;

use oihana\core\bits\BitFlagTrait;

/**
 * Enumeration representing string sanitization modes as bit flags.
 *
 * Each flag can be combined using the bitwise OR operator (`|`) to form a mask.
 * The `has()` helper can be used to check if a particular flag is present in a mask.
 *
 * @example
 * ```php
 * use oihana\core\strings\SanitizeFlag;
 *
 * $mask = SanitizeFlag::TRIM | SanitizeFlag::NULLIFY;
 * SanitizeFlag::has($mask, SanitizeFlag::TRIM); // true
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz
 * @since   1.0.8
 */
class SanitizeFlag
{
    use BitFlagTrait ;

    /**
     * Do nothing.
     * @var int
     */
    public const int NONE = 0 ;

    /**
     * Trim spaces, tabs, and line breaks at the start and end
     */
    public const int TRIM = 1 << 0 ;

    /**
     * Convert empty string to null
     */
    public const int NULLIFY = 1 << 1 ;

    /**
     * Normalize line breaks (\r\n, \r → \n)
     */
    public const int NORMALIZE_LINE_BREAKS = 1 << 2 ;

    /**
     * Collapse multiple consecutive line breaks into one.
     */
    public const int REMOVE_EXTRA_LINE_BREAKS = 1 << 3 ;

    /**
     * Remove invisible Unicode characters (zero-width, non-breaking, etc.)
     */
    public const int REMOVE_INVISIBLE = 1 << 4 ;

    /**
     * Collapse multiple spaces into one
     */
    public const int COLLAPSE_SPACES = 1 << 5 ;

    /**
     * Remove HTML/PHP tags
     */
    public const int STRIP_TAGS = 1 << 6 ;

    /**
     * Decode HTML entities to their corresponding characters.
     * Example: &amp; → &, &lt; → <, &quot; → "
     */
    public const int DECODE_ENTITIES = 1 << 7 ;

    /**
     * Remove all non-printable ASCII characters (0x00-0x1F, 0x7F).
     * Less aggressive than REMOVE_INVISIBLE, keeps Unicode characters.
     */
    public const int REMOVE_CONTROL_CHARS = 1 << 8 ;

    /**
     * Normalize Unicode characters (NFD, NFC, NFKD, NFKC).
     * Uses 'NFC' normalization by default (canonical decomposition + composition).
     */
    public const int NORMALIZE_UNICODE = 1 << 9 ;

    /**
     * All flags combined.
     */
    public const int ALL = self::TRIM
    | self::NULLIFY
    | self::NORMALIZE_LINE_BREAKS
    | self::REMOVE_EXTRA_LINE_BREAKS
    | self::REMOVE_INVISIBLE
    | self::COLLAPSE_SPACES
    | self::STRIP_TAGS
    | self::DECODE_ENTITIES
    | self::REMOVE_CONTROL_CHARS
    | self::NORMALIZE_UNICODE;

    /**
     * Default flags
     */
    public const int DEFAULT = self::TRIM | self::REMOVE_INVISIBLE;

    /**
     * Normalize flags (for consistent text processing)
     */
    public const int NORMALIZE = self::TRIM
    | self::REMOVE_INVISIBLE
    | self::NORMALIZE_LINE_BREAKS
    | self::NORMALIZE_UNICODE ;

    /**
     * Clean HTML flags (for user-submitted HTML content)
     */
    public const int CLEAN_HTML = self::TRIM
    | self::STRIP_TAGS
    | self::DECODE_ENTITIES
    | self::COLLAPSE_SPACES ;

    /**
     * Strict flags (aggressive cleaning)
     */
    public const int STRICT = self::TRIM
    | self::NULLIFY
    | self::REMOVE_INVISIBLE
    | self::REMOVE_CONTROL_CHARS
    | self::NORMALIZE_LINE_BREAKS
    | self::REMOVE_EXTRA_LINE_BREAKS
    | self::COLLAPSE_SPACES ;

    /**
     * List of all flags
     */
    public const array FLAGS =
    [
        self::TRIM,
        self::NULLIFY,
        self::NORMALIZE_LINE_BREAKS,
        self::REMOVE_EXTRA_LINE_BREAKS,
        self::REMOVE_INVISIBLE,
        self::COLLAPSE_SPACES,
        self::STRIP_TAGS,
        self::DECODE_ENTITIES,
        self::REMOVE_CONTROL_CHARS,
        self::NORMALIZE_UNICODE,
    ];

    /**
     * Names of flags
     */
    public const array FLAGS_NAME = [
        self::TRIM                     => 'TRIM',
        self::NULLIFY                  => 'NULLIFY',
        self::NORMALIZE_LINE_BREAKS    => 'NORMALIZE_LINE_BREAKS',
        self::REMOVE_EXTRA_LINE_BREAKS => 'REMOVE_EXTRA_LINE_BREAKS',
        self::REMOVE_INVISIBLE         => 'REMOVE_INVISIBLE',
        self::COLLAPSE_SPACES          => 'COLLAPSE_SPACES',
        self::STRIP_TAGS               => 'STRIP_TAGS',
        self::DECODE_ENTITIES          => 'DECODE_ENTITIES',
        self::REMOVE_CONTROL_CHARS     => 'REMOVE_CONTROL_CHARS',
        self::NORMALIZE_UNICODE        => 'NORMALIZE_UNICODE',
    ];
}