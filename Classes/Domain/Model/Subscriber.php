<?php
namespace Keizer\KoningMailchimpSignup\Domain\Model;

/**
 * Model: Subscriber
 *
 * @package Keizer\KoningMailchimpSignip\Domain\Model
 */
class Subscriber extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * @lazy
     * @var \Keizer\KoningMailchimpSignup\Domain\Model\SubscriberList
     */
    protected $list;

    /**
     * @var string
     */
    protected $email;

    /**
     * @return SubscriberList
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @param SubscriberList $list
     * @return void
     */
    public function setList(SubscriberList $list)
    {
        $this->list = $list;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = strtolower($email);
    }
}
