<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Hooks;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\MathUtility;

class DatamapDataHandlerHook
{
    /**
     * @var int[][]
     */
    protected static $colPosCount = [];

    /**
     * @param DataHandler $dataHandler
     */
    public function processDatamap_beforeStart(DataHandler $dataHandler): void
    {
        $datamap = $dataHandler->datamap;
        if (empty($datamap['tt_content']) || $dataHandler->bypassAccessCheckForRecords) {
            return;
        }

        foreach ($datamap['tt_content'] as $id => $incomingFieldArray) {
            $incomingFieldArray['uid'] = $id;
            if (MathUtility::canBeInterpretedAsInteger($id)) {
                $incomingFieldArray = array_merge(BackendUtility::getRecord('tt_content', $id), $incomingFieldArray);
            }

            $pageId = (int)$incomingFieldArray['pid'];
            if ($pageId < 0) {
                $previousRecord = BackendUtility::getRecord('tt_content', abs($pageId), 'pid');
                $pageId = (int)$previousRecord['pid'];
                $incomingFieldArray['pid'] = $pageId;
            }

            $page = BackendUtility::getRecord('pages', abs($pageId));

            if (!$this->isAllowedTargetPage($incomingFieldArray['list_type'], $page['doktype'])) {
                unset($dataHandler->datamap['tt_content'][$id]);
                $dataHandler->log(
                    'tt_content',
                    $id,
                    1,
                    $pageId,
                    1,
                    'The record "%s" couldn\'t be saved due to disallowed value(s).',
                    23,
                    [
                        $incomingFieldArray[$GLOBALS['TCA']['tt_content']['ctrl']['label']],
                    ]
                );
            }
        }
    }

    public function processDatamap_afterAllOperations(DataHandler $dataHandler): void
    {
        $newIdUidArray = $dataHandler->substNEWwithIDs;

        if (empty($newIdUidArray)) {
            return;
        }
        foreach (self::$colPosCount as $identifier => $uidArray) {
            $intersect = array_intersect_key($newIdUidArray, $uidArray);
            if (empty($intersect)) {
                continue;
            }
            self::$colPosCount[$identifier] = array_replace(
                array_diff_key($uidArray, $newIdUidArray),
                array_combine($intersect, $intersect)
            );
        }
    }

    protected function isAllowedTargetPage($listType, $doktype)
    {
        if (empty($listType) || !str_starts_with((string)$listType, 'cartevents_')) {
            return true;
        }
        if (($doktype == 186) && ($listType === 'cartevents_singleevent')) {
            return true;
        }
        if (($doktype != 186) && ($listType === 'cartevents_events' || $listType === 'cartevents_eventdates' || $listType === 'cartevents_teaserevents')) {
            return true;
        }

        return false;
    }
}
