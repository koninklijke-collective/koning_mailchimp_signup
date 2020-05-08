<?php

defined('TYPO3_MODE') or die('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'koning_mailchimp_signup',
    'Form',
    'LLL:EXT:koning_mailchimp_signup/Resources/Private/Language/locallang_be.xlf:plugin.form'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['koningmailchimpsignup_form'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'koningmailchimpsignup_form',
    'FILE:EXT:koning_mailchimp_signup/Configuration/FlexForm/Form.xml'
);
