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
use Extcode\CartEvents\Domain\Model\Dto\EventDemand;
use Extcode\CartEvents\Domain\Repository\EventRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

#[CoversClass(EventRepository::class)]
class EventRepositoryTest extends FunctionalTestCase
{
    use TestingFramework;

    private EventRepository $eventRepository;

    public function setUp(): void
    {
        $this->testExtensionsToLoad[] = 'extcode/cart';
        $this->testExtensionsToLoad[] = 'extcode/cart-events';

        parent::setUp();

        $this->eventRepository = GeneralUtility::makeInstance(EventRepository::class);

        $this->importPHPDataSet(__DIR__ . '/../../../Fixtures/PagesDatabase.php');
        $this->importPHPDataSet(__DIR__ . '/../../../Fixtures/EventsDatabase.php');
    }

    #[Test]
    public function findByUidsWithoutLimit(): void
    {
        $uids = '1,2,3,4';
        $events = $this->eventRepository->findByUids($uids);

        self::assertCount(
            4,
            $events
        );
    }

    #[Test]
    public function findByUidsWithLimit(): void
    {
        $uids = '1,2,3,4';
        $events = $this->eventRepository->findByUids($uids, 2);

        self::assertCount(
            2,
            $events
        );
    }

    #[Test]
    public function findDemandedByNewEventDemand(): void
    {
        $eventDemand = new EventDemand();

        $querySettings = GeneralUtility::makeInstance(QuerySettingsInterface::class);
        $querySettings->setStoragePageIds([7]);
        $this->eventRepository->setDefaultQuerySettings($querySettings);
        $events = $this->eventRepository->findDemanded($eventDemand);

        self::assertCount(
            3,
            $events
        );
    }

    #[Test]
    public function findDemandedByNewEventDemandWithLimit(): void
    {
        $eventDemand = new EventDemand();
        $eventDemand->setLimit(2);

        $querySettings = GeneralUtility::makeInstance(QuerySettingsInterface::class);
        $querySettings->setStoragePageIds([7]);
        $this->eventRepository->setDefaultQuerySettings($querySettings);
        $events = $this->eventRepository->findDemanded($eventDemand);

        self::assertCount(
            2,
            $events
        );
    }

    #[Test]
    public function findDemandedWithGivenSkuReturnsEvents(): void
    {
        $eventDemand = new EventDemand();
        $eventDemand->setSku('event-1');

        $querySettings = GeneralUtility::makeInstance(QuerySettingsInterface::class);
        $querySettings->setStoragePageIds([7]);
        $this->eventRepository->setDefaultQuerySettings($querySettings);
        $events = $this->eventRepository->findDemanded($eventDemand);

        self::assertCount(
            1,
            $events
        );

        self::assertSame(
            1,
            $events->getFirst()->getUid()
        );
    }

    #[Test]
    public function findDemandedWithGivenTitleReturnsEvents(): void
    {
        $eventDemand = new EventDemand();
        $eventDemand->setTitle('Event 3');

        $querySettings = GeneralUtility::makeInstance(QuerySettingsInterface::class);
        $querySettings->setStoragePageIds([7]);
        $this->eventRepository->setDefaultQuerySettings($querySettings);
        $events = $this->eventRepository->findDemanded($eventDemand);

        self::assertCount(
            1,
            $events
        );

        self::assertSame(
            3,
            $events->getFirst()->getUid()
        );
    }
}
