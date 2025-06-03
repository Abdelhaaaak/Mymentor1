<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Exception dédiée pour les erreurs de suggestion de domaines (service Cohere, etc.).
 */
class DomainSuggestionException extends RuntimeException
{
    /**
     * Constructeur personnalisé.
     *
     * @param string         $message  Le message d’erreur (optionnel).
     * @param int            $code     Le code d’erreur (optionnel).
     * @param \Throwable|null $previous Exception précédente (optionnel).
     */
    public function __construct(
        string $message = "Erreur lors de la suggestion de domaines.",
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
