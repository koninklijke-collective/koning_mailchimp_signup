<?php

defined('TYPO3_MODE') or die('Access denied.');

call_user_func(function ($extension): void {
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        'mailchimp-member',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:koning_mailchimp_signup/Resources/Public/Icons/mailchimp-member.svg']
    );
    $iconRegistry->registerIcon(
        'mailchimp-list',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:koning_mailchimp_signup/Resources/Public/Icons/mailchimp-list.svg']
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Keizer.' . $extension,
        'Form',
        ['Form' => 'show, create, success'],
        ['Form' => 'create'],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_PLUGIN
    );
}, 'koning_mailchimp_signup');
