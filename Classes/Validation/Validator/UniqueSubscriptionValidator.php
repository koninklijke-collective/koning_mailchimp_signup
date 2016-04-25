<?php
namespace Keizer\KoningMailchimpSignup\Validation\Validator;

use Keizer\KoningMailchimpSignup\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Keizer\KoningMailchimpSignup\Domain\Model\SubscriberList;

/**
 * Validator: Unique subscription
 *
 * @package Keizer\KoningMailchimpSignup\Validation\Validator
 */
class UniqueSubscriptionValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{
    /**
     * @var \Keizer\KoningMailchimpSignup\Domain\Repository\SubscriberListRepository
     * @inject
     */
    protected $subscriberListRepository;

    /**
     * @var \Keizer\KoningMailchimpSignup\Domain\Repository\SubscriberRepository
     * @inject
     */
    protected $subscriberRepository;

    /**
     * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
     */
    protected $contentObject;

    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
     * @return void
     */
    public function injectConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager) {
        $this->configurationManager = $configurationManager;
        $this->contentObject = $this->configurationManager->getContentObject();
    }

    /**
     * @param mixed $value
     * @return void
     */
    protected function isValid($value)
    {
        /** @var \TYPO3\CMS\Extbase\Service\FlexFormService $flexFormService */
        $flexFormService = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Service\\FlexFormService');
        $flexFormArray = $flexFormService->convertFlexFormContentToArray($this->contentObject->data['pi_flexform']);

        /** @var SubscriberList $list */
        $list = $this->subscriberListRepository->findByUid($flexFormArray['settings']['data']['list']);
        if ($list !== null) {
            $subscriber = $this->subscriberRepository->findOneByEmailAndSubscriberList(strtolower($value), $list);
            if ($subscriber !== null) {
                $this->addError('E-mail address is already subscribed to this list', 1461581334);
            } else {
                $settings = ConfigurationUtility::getConfiguration();
                $mailChimpApi = new \DrewM\MailChimp\MailChimp($settings['mailchimp.']['apiKey']);
                $url = 'lists/' . $list->getIdentifier() . '/members/' . $mailChimpApi->subscriberHash($value);
                $memberRequest = $mailChimpApi->get($url);
                if (isset($memberRequest['status']) && $memberRequest['status'] === 'subscribed') {
                    $this->addError('E-mail address is already subscribed to this list', 1461581334);
                }
            }
        } else {
            $this->addError('List not found', 1461581360);
        }
    }
}
