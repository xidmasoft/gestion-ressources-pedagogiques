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

    if (empty($sessionToken) || empty($postToken)) {
        return false;
    }

    return hash_equals($sessionToken, $postToken);
}

/**
 * Définit un message flash.
 *
 * @param string $type Le type de message (ex: 'success', 'error').
 * @param string $message Le message à afficher.
 */
function setFlash(string $type, string $message): void {
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }
    $_SESSION['flash'][$type][] = $message;
}

/**
 * Récupère et supprime les messages flash d'un certain type.
 *
 * @param string $type Le type de message.
 * @return array Liste des messages flash du type demandé.
 */
function getFlash(string $type): array {
    if (isset($_SESSION['flash'][$type])) {
        $messages = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $messages;
    }
    return [];
}
