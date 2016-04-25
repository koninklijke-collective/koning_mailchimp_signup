<?php
namespace Keizer\KoningMailchimpSignup\Domain\Repository;

/**
 * Repository: Subscriber list
 *
 * @package Keizer\KoningMailchimpSignup\Domain\Model
 */
class SubscriberListRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
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
     * @param array $foundListIds
     * @return void
     */
    public function removeRemotelyDeleted(array $foundListIds)
    {
        $query = $this->createQuery();
        $constraints = [];
        $constraints[] = $query->logicalNot($query->in('identifier', $foundListIds));
        $records = $query->matching($query->logicalAnd($constraints))->execute();
        foreach ($records as $record) {
            $this->remove($record);
        }
    }
}
