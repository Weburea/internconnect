<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Code 128 (set B) barcodes for receipts. Scannable by any barcode reader.
 * pattern() returns module widths, bar first (bar, space, bar, ...), so the same
 * data draws the SVG on the page and the bars in the jsPDF receipt.
 */
final class Barcode
{
    /** Code 128 symbol widths, values 0..106 (106 = stop, 7 elements). */
    private const PATTERNS = [
        '212222', '222122', '222221', '121223', '121322', '131222', '122213', '122312', '132212', '221213',
        '221312', '231212', '112232', '122132', '122231', '113222', '123122', '123221', '223211', '221132',
        '221231', '213212', '223112', '312131', '311222', '321122', '321221', '312212', '322112', '322211',
        '212123', '212321', '232121', '111323', '131123', '131321', '112313', '132113', '132311', '211313',
        '231113', '231311', '112133', '112331', '132131', '113123', '113321', '133121', '313121', '211331',
        '231131', '213113', '213311', '213131', '311123', '311321', '331121', '312113', '312311', '332111',
        '314111', '221411', '431111', '111224', '111422', '121124', '121421', '141122', '141221', '112214',
        '112412', '122114', '122411', '142112', '142211', '241211', '221114', '413111', '241112', '134111',
        '111242', '121142', '121241', '114212', '124112', '124211', '411212', '421112', '421211', '212141',
        '214121', '412121', '111143', '111341', '131141', '114113', '114311', '411113', '411311', '113141',
        '114131', '311141', '411131', '211412', '211214', '211232', '2331112',
    ];
    private const START_B = 104;
    private const STOP = 106;

    /** @return int[] module widths, starting with a bar */
    public static function pattern(string $text): array
    {
        // Set B covers printable ASCII 32..126; anything else becomes "?".
        $text = preg_replace('/[^\x20-\x7E]/', '?', $text) ?? '';
        $codes = [self::START_B];
        $sum = self::START_B;
        foreach (str_split($text) as $i => $ch) {
            $v = ord($ch) - 32;
            $codes[] = $v;
            $sum += $v * ($i + 1);
        }
        $codes[] = $sum % 103;
        $codes[] = self::STOP;
        $out = [];
        foreach ($codes as $c) {
            foreach (str_split(self::PATTERNS[$c]) as $w) {
                $out[] = (int)$w;
            }
        }
        return $out;
    }

    /** Inline SVG (scales to its container width; crisp at any size). */
    public static function svg(string $text, int $height = 56, string $color = '#000623'): string
    {
        $x = 10; // quiet zone
        $rects = '';
        foreach (self::pattern($text) as $i => $w) {
            if ($i % 2 === 0) {
                $rects .= '<rect x="' . $x . '" y="0" width="' . $w . '" height="' . $height . '"/>';
            }
            $x += $w;
        }
        $total = $x + 10;
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' . $total . ' ' . $height . '" width="100%" height="' . $height . '" preserveAspectRatio="none" role="img" aria-label="Barcode ' . htmlspecialchars($text, ENT_QUOTES) . '" fill="' . $color . '" shape-rendering="crispEdges">' . $rects . '</svg>';
    }
}
