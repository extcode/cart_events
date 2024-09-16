<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Tests\Unit\Domain\Model;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\CartEvents\Domain\Model\Event;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class EventTest extends UnitTestCase
{
    /**
     * Event
     *
     * @var Event
     */
    protected $event;

    protected function setUp(): void
    {
        $this->event = new Event();
    }

    protected function tearDown(): void
    {
        unset($this->event);
    }

    /**
     * @test
     */
    public function getTeaserReturnsInitialValueForTeaser(): void
    {
        self::assertSame(
            '',
            $this->event->getTeaser()
        );
    }

    /**
     * @test
     */
    public function setTeaserForStringSetsTeaser(): void
    {
        $this->event->setTeaser('Conceived at T3CON10');

        self::assertSame(
            'Conceived at T3CON10',
            $this->event->getTeaser()
        );
    }
}
