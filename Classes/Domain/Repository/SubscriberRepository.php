<?php
namespace Keizer\KoningMailchimpSignup\Domain\Repository;

use Keizer\KoningMailchimpSignup\Domain\Model\Subscriber;
use Keizer\KoningMailchimpSignup\Domain\Model\SubscriberList;

/**
 * Repository: Subscriber
 *
 * @package Keizer\KoningMailchimpSignup\Domain\Model
 */
class SubscriberRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * @return void
     */
    public function initializeObject()
    {
        /* @var \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings $querySettings */
        $querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
        $querySettings->setStoragePageIds(array($querySettings->setStoragePageIds(array(0))));
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * @param string $email
     * @param SubscriberList $subscriberList
     * @return Subscriber
     */
    public function findOneByEmailAndSubscriberList($email, SubscriberList $subscriberList)
    {
        $query = $this->createQuery();
        $constraints = [];
        $constraints[] = $query->equals('email', $email);
        $constraints[] = $query->equals('list', $subscriberList);
        return $query->matching($query->logicalAnd($constraints))->execute()->getFirst();
    }

    /**
     * @param string $limit
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findAllByLimit($limit)
    {
        return $this->createQuery()->setLimit($limit)->execute();
    }
}
