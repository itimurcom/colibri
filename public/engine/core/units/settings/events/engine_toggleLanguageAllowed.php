<?php
function toggleLanguageAllowed( $langCode)
{
    
    $langFile = './engine/ini/ini.languages.php';

    if (!file_exists($langFile)) {
        throw new Exception("Language file not found.");
    }

    // Зчитуємо вміст файлу
    $fileContents = file_get_contents($langFile);

    // Регулярний вираз для пошуку allowed по ключу мови
    $pattern = "/(\\\$lang_cat\['" . preg_quote($langCode, '/') . "'\][^;]*'allowed'\s*=>\s*)([01])/m";

    if (preg_match($pattern, $fileContents, $matches)) {
        $current = (int)$matches[2];
        $new = $current === 1 ? 0 : 1;

        // Заміна значення allowed
        $newContents = preg_replace($pattern, '${1}' . $new, $fileContents, 1);

        // Запис назад у файл
        if (file_put_contents($langFile, $newContents) === false) {
            throw new Exception("Failed to write to language file.");
        }

        return $new;
    }

    // Якщо ключ не знайдений
    throw new Exception("Language key '{$langCode}' not found or 'allowed' parameter missing.");
}
?>