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
use Extcode\CartEvents\Domain\Repository\EventRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

#[CoversClass(EventRepository::class)]
class EventDateRepositoryTest extends FunctionalTestCase
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
    public function findNextReturnsNext(): never
    {
        self::markTestSkipped();
    }
}
