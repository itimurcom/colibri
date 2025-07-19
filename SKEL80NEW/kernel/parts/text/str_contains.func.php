<?
    function str_contains(?string $haystack, string $needle): bool {
        return $needle === '' || strpos((string)$haystack, $needle) !== false;
    }
?>