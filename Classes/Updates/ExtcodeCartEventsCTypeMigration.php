<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Updates;

use TYPO3\CMS\Core\Attribute\UpgradeWizard;
use TYPO3\CMS\Core\Upgrades\AbstractListTypeToCTypeUpdate;

#[UpgradeWizard('extcodeCartEventsCTypeMigration')]
final class ExtcodeCartEventsCTypeMigration extends AbstractListTypeToCTypeUpdate
{
    public function getTitle(): string
    {
        return 'Migrate "Extcode CartEvents" plugins to content elements.';
    }

    public function getDescription(): string
    {
        return 'The "Extcode CartEvents" plugins are now registered as content element. Update migrates existing records and backend user permissions.';
    }

    protected function getListTypeToCTypeMapping(): array
    {
        return [
            'cartevents' => 'cartevents_listevents'
        ];
    }
}
