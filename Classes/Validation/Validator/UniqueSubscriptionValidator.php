<?php

namespace Keizer\KoningMailchimpSignup\Validation\Validator;

use Keizer\KoningMailchimpSignup\Domain\Repository\AudienceRepository;
use Keizer\KoningMailchimpSignup\Exception\ValidationException;
use Keizer\KoningMailchimpSignup\Service\MailChimpService;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

/**
 * Validator: Unique MailChimp member in audience list
 */
class UniqueSubscriptionValidator extends AbstractValidator
{
    /** @var \TYPO3\CMS\Core\Service\FlexFormService */
    protected $flexFormService;

    /** @var \Keizer\KoningMailchimpSignup\Service\MailChimpService */
    protected $mailChimpService;

    /** @var \Keizer\KoningMailchimpSignup\Domain\Repository\AudienceRepository */
    protected $audienceRepository;

    /** @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer */
    protected $contentObject;

    /** @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface */
    protected $configurationManager;

    /**
     * @param  \TYPO3\CMS\Core\Service\FlexFormService  $flexFormService
     * @return void
     */
    public function injectFlexFormService(FlexFormService $flexFormService): void
    {
        $this->flexFormService = $flexFormService;
    }

    /**
     * @param  \Keizer\KoningMailchimpSignup\Service\MailChimpService  $mailChimpService
     * @return void
     */
    public function injectMailChimpService(MailChimpService $mailChimpService): void
    {
        $this->mailChimpService = $mailChimpService;
    }

    /**
     * @param  \Keizer\KoningMailchimpSignup\Domain\Repository\AudienceRepository  $audienceRepository
     * @return void
     */
    public function injectAudienceRepository(AudienceRepository $audienceRepository): void
    {
        $this->audienceRepository = $audienceRepository;
    }

    /**
     * @param  \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface  $configurationManager
     * @return void
     */
    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager): void
    {
        $this->configurationManager = $configurationManager;
        $this->contentObject = $this->configurationManager->getContentObject();
    }

    /**
     * @param  mixed  $value
     * @return void
     */
    protected function isValid($value): void
    {
        $configuration = $this->flexFormService->convertFlexFormContentToArray($this->contentObject->data['pi_flexform']);
        $list = $configuration['settings']['data']['list'] ?? '';
        if (empty($list)) {
            throw ValidationException::invalidAudience();
        }

        $audience = $this->audienceRepository->get($list);
        if ($audience === null) {
            throw ValidationException::invalidAudience();
        }

        if ($this->mailChimpService->isMember($audience->getIdentifier(), $value)) {
            $this->addError('E-mail address is already subscribed to this list', 1461581334);
        }
    }
}
