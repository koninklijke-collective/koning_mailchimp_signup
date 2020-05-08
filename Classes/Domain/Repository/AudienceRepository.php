<?php

namespace Keizer\KoningMailchimpSignup\Domain\Repository;

use Doctrine\DBAL\Driver\DriverException;
use Keizer\KoningMailchimpSignup\Domain\Model\Audience;
use Keizer\KoningMailchimpSignup\Exception\ValidationException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AudienceRepository implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /** @var \TYPO3\CMS\Core\Context\Context */
    protected $context;

    /** @var \TYPO3\CMS\Core\Database\Query\QueryBuilder */
    protected $queryBuilder;

    /**
     * @param  \TYPO3\CMS\Core\Context\Context|null  $context
     */
    public function __construct(?Context $context = null, ?ConnectionPool $connectionPool = null)
    {
        $this->context = $context ?? GeneralUtility::makeInstance(Context::class);
        $connectionPool = $connectionPool ?? GeneralUtility::makeInstance(ConnectionPool::class);
        $this->queryBuilder = $connectionPool->getQueryBuilderForTable(Audience::TABLE);
    }

    /**
     * Create or update record using the QueryBuilder
     *
     * @param  \Keizer\KoningMailchimpSignup\Domain\Model\Audience  $audience
     * @return void
     */
    public function createOrUpdate(Audience $audience): void
    {
        try {
            $original = $this->get($audience->getIdentifier());
            if ($original !== null) {
                // Update
                $this->queryBuilder->update(Audience::TABLE)
                    ->where($this->queryBuilder->expr()->eq(
                        'identifier',
                        $this->queryBuilder->createNamedParameter($audience->getIdentifier())
                    ))
                    ->set('name', $audience->getName())
                    ->execute();

                return;
            }

            // Insert full record
            $this->queryBuilder->insert(Audience::TABLE)
                ->values($audience->values())
                ->execute();
        } catch (DriverException $e) {
        }
    }

    /**
     * @return \Keizer\KoningMailchimpSignup\Domain\Model\Audience[]
     */
    public function all(): array
    {
        try {
            $query = $this->queryBuilder->select(...Audience::fields())
                ->from(Audience::TABLE);
            $statement = $query->execute();

            $audiences = [];
            while ($row = $statement->fetch()) {
                $audiences[] = new Audience($row);
            }

            return $audiences;
        } catch (DriverException $e) {
            return [];
        }
    }

    /**
     * @param  string  $identifier
     * @return \Keizer\KoningMailchimpSignup\Domain\Model\Audience|null
     */
    public function get(string $identifier): ?Audience
    {
        try {
            $query = $this->queryBuilder->select(...Audience::fields())
                ->from(Audience::TABLE)
                ->where($this->queryBuilder->expr()->eq(
                    'identifier',
                    $this->queryBuilder->createNamedParameter($identifier)
                ));

            $row = $query->execute()->fetch();
            if (is_array($row)) {
                return new Audience($row);
            }
        } catch (ValidationException | DriverException $e) {
        }

        return null;
    }
}
