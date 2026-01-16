<?php

namespace oihana\core\strings ;

use InvalidArgumentException;
use Normalizer;

/**
 * Sanitize a string based on configurable flags.
 *
 * This function acts as a comprehensive filter chain for string data. It can perform
 * operations ranging from simple trimming to complex HTML stripping, Unicode normalization,
 * and invisible character removal.
 *
 * ### Available flags (SanitizeFlag)
 * **Cleaning & Security:**
 * - `SanitizeFlag::STRIP_TAGS`           : Remove HTML/PHP tags (and content of <script>/<style>).
 * - `SanitizeFlag::DECODE_ENTITIES`      : Decode HTML entities (e.g., `&amp;` -> `&`).
 * - `SanitizeFlag::REMOVE_CONTROL_CHARS` : Remove non-printable ASCII characters (0-31, 127) except line breaks/tabs.
 * - `SanitizeFlag::REMOVE_INVISIBLE`     : Remove invisible Unicode characters (zero-width, BOM, etc.) and normalize non-breaking spaces.
 * **Formatting & Normalization:**
 * - `SanitizeFlag::NORMALIZE_UNICODE`    : Normalize string to Unicode Normalization Form C (NFC) by default.
 * - `SanitizeFlag::NORMALIZE_LINE_BREAKS`: Convert Windows (`\r\n`) and Mac (`\r`) line endings to Unix (`\n`).
 * - `SanitizeFlag::REMOVE_EXTRA_LINE_BREAKS`: Collapse multiple consecutive line breaks into a single one.
 * - `SanitizeFlag::COLLAPSE_SPACES`      : Collapse multiple consecutive horizontal spaces into a single space.
 * - `SanitizeFlag::TRIM`                 : Remove whitespace from the start and end of the string.
 * **Output Control:**
 * - `SanitizeFlag::NULLIFY`              : Return `null` if the resulting string is empty.
 *
 * ### Processing order
 *
 * Operations are applied in this specific order to ensure data integrity:
 * 1.  **DECODE_ENTITIES**: Decode HTML entities first (to expose hidden tags or chars).
 * 2.  **STRIP_TAGS**: Remove scripts/styles content, then strip tags.
 * 3.  **REMOVE_CONTROL_CHARS**: Clean basic ASCII control noise.
 * 4.  **REMOVE_INVISIBLE**: aggressive cleaning of Unicode invisible chars.
 * 5.  **NORMALIZE_UNICODE**: Standardize Unicode composition.
 * 6.  **NORMALIZE_LINE_BREAKS**: Standardize line endings to `\n`.
 * 7.  **REMOVE_EXTRA_LINE_BREAKS**: Collapse vertical spacing.
 * 8.  **COLLAPSE_SPACES**: Collapse horizontal spacing.
 * 9.  **TRIM**: Clean edges.
 * 10. **NULLIFY**: Final check for emptiness.
 *
 * @param string|null $source  The string to sanitize. Can be null.
 * @param int         $flags   A bitmask of SanitizeFlag constants. Defaults to `SanitizeFlag::DEFAULT`.
 * @param array       $options Optional parameters for specific flags:
 *                             - `allowed_tags` (string|string[]): Used with `STRIP_TAGS`. allowable tags (e.g. '<p><a>').
 *                             - `unicode_form` (int): Used with `NORMALIZE_UNICODE`. Defaults to `Normalizer::NFC`.
 *
 * @return string|null The sanitized string, or `null` if `NULLIFY` is enabled and the result is empty.
 *
 * @throws InvalidArgumentException If the flags parameter contains invalid flag values.
 *
 * @example
 * ```php
 * use oihana\core\strings\{sanitize, SanitizeFlag};
 *
 * // 1. Basic usage (TRIM | REMOVE_INVISIBLE by default)
 * sanitize("  Hello\u{200B}World  "); // "HelloWorld"
 *
 * // 2. HTML Cleaning
 * $html = "<script>alert('xss')</script><p>Hello &amp; <b>World</b></p>";
 * $flags = SanitizeFlag::STRIP_TAGS | SanitizeFlag::DECODE_ENTITIES | SanitizeFlag::TRIM;
 * sanitize($html, $flags); // "Hello & World"
 *
 * // 3. Keeping specific tags
 * sanitize($html, $flags, ['allowed_tags' => '<b>']); // "Hello & <b>World</b>"
 *
 * // 4. Text Formatting
 * $text = "Line 1  \t  Content\n\n\nLine 2";
 * $flags = SanitizeFlag::COLLAPSE_SPACES | SanitizeFlag::REMOVE_EXTRA_LINE_BREAKS | SanitizeFlag::TRIM;
 * sanitize($text, $flags); // "Line 1 Content\nLine 2"
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz
 * @since   1.0.8
 */
