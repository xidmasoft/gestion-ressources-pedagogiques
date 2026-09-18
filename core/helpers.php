<?php
// core/helpers.php

/**
 * Échappe les caractères spéciaux en entités HTML pour prévenir les failles XSS.
 *
 * @param string|null $string La chaîne à échapper.
 * @return string La chaîne échappée.
 */
function h(?string $string): string {
    if ($string === null) {
        return '';
    }
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}
