<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Domain\Model;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

abstract class AbstractEventDate extends AbstractEntity
{
    protected ?\DateTime $begin = null;

    protected ?\DateTime $end = null;

    protected string $note = '';

    public function getBegin(): ?\DateTime
    {
        return $this->begin;
    }

    public function setBegin(\DateTime $begin): void
    {
        $this->begin = $begin;
    }

    public function getEnd(): ?\DateTime
    {
        return $this->end;
    }

    public function setEnd(\DateTime $end): void
    {
        $this->end = $end;
    }

    public function getNote(): string
    {
        return $this->note;
    }

    public function setNote(string $note): void
    {
        $this->note = $note;
    }
}
