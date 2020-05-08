<?php

namespace Keizer\KoningMailchimpSignup\Exception;

class ConfigurationException extends \RuntimeException implements ExtensionException
{
    /**
     * @return \Keizer\KoningMailchimpSignup\Exception\ConfigurationException
     */
    public static function requiredLibraryNotLoaded(): self
    {
        return new self(
            'MailChimp API wrapper not found. Run composer require drewm/mailchimp-api to install it.',
            1588856564
        );
    }

    /**
     * @return \Keizer\KoningMailchimpSignup\Exception\ConfigurationException
     */
    public static function missingMailChimpKey(): self
    {
        return new self(
            'MailChimp api key not found. Check the Extension Manager for configuring the settings.',
            1588857030
        );
    }

    /**
     * @param  string  $key
     * @return \Keizer\KoningMailchimpSignup\Exception\ConfigurationException
     */
    public static function noConfigurationFound(string $key): self
    {
        return new self(
            'No extension configuration found with key: ' . $key,
            1588857287
        );
    }
}
