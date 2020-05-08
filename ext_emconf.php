<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'MailChimp sign up',
    'description' => 'MailChimp sign up form for TYPO3',
    'category' => 'plugin',
    'version' => '2.0.0',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'author' => 'Jesper Paardekooper',
    'author_email' => 'koninklijkecollective@gmail.com',
    'author_company' => 'Koninklijke Collective',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-9.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
            'Keizer\\KoningMailchimpSignup\\' => 'Classes',
        ],
    ],
];
