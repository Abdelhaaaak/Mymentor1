<?php

namespace App\Exceptions;

use Exception;

/**
 * Exception levée lorsque la configuration Cohere est incomplète ou invalide.
 */
class CohereConfigurationException extends Exception
{
    /**
     * Création d’une nouvelle instance de l’exception.
     *
     * @param  string       $message  Message d’erreur (par défaut : “Configuration Cohere incomplète”).
     * @param  int          $code     Code d’erreur (par défaut : 0).
     * @param  Exception|null  $previous  Exception précédente pour la pile d’exceptions.
     */
    public function __construct(
        string $message = 'Configuration Cohere incomplète',
        int $code = 0,
        Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
