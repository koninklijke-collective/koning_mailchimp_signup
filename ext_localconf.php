<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'Keizer\\KoningMailchimpSignup\\Command\\MailChimpCommandController';

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Keizer.' . $_EXTKEY,
    'Form',
    array(
        'Form' => 'show, create, success, failed'
    ),
    array(
        'Form' => 'show, create, success, failed'
    ),
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_PLUGIN
);
