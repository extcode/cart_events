<?php
declare(strict_types=1);
namespace Extcode\CartEvents\Updates;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\ChattyInterface;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * Generate slugs for empty path_segments
 */
class SlugUpdater implements UpgradeWizardInterface, ChattyInterface
{
    const IDENTIFIER = 'cartEventsSlugUpdater';
    const TABLE_NAME = 'tx_cartevents_domain_model_event';

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * Return the identifier for this wizard
     * This should be the same string as used in the ext_localconf class registration
     */
    public function getIdentifier(): string
    {
        return self::IDENTIFIER;
    }

    public function getTitle(): string
    {
        return 'Updates slug field "path_segment" of EXT:cart_events records';
    }

    public function getDescription(): string
    {
        return 'TYPO3 includes native URL handling. Every event record has its own speaking URL path called "slug" which can be edited in TYPO3 Backend. However, it is necessary that all events have a URL pre-filled. This is done by evaluating the title.';
    }

    /**
     * Checks if an update is needed
     */
    public function updateNecessary(): bool
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable(self::TABLE_NAME);
        $queryBuilder->getRestrictions()->removeAll();
        $elementCount = $queryBuilder->count('uid')
            ->from(self::TABLE_NAME)
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq('path_segment', $queryBuilder->createNamedParameter('', \PDO::PARAM_STR)),
                    $queryBuilder->expr()->isNull('path_segment')
                )
            )
            ->execute()->fetchColumn(0);

        return (bool)$elementCount;
    }

    /**
     * Performs the database update
     */
    public function executeUpdate(): bool
    {
        $slugHelper = GeneralUtility::makeInstance(
            SlugHelper::class,
            self::TABLE_NAME,
            'path_segment',
            $GLOBALS['TCA'][self::TABLE_NAME]['columns']['path_segment']['config']
        );

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable(self::TABLE_NAME);
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll();
        $statement = $queryBuilder->select('uid', 'title')
            ->from(self::TABLE_NAME)
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq('path_segment', $queryBuilder->createNamedParameter('', \PDO::PARAM_STR)),
                    $queryBuilder->expr()->isNull('path_segment')
                )
            )
            ->execute();
        while ($record = $statement->fetch()) {
            $queryBuilder = $connection->createQueryBuilder();
            $queryBuilder->update(self::TABLE_NAME)
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($record['uid'], \PDO::PARAM_INT)
                    )
                )
                ->set('path_segment', $slugHelper->sanitize((string)$record['title']));
            $queryBuilder->getSQL();
            $queryBuilder->execute();
        }

        return true;
    }

    /**
     * Returns an array of class names of Prerequisite classes
     *
     * @return string[]
     */
    public function getPrerequisites(): array
    {
        return [];
    }

    /**
     * Setter injection for output into upgrade wizards
     */
    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }
}
