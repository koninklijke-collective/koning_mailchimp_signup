<?php
namespace Keizer\KoningMailchimpSignup\Domain\Model;

/**
 * Model: Subscriber list
 *
 * @package Keizer\KoningMailchimpSignip\Domain\Model
 */
class SubscriberList extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Keizer\KoningMailchimpSignup\Domain\Model\Subscriber>
     */
    protected $subscribers;

    /**
     * Constructor
     */
    public function __construct() {
        $this->initStorageObjects();
    }

    /**
     * @return void
     */
    protected function initStorageObjects() {
        $this->subscribers = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     * @return void
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getSubscribers()
    {
        return $this->subscribers;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $subscribers
     * @return void
     */
    public function setSubscribers(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $subscribers)
    {
        $this->subscribers = $subscribers;
    }
}
