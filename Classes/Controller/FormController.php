<?php

namespace Keizer\KoningMailchimpSignup\Controller;

use Keizer\KoningMailchimpSignup\Domain\Model\Audience;
use Keizer\KoningMailchimpSignup\Domain\Repository\AudienceRepository;
use Keizer\KoningMailchimpSignup\Exception\ExtensionException;
use Keizer\KoningMailchimpSignup\Service\MailChimpService;
use Keizer\KoningMailchimpSignup\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class FormController extends ActionController
{
    /** @var \Keizer\KoningMailchimpSignup\Domain\Model\Audience */
    protected $audience;

    /** @var \Keizer\KoningMailchimpSignup\Service\MailChimpService */
    protected $mailChimpService;

    /** @var \Keizer\KoningMailchimpSignup\Domain\Repository\AudienceRepository */
    protected $audienceRepository;

    /**
     * @param  \Keizer\KoningMailchimpSignup\Domain\Repository\AudienceRepository  $audienceRepository
     * @param  \Keizer\KoningMailchimpSignup\Service\MailChimpService  $mailchimpService
     */
    public function __construct(
        AudienceRepository $audienceRepository,
        MailChimpService $mailchimpService
    ) {
        parent::__construct();
        $this->audienceRepository = $audienceRepository;
        $this->mailChimpService = $mailchimpService;
    }

    /**
     * @return void
     */
    public function initializeAction(): void
    {
        parent::initializeAction();

        if (empty($this->settings['data']['list'])) {
            return;
        }

        $this->audience = $this->audienceRepository->get($this->settings['data']['list']);
    }

    /**
     * Show MailChimp registration form
     *
     * @return string|void
     * @throws \Exception
     */
    public function showAction()
    {
        if (!$this->audience instanceof Audience) {
            return 'No valid MailChimp audience selected: check plugin configuration.';
        }

        try {
            ConfigurationUtility::validate();
        } catch (ExtensionException $e) {
            return $e->getMessage();
        }

        $configuredFields = GeneralUtility::trimExplode(',', $this->settings['data']['fields'], true);
        $fields = $this->mailChimpService->enhanceFields($this->audience->getIdentifier(), array_flip($configuredFields));

        $this->view->assign('fields', $fields);
    }

    /**
     * Create MailChimp audience member
     *
     * @param  string  $email
     * @param  array  $fields
     * @return string|void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty", param="email")
     * @TYPO3\CMS\Extbase\Annotation\Validate("EmailAddress", param="email")
     * @TYPO3\CMS\Extbase\Annotation\Validate("\Keizer\KoningMailchimpSignup\Validation\Validator\UniqueSubscriptionValidator", param="email")
     */
    public function createAction(string $email = '', array $fields = [])
    {
        if (!$this->audience instanceof Audience) {
            return 'No valid MailChimp audience selected: check plugin configuration.';
        }

        // Flatten fields with expected format
        foreach ($fields as $key => $value) {
            if (!is_array($value)) {
                continue;
            }
            if (isset($value['date'], $value['format'])) {
                $fields[$key] = date($value['format'], strtotime($value['date']));
                continue;
            }
        }

        if ($this->mailChimpService->addMember($this->audience->getIdentifier(), $email, $fields)) {
            $successPage = (int)($this->settings['data']['successPid'] ?? 0);
            if ($successPage > 0) {
                $url = $this->uriBuilder->reset()->setTargetPageUid($successPage)->build();
                $this->redirectToUri($url);
            } else {
                $this->redirect('success');
            }
        }

        $this->forwardToReferringRequest();
    }

    /**
     * Shown when the sign up was successful
     *
     * @return void
     */
    public function successAction(): void
    {
    }
}
