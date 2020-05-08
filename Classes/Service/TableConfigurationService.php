<?php

namespace Keizer\KoningMailchimpSignup\Service;

use Keizer\KoningMailchimpSignup\Domain\Repository\AudienceRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TableConfigurationService
{
    /**
     * @param  array  $parameters
     * @return void
     */
    public function addAvailableAudiences(array &$parameters): void
    {
        $parameters['items'] = [
            [
                'LLL:EXT:koning_mailchimp_signup/Resources/Private/Language/locallang_be.xlf:configuration.form.list.select',
                '--div--',
            ],
        ];

        $audiences = $this->getAudienceRepository()->all();
        if (empty($audiences)) {
            $parameters['items'][] = [
                'LLL:EXT:koning_mailchimp_signup/Resources/Private/Language/locallang_be.xlf:configuration.form.list.select.empty',
                null,
            ];

            return;
        }

        foreach ($audiences as $audience) {
            $parameters['items'][] = [
                $audience->getName(),
                $audience->getIdentifier(),
            ];
        }
    }

    /**
     * @param  array  $parameters
     * @return void
     */
    public function addAvailableFormFieldsForSelectedAudience(array &$parameters): void
    {
        $parameters['items'] = [
            [
                'LLL:EXT:koning_mailchimp_signup/Resources/Private/Language/locallang_be.xlf:configuration.form.fields.email',
                'EMAIL',
            ],
        ];

        $list = $parameters['row']['settings.data.list'] ?? '';
        if (is_array($list)) {
            $list = reset($list);
        }

        if (!is_string($list) || empty($list)) {
            $parameters['items'][] = [
                'LLL:EXT:koning_mailchimp_signup/Resources/Private/Language/locallang_be.xlf:configuration.form.fields.error.no_list_selected',
                '--div--',
            ];

            return;
        }

        foreach ($this->getMailChimpService()->mergeFields($list) as $field) {
            $parameters['items'][] = [
                $field['name'],
                $field['tag'],
            ];
        }
    }

    /**
     * @return \Keizer\KoningMailchimpSignup\Service\MailChimpService
     */
    protected function getMailChimpService(): MailChimpService
    {
        return GeneralUtility::makeInstance(MailChimpService::class);
    }

    /**
     * @return \Keizer\KoningMailchimpSignup\Domain\Repository\AudienceRepository
     */
    protected function getAudienceRepository(): AudienceRepository
    {
        return GeneralUtility::makeInstance(AudienceRepository::class);
    }
}
