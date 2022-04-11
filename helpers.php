<?php

if (!function_exists('str_ordinal')) {
    /**
     * Append an ordinal indicator to a numeric value.
     *
     * @param  string|int $value
     * @param  bool $superscript
     * @return string
     */
    function str_ordinal($value, $superscript = false)
    {
        $number = abs($value);

        if (class_exists('NumberFormatter')) {
            $nf = new \NumberFormatter('en_US', \NumberFormatter::ORDINAL);
            $ordinalized = $superscript ?
                number_format($number) .
                '<sup>' .
                substr($nf->format($number), -2) .
                '</sup>' :
                $nf->format($number);

            return $ordinalized;
        }

        $indicators = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];

        $suffix = $superscript ? '<sup>' . $indicators[$number % 10] . '</sup>' : $indicators[$number % 10];
        if ($number % 100 >= 11 && $number % 100 <= 13) {
            $suffix = $superscript ? '<sup>th</sup>' : 'th';
        }

        return number_format($number) . $suffix;
    }
}

if (!function_exists('tmp_path')) {
    function tmp_path($path = '')
    {
        return storage_path('app/tmp' . ($path ? DIRECTORY_SEPARATOR.$path : $path));
    }
}
