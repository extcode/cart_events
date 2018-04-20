<?php

namespace Extcode\CartEvents\Tests\Domain\Model;

class EventTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * Event
     *
     * @var \Extcode\CartEvents\Domain\Model\Event
     */
    protected $event = null;

    protected function setUp()
    {
        $this->event = new \Extcode\CartEvents\Domain\Model\Event();
    }

    protected function tearDown()
    {
        unset($this->event);
    }

    /**
     * @test
     */
    public function getTeaserReturnsInitialValueForTeaser()
    {
        $this->assertSame(
            '',
            $this->event->getTeaser()
        );
    }

    /**
     * @test
     */
    public function setTeaserForStringSetsTeaser()
    {
        $this->event->setTeaser('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'teaser',
            $this->event
        );
    }
}
