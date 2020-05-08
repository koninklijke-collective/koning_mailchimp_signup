<?php

namespace Keizer\KoningMailchimpSignup\Domain\Model;

use Keizer\KoningMailchimpSignup\Exception\ValidationException;

/**
 * Model: audience/list
 */
class Audience
{
    public const TABLE = 'mailchimp_audience';

    /**
     * Expected row for internal usage
     *
     * @see \Keizer\KoningMailchimpSignup\Domain\Model\Audience::fields()
     * @see \Keizer\KoningMailchimpSignup\Domain\Model\Audience::values()
     */
    protected const ROW = [
        'identifier' => '',
        'web_identifier' => 0,
        'name' => '',
    ];

    /** @var array */
    protected $row = self::ROW;

    /**
     * @param  array  $row
     * @throws \Exception
     */
    public function __construct(array $row)
    {
        foreach (self::fields() as $field) {
            if (!isset($row[$field])) {
                throw ValidationException::missingField($field);
            }

            $this->row[$field] = $row[$field];
        }
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->row['identifier'];
    }

    /**
     * @return int
     */
    public function getWebIdentifier(): int
    {
        return (int)$this->row['web_identifier'];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->row['name'];
    }

    /**
     * @return array
     */
    public static function fields(): array
    {
        return array_keys(self::ROW);
    }

    /**
     * @return array
     */
    public function values(): array
    {
        return $this->row;
    }
}
