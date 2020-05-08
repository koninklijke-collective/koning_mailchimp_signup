<?php

namespace Keizer\KoningMailchimpSignup\Service;

use DrewM\MailChimp\MailChimp;
use Keizer\KoningMailchimpSignup\Utility\ConfigurationUtility;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class MailChimpService implements LoggerAwareInterface, SingletonInterface
{
    use LoggerAwareTrait;

    /** @var \DrewM\MailChimp\MailChimp */
    protected $client;

    /**
     * @param  \DrewM\MailChimp\MailChimp|null  $client
     */
    public function __construct(?MailChimp $client = null)
    {
        $this->client = $client ?? new MailChimp(ConfigurationUtility::getMailchimpKey());
    }

    /**
     * @return array
     */
    public function lists(): array
    {
        $data = $this->client->get('lists');

        return $data['lists'] ?? [];
    }

    /**
     * @param  string  $audienceId
     * @return array
     */
    public function list(string $audienceId): ?array
    {
        $list = $this->client->get('lists/' . $audienceId);

        return isset($list['id']) ? $list : null;
    }

    /**
     * @param  string  $audienceId
     * @param  array  $fields
     * @return array
     */
    public function enhanceFields(string $audienceId, array $fields): array
    {
        $fields['EMAIL'] = [
            'id' => 'mailchimp-email-address',
            'name' => 'email',
            'label' => LocalizationUtility::translate(
                'LLL:EXT:koning_mailchimp_signup/Resources/Private/Language/locallang.xlf:fields_email_label'
            ),
            'type' => 'input',
            'input_type' => 'email',
            'required' => true,
        ];

        if (count($fields) === 1) {
            return $fields;
        }

        $mergeFields = $this->mergeFields($audienceId);
        foreach (array_keys($fields) as $field) {
            if ($field === 'EMAIL') {
                // Already enhanced outside foreach
                continue;
            }
            if (!isset($mergeFields[$field])) {
                // If not available as key, unset and parse next field
                unset($fields[$field]);
                continue;
            }

            $row = $mergeFields[$field];
            switch ($row['type']) {
                case 'birthday':
                    $fields[$field] = [
                        'id' => 'mailchimp-' . strtolower($field),
                        'name' => 'fields[' . $field . ']',
                        'label' => $row['name'] ?? $field,
                        'type' => 'date',
                        'additionalAttributes' => [
                            'min' => date('Y-m-d', strtotime('-150 years')),
                            'max' => date('Y-m-d', strtotime('-12 years')),
                        ],
                        'format' => $row['options']['date_format'] === 'MM/DD' ? 'm/d' : 'd/m',
                        'required' => $row['required'] ?? false,
                    ];

                    break;

                case 'number':
                    $fields[$field] = [
                        'id' => 'mailchimp-' . strtolower($field),
                        'name' => 'fields[' . $field . ']',
                        'label' => $row['name'] ?? $field,
                        'type' => 'input',
                        'input_type' => 'number',
                        'required' => $row['required'] ?? false,
                    ];

                    break;

                case 'phone':
                    $fields[$field] = [
                        'id' => 'mailchimp-' . strtolower($field),
                        'name' => 'fields[' . $field . ']',
                        'label' => $row['name'] ?? $field,
                        'type' => 'input',
                        'input_type' => 'tel',
                        'required' => $row['required'] ?? false,
                    ];

                    break;

                case 'address':
                case 'text':
                    $fields[$field] = [
                        'id' => 'mailchimp-' . strtolower($field),
                        'name' => 'fields[' . $field . ']',
                        'label' => $row['name'] ?? $field,
                        'type' => 'input',
                        'input_type' => 'text',
                        'required' => $row['required'] ?? false,
                    ];
                    break;

                default:
                    // If not available as key, unset and parse next field
                    $this->logger->warning('Selected field is not yet supported by this extension', ['field' => $row]);
                    unset($fields[$field]);
            }
        }

        return $fields;
    }

    /**
     * @param  string  $audienceId
     * @return array
     */
    public function mergeFields(string $audienceId): ?array
    {
        $data = $this->client->get('lists/' . $audienceId . '/merge-fields');
        $fields = [];
        foreach ($data['merge_fields'] ?? [] as $field) {
            $fields[$field['tag']] = $field;
        }

        return !empty($fields) ? $fields : null;
    }

    /**
     * @param  string  $audience
     * @param  string  $email
     * @param  array  $fields
     * @return bool
     */
    public function addMember(string $audience, string $email, array $fields): bool
    {
        // If merge_fields are given, first try to create with additional data
        if (!empty($fields)) {
            $data = $this->client->post(
                'lists/' . $audience . '/members',
                ['email_address' => $email, 'status' => 'subscribed', 'merge_fields' => $fields]
            );
            $status = $data['status'] ?? 'unknown';
            if ($status === 'subscribed') {
                return true;
            }
        }

        // Fallback or try subscription based without additional fields
        $data = $this->client->post(
            'lists/' . $audience . '/members',
            ['email_address' => $email, 'status' => 'subscribed']
        );

        $status = $data['status'] ?? 'unknown';

        return $status === 'subscribed';
    }

    /**
     * @param  string  $audience
     * @param  string  $email
     * @return bool
     */
    public function isMember(string $audience, string $email): bool
    {
        $data = $this->client->get('lists/' . $audience . '/members/' . MailChimp::subscriberHash($email));
        $status = $data['status'] ?? 'unknown';

        return $status === 'subscribed';
    }
}
