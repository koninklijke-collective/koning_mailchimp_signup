<?php
namespace Keizer\KoningMailchimpSignup\Utility;

/**
 * Utility: Configuration
 *
 * @package Keizer\KoningMailchimpSignup\Utility
 */
class ConfigurationUtility
{
    /**
     * Check if Mailchimp configuration is rightly configured
     *
     * @return boolean
     */
    public static function isValid()
    {
        $settings = static::getMailchimpSettings();
        return (is_array($settings) && !empty($settings['apiKey']));
    }

    /**
     * Get Mailchimp settings
     *
     * @return array
     */
    public static function getMailchimpSettings()
    {
        $configuration = static::getConfiguration();
        return (isset($configuration['mailchimp']) ? $configuration['mailchimp'] : $configuration['mailchimp.']);
    }

    /**
     * Get global configuration
     *
     * @return array
     */
    public static function getConfiguration()
    {
        static $configuration;
        if ($configuration === null) {
            $data = $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['koning_mailchimp_signup'];
            if (!is_array($data)) {
                $configuration = (array) unserialize($data);
            } else {
                $configuration = $data;
            }
        }
        return $configuration;
    }
}