function sanitize
(
    ?string $source ,
    int     $flags   = SanitizeFlag::DEFAULT ,
    array   $options = []
)
:?string
{
    if ( !SanitizeFlag::isValid( $flags ) )
    {
        throw new InvalidArgumentException( sprintf
        (
            'Invalid flags: %d. Valid flags are: %s',
            $flags,
            SanitizeFlag::describe( SanitizeFlag::ALL )
        )) ;
    }

    // Convert null to empty string for processing
    if ( $source === null )
    {
        $source = '' ;
    }

    // Early return for empty string
    if ( $source === '' )
    {
        return SanitizeFlag::has( $flags , SanitizeFlag::NULLIFY ) ? null : '' ;
    }

    if ( $flags === SanitizeFlag::NONE )
    {
        return $source ;
    }

    // Step 1: Decode HTML entities (before removing tags)
    if ( SanitizeFlag::has( $flags, SanitizeFlag::DECODE_ENTITIES ) )
    {
        $source = html_entity_decode( $source, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
    }

    // Step 2: Strip HTML/PHP tags
    if ( SanitizeFlag::has($flags, SanitizeFlag::STRIP_TAGS ) )
    {
        $source      = preg_replace('#<(script|style).*?>.*?</\1>#si', '', $source);
        $allowedTags = $options['allowed_tags'] ?? null;
        $source      = strip_tags($source, $allowedTags);
    }

    // Step 3: Remove control characters (less aggressive than invisible)
    if ( SanitizeFlag::has( $flags, SanitizeFlag::REMOVE_CONTROL_CHARS ) )
    {
        // Keep \t, \n, \r but remove other control chars
        $source = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/', '', $source);
    }

    // Step 4: Remove invisible characters (more aggressive)
    // IMPORTANT: Convert non-breaking spaces to regular spaces before removal
    if ( SanitizeFlag::has( $flags, SanitizeFlag::REMOVE_INVISIBLE ) )
    {
        $source = str_replace("\u{00A0}", ' ', $source) ;
        $clean  = preg_replace
        (
            '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F\x{200B}-\x{200F}\x{202A}-\x{202E}\x{2060}-\x{2064}\x{FEFF}]+/u',
            '',
            $source
        );
        if ( $clean !== null )
        {
            $source = $clean ;
        }
    }

    // Step 5: Normalize Unicode
    if ( SanitizeFlag::has( $flags, SanitizeFlag::NORMALIZE_UNICODE ) )
    {
        $form = $options['unicode_form'] ?? Normalizer::NFC ;
        if ( class_exists('\Normalizer') )
        {
            $normalized = Normalizer::normalize( $source, $form );
            if ( $normalized !== false )
            {
                $source = $normalized;
            }
        }
    }

    // Step 6: Normalize line breaks
    if ( SanitizeFlag::has( $flags , SanitizeFlag::NORMALIZE_LINE_BREAKS ) )
    {
        $source = str_replace( ["\r\n", "\r"] , "\n" , $source ) ;
    }

    // Step 7: Remove extra line breaks
    if ( SanitizeFlag::has( $flags , SanitizeFlag::REMOVE_EXTRA_LINE_BREAKS ) )
    {
        $source = preg_replace("/\n{2,}/" , "\n" , $source ) ;
    }

    // Step 8: Collapse multiple spaces
    if ( SanitizeFlag::has( $flags, SanitizeFlag::COLLAPSE_SPACES ) )
    {
        // Replace multiple spaces/tabs with single space (preserving line breaks)
        $source = preg_replace('/[^\S\n]+/', ' ', $source) ;
    }

    // Step 9: Trim whitespace (after other operations)
    if ( SanitizeFlag::has( $flags , SanitizeFlag::TRIM ) )
    {
        $source = trim( $source ) ;
        // If TRIM is enabled, also remove indentation (spaces/tabs) immediately following a line break.
        // \h matches horizontal whitespace (spaces, tabs).
        $source = preg_replace('/(\r\n|\n|\r)\h+/' , '$1' , $source ) ;
    }

    // Step 10: Apply NULLIFY flag if result is empty
    if ( $source === '' )
    {
        return SanitizeFlag::has( $flags , SanitizeFlag::NULLIFY ) ? null : '' ;
    }

    return $source ;
}