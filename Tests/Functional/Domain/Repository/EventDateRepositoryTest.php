<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Tests\Functional\Domain\Repository;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Codappix\Typo3PhpDatasets\TestingFramework;
use Extcode\CartEvents\Domain\Repository\EventDateRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

#[CoversClass(EventDateRepository::class)]
class EventDateRepositoryTest extends FunctionalTestCase
{
    use TestingFramework;

    private EventDateRepository $eventDateRepository;

    public function setUp(): void
    {
        $this->testExtensionsToLoad[] = 'extcode/cart';
        $this->testExtensionsToLoad[] = 'extcode/cart-events';

        parent::setUp();

        $this->eventDateRepository = GeneralUtility::makeInstance(EventDateRepository::class);

        $this->importPHPDataSet(__DIR__ . '/../../../Fixtures/PagesDatabase.php');
        $this->importPHPDataSet(__DIR__ . '/../../../Fixtures/EventsDatabase.php');
    }

    #[Test]
    public function findNextReturnsNextForOnePid(): void
    {
        $connection = $this->getConnectionPool()->getConnectionForTable('tx_cartevents_domain_model_eventdate');

        $eventDates = $this->eventDateRepository->findNext(1, '7, 9');

        self::assertCount(
            0,
            $eventDates
        );

        $connection->update(
            'tx_cartevents_domain_model_eventdate',
            ['begin' => time() + 3 * 86400],
            ['uid' => 3]
        );

        $connection->update(
            'tx_cartevents_domain_model_eventdate',
            ['begin' => time() + 2 * 86400],
            ['uid' => 4]
        );

        $connection->update(
            'tx_cartevents_domain_model_eventdate',
            ['begin' => time() + 4 * 86400],
            ['uid' => 5]
        );

        $connection->update(
            'tx_cartevents_domain_model_eventdate',
            ['begin' => time() + 1 * 86400],
            ['uid' => 6]
        );

        $eventDates = $this->eventDateRepository->findNext(1, '7');

        self::assertCount(
            1,
            $eventDates
        );

        self::assertSame(
            4,
            $eventDates[0]['uid']
        );

        $eventDates = $this->eventDateRepository->findNext(1, '9');

        self::assertCount(
            1,
            $eventDates
        );

        self::assertSame(
            6,
            $eventDates[0]['uid']
        );
    }

    #[Test]
    public function findNextReturnsNextForTwoPid(): void
    {
        $connection = $this->getConnectionPool()->getConnectionForTable('tx_cartevents_domain_model_eventdate');

        $eventDates = $this->eventDateRepository->findNext(1, '7, 9');

        self::assertCount(
            0,
            $eventDates
        );

        $connection->update(
            'tx_cartevents_domain_model_eventdate',
            ['begin' => time() + 3 * 86400],
            ['uid' => 3]
        );

        $connection->update(
            'tx_cartevents_domain_model_eventdate',
            ['begin' => time() + 2 * 86400],
            ['uid' => 4]
        );

        $connection->update(
            'tx_cartevents_domain_model_eventdate',
            ['begin' => time() + 4 * 86400],
            ['uid' => 5]
        );

        $connection->update(
            'tx_cartevents_domain_model_eventdate',
            ['begin' => time() + 1 * 86400],
            ['uid' => 6]
        );

        $eventDates = $this->eventDateRepository->findNext(1, '7, 9');

        self::assertCount(
            1,
            $eventDates
        );

        self::assertSame(
            6,
            $eventDates[0]['uid']
        );
    }

    #[Test]
    public function findNextsOnlyReturnsNextInFutureInCorrectOrder(): void
    {
        $connection = $this->getConnectionPool()->getConnectionForTable('tx_cartevents_domain_model_eventdate');

        $eventDates = $this->eventDateRepository->findNext(10, '7');

        self::assertCount(
            0,
            $eventDates
        );

        $connection->update(
            'tx_cartevents_domain_model_eventdate',
            ['begin' => time() + 3 * 86400],
            ['uid' => 3]
        );

        $connection->update(
            'tx_cartevents_domain_model_eventdate',
            ['begin' => time() + 2 * 86400],
            ['uid' => 4]
        );

        $connection->update(
            'tx_cartevents_domain_model_eventdate',
            ['begin' => time() + 4 * 86400],
            ['uid' => 5]
        );

        $connection->update(
            'tx_cartevents_domain_model_eventdate',
            ['begin' => time() + 1 * 86400],
            ['uid' => 6]
        );

        $eventDates = $this->eventDateRepository->findNext(10, '7');

        self::assertCount(
            3,
            $eventDates
        );
        self::assertSame(
            4,
            $eventDates[0]['uid']
        );
        self::assertSame(
            3,
            $eventDates[1]['uid']
        );
        self::assertSame(
            5,
            $eventDates[2]['uid']
        );
    }
}
