<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

return array(
    'ctrl' => array(
        'title' => 'LLL:EXT:koning_mailchimp_signup/Resources/Private/Language/locallang_be.xlf:tx_koningmailchimpsignup_domain_model_subscriberlist.singular',
        'groupName' => 'LLL:EXT:koning_mailchimp_signup/Resources/Private/Language/locallang_be.xlf:tx_koningmailchimpsignup_domain_model_subscriberlist.plural',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'editlock' => 'editlock',
        'dividers2tabs' => true,
        'iconfile' => 'EXT:koning_mailchimp_signup/Resources/Public/Icons/tx_koningmailchimpsignup_domain_model_subscriberlist.png',
        'rootLevel' => true,
        'canNotCollapse' => true,
        'hideTable' => false,
        'security' => array(
            'ignoreWebMountRestriction' => true,
            'ignoreRootLevelRestriction' => true,
        ),
        'readOnly' => true
    ),
    'interface' => array(
        'showRecordFieldList' => 'identifier, name, subscribers'
    ),
    'types' => array(
        0 => array(
            'showitem' => 'identifier, name, subscribers'
        )
    ),
    'palettes' => array(),
    'columns' => array(
        'identifier' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:koning_mailchimp_signup/Resources/Private/Language/locallang_be.xlf:tx_koningmailchimpsignup_domain_model_subscriberlist.identifier',
            'config' => array(
                'type' => 'input',
                'size' => 30,
            )
        ),
        'name' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:koning_mailchimp_signup/Resources/Private/Language/locallang_be.xlf:tx_koningmailchimpsignup_domain_model_subscriberlist.name',
            'config' => array(
                'type' => 'input',
                'size' => 30,
            )
        ),
        'subscribers' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:koning_mailchimp_signup/Resources/Private/Language/locallang_be.xlf:tx_koningmailchimpsignup_domain_model_subscriberlist.subscribers',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_koningmailchimpsignup_domain_model_subscriber',
                'foreign_table' => 'tx_koningmailchimpsignup_domain_model_subscriber',
                'foreign_field' => 'list',
                'minitems' => 0,
                'maxitems' => 9999,
            ),
        ),
    ),
);
