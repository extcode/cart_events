<?php
declare(strict_types=1);

namespace Extcode\CartEvents\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

abstract class AbstractEventDate extends AbstractEntity
{
    /**
     * @var \DateTime
     */
    protected $begin;

    /**
     * @var \DateTime
     */
    protected $end;

    /**
     * Note
     *
     * @var string
     */
    protected $note = '';

    /**
     * @return \DateTime
     */
    public function getBegin() : ?\DateTime
    {
        return $this->begin;
    }

    /**
     * @param \DateTime $begin
     * @return CalendarEntry
     */
    public function setBegin(\DateTime $begin) : self
    {
        $this->begin = $begin;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEnd() : ?\DateTime
    {
        return $this->end;
    }

    /**
     * @param \DateTime $end
     * @return CalendarEntry
     */
    public function setEnd(\DateTime $end) : self
    {
        $this->end = $end;
        return $this;
    }

    /**
     * @return string
     */
    public function getNote() : string
    {
        return $this->note;
    }

    /**
     * @param string $note
     * @return CalendarEntry
     */
    public function setNote(string $note) : self
    {
        $this->note = $note;
        return $this;
    }
}
