<?php
namespace Keizer\KoningMailchimpSignup\Command;

use Keizer\KoningMailchimpSignup\Domain\Model\Subscriber;
use Keizer\KoningMailchimpSignup\Domain\Model\SubscriberList;
use Keizer\KoningMailchimpSignup\Utility\ConfigurationUtility;

/**
 * Command: MailChimp
 *
 * @package Keizer\KoningMailchimpSignup\Command
 */
class MailChimpCommandController extends  \TYPO3\CMS\Extbase\Mvc\Controller\CommandController
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
     * Syncs lists from MailChimp to TYPO3
     *
     * @return bool
     * @throws \Exception
     */
    public function listsCommand()
    {
        if (!class_exists('\DrewM\MailChimp\MailChimp')) {
            throw new \Exception('MailChimp API wrapper not found. Run composer require drewm/mailchimp-api to install it and make sure vendor/autoload.php is included.');
        }
        if (!ConfigurationUtility::isValid()) {
            throw new \Exception('MailChimp settings not found. Check the Extension Manager for configuring the settings.');
        }

        $settings = ConfigurationUtility::getConfiguration();

        $mailChimpApi = new \DrewM\MailChimp\MailChimp($settings['mailchimp.']['apiKey']);
        $listsResult = $mailChimpApi->get('lists');
        if (isset($listsResult['lists'])) {
            $foundListIds = [];
            foreach ($listsResult['lists'] as $list) {
                /** @var SubscriberList $existingListModel */
                $existingListModel = $this->subscriberListRepository->findOneByIdentifier($list['id']);
                if ($existingListModel !== null) {
                    $existingListModel->setName($list['name']);
                    $this->subscriberListRepository->update($existingListModel);
                } else {
                    $newListModel = new SubscriberList();
                    $newListModel->setIdentifier($list['id']);
                    $newListModel->setName($list['name']);
                    $newListModel->setPid(0);
                    $this->subscriberListRepository->add($newListModel);
                }
                $foundListIds[] = $list['id'];
            }
            $this->subscriberListRepository->removeRemotelyDeleted($foundListIds);
        } else {
            throw new \Exception('Error while contacting the MailChimp API: ' . $mailChimpApi->getLastError());
        }
        return true;
    }

    /**
     * Adds subscribers from TYPO3 to MailChimp
     *
     * @return bool
     * @throws \Exception
     */
    public function subscribersCommand()
    {
        if (!class_exists('\DrewM\MailChimp\MailChimp')) {
            throw new \Exception('MailChimp API wrapper not found. Run composer require drewm/mailchimp-api to install it and make sure vendor/autoload.php is included.');
        }
        if (!ConfigurationUtility::isValid()) {
            throw new \Exception('MailChimp settings not found. Check the Extension Manager for configuring the settings.');
        }

        $subscribers = $this->subscriberRepository->findAllByLimit(25);
        if (!empty($subscribers)) {
            $settings = ConfigurationUtility::getConfiguration();
            $mailChimpApi = new \DrewM\MailChimp\MailChimp($settings['mailchimp.']['apiKey']);
            foreach ($subscribers as $subscriber) {
                /** @var Subscriber $subscriber */
                $result = $mailChimpApi->post(
                    'lists/' . $subscriber->getList()->getIdentifier() . '/members',
                    [
                        'email_address' => $subscriber->getEmail(),
                        'status' => 'subscribed'
                    ]
                );
                $delete = false;
                if ($result !== false) {
                    switch ($result['status']) {
                        case 'subscribed':
                            $delete = true;
                            break;
                        case 400:
                        default:
                            if ($result['title'] === 'Member Exists') {
                                $delete = true;
                            }
                            break;
                    }
                    if ($result['status'] === 400 && $result['title'] === 'Member Exists') {
                        $delete = true;
                    }
                }
                if ($delete) {
                    $this->subscriberRepository->remove($subscriber);
                }
            }
        }
        return true;
    }
}
