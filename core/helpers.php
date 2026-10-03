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

/**
 * Génère le champ caché contenant le token CSRF pour les formulaires.
 *
 * @return string Le code HTML du champ caché.
 */
function csrf_field(): string {
    $token = $_SESSION['csrf_token'] ?? '';
    return '<input type="hidden" name="csrf_token" value="' . h($token) . '">';
}

/**
 * Vérifie si le token CSRF soumis correspond à celui en session.
 *
 * @return bool True si valide, False sinon.
 */
function verify_csrf(): bool {
    $sessionToken = $_SESSION['csrf_token'] ?? '';
    $postToken = $_POST['csrf_token'] ?? '';

    if (empty($sessionToken) || empty($postToken) || !is_string($sessionToken) || !is_string($postToken)) {
        return false;
    }

    return hash_equals($sessionToken, $postToken);
}
