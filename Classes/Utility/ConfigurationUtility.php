<?php

namespace Keizer\KoningMailchimpSignup\Utility;

use Keizer\KoningMailchimpSignup\Exception\ConfigurationException;

/**
 * Utility: Configuration
 */
class ConfigurationUtility
{
    public const EXTENSION = 'koning_mailchimp_signup';

    /** @var array */
    public static $configuration;

    /**
     * Get Mailchimp settings
     *
     * @return array
     */
    public static function getMailchimpKey(): string
    {
        $configuration = static::getConfiguration();

        return $configuration['mailchimp']['apiKey'] ?? $configuration['mailchimp.']['apiKey'] ?? '';
    }

    /**
     * @return bool
     * @throws \Keizer\KoningMailchimpSignup\Exception\ConfigurationException
     */
    public static function validate(): bool
    {
        if (!class_exists('\DrewM\MailChimp\MailChimp')) {
            throw ConfigurationException::requiredLibraryNotLoaded();
        }

        if (static::getConfiguration() === []) {
            throw ConfigurationException::noConfigurationFound(static::EXTENSION);
        }

        if (static::getMailchimpKey() === '') {
            throw ConfigurationException::missingMailChimpKey();
        }

        return true;
    }

    /**
     * Get Global Configuration from:
     * $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['koning_mailchimp_signup']
     *
     * @return array
     */
    public static function getConfiguration(): array
    {
        if (static::$configuration === null) {
            try {
                static::$configuration = static::resolveExtensionConfiguration(static::EXTENSION);
            } catch (ConfigurationException $e) {
                // Do not throw exception in getter
                static::$configuration = [];
            }
        }

        return static::$configuration;
    }

    /**
     * @param  string  $key
     * @return array
     */
    public static function resolveExtensionConfiguration(string $key): array
    {
        $data = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$key]
            ?? $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS'][$key]
            ?? [];
        if (!empty($data)) {
            // Re-retrieve data when referenced
            if (is_string($data)) {
                return static::resolveExtensionConfiguration($data);
            }

            if (is_array($data)) {
                return $data;
            }
        }

        throw ConfigurationException::noConfigurationFound($key);
    }
}
