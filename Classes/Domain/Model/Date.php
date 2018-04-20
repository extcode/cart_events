<?php
declare(strict_types=1);

namespace Extcode\CartEvents\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Date extends AbstractEntity
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
    public function getBegin() : \DateTime
    {
        return $this->begin;
    }

    /**
     * @param \DateTime $begin
     */
    public function setBegin(\DateTime $begin)
    {
        $this->begin = $begin;
    }

    /**
     * @return \DateTime
     */
    public function getEnd() : \DateTime
    {
        return $this->end;
    }

    /**
     * @param \DateTime $end
     */
    public function setEnd(\DateTime $end)
    {
        $this->end = $end;
    }

    /**
     * @return string
     */
    public function getNote(): string
    {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote(string $note)
    {
        $this->note = $note;
    }
}
