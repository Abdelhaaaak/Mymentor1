<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | Ce fichier stocke les identifiants pour les services tiers.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel'              => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration de l'API Hugging Face
    |--------------------------------------------------------------------------
    |
    | Utilisée pour les inférences de modèles (ex. google/flan-t5-small).
    | Assurez-vous d'avoir dans votre .env :
    |   HUGGINGFACE_TOKEN    (votre clé d'API)
    |   HUGGINGFACE_MODEL    (identifiant du modèle)
    |   HUGGINGFACE_API_URL  (facultatif, URL de base)
    |
    */


  



    'cohere' => [
        'token'    => env('COHERE_API_KEY'),
        'endpoint' => env('COHERE_API_URL'),
        'model'    => env('COHERE_API_MODEL', 'command'),
    ],
    // …



    /*
    |--------------------------------------------------------------------------
    | Configuration de l'API OpenAI
    |--------------------------------------------------------------------------
    |
    */
   
    /*
    |--------------------------------------------------------------------------
    | Configuration du service de suggestion de domaine
    |--------------------------------------------------------------------------
    |
    | Utilise une clé API pour suggérer des noms de domaine.
    */
   

];
