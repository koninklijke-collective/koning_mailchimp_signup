<?php

namespace Keizer\KoningMailchimpSignup\Exception;

class ValidationException extends \RuntimeException implements ExtensionException
{
    /**
     * @param  string  $field
     * @return \Keizer\KoningMailchimpSignup\Exception\ValidationException
     */
    public static function missingField(string $field): self
    {
        return new self('Missing field for audience: ' . $field, 1588930954);
    }

    /**
     * @return \Keizer\KoningMailchimpSignup\Exception\ValidationException
     */
    public static function invalidAudience(): self
    {
        return new self('Configured audience cannot be found, are you trying something fishy?', 1461581360);
    }
}
