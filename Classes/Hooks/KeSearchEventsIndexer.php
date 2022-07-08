<?php

namespace Extcode\CartEvents\Hooks;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Tpwd\KeSearch\Indexer\IndexerRunner;

class KeSearchEventsIndexer extends KeSearchIndexer
{
    protected $indexerKey = 'carteventsindexer';
    protected $indexerName = 'Cart Events Indexer';

    public function cartEventIndexer(array &$indexerConfig, IndexerRunner &$indexerObject): string
    {
        $eventIndexerName = 'Event Indexer "' . $indexerConfig['title'] . '"';

        $indexPids = $this->getPidList($indexerConfig);

        if ($indexPids === '') {
            $eventIndexerMessage = 'ERROR: No Storage Pids configured!';
        } else {
            $events = $this->getEventsToIndex($indexPids);

            if ($events) {
                foreach ($events as $event) {
                    $targetPid = $this->getTargetPidFormPage($event['uid']);

                    if ($targetPid) {
                        continue;
                    }

                    // compile the information which should go into the index
                    // the field names depend on the table you want to index!
                    $sku = strip_tags($event['sku']);
                    $title = strip_tags($event['title']);
                    $teaser = strip_tags($event['teaser']);
                    $description = strip_tags($event['description']);

                    $fullContent = $sku . "\n" . $title . "\n" . $teaser . "\n" . $description;
                    $tags = '#event#';
                    $additionalFields = [
                        'sortdate' => $event['crdate'],
                        'orig_uid' => $event['uid'],
                        'orig_pid' => $event['pid'],
                    ];

                    $params = '&tx_cartevents_events[event]=' . $event['uid'];

                    $targetPid = $this->getTargetPidFormCategory($event['category']);

                    if ($targetPid == 0) {
                        $targetPid = $indexerConfig['targetpid'];
                    }

                    $indexerObject->storeInIndex(
                        $indexerConfig['storagepid'], // storage PID
                        $title, // record title
                        'cartevent', // content type
                        $targetPid, // target PID: where is the single view?
                        $fullContent, // indexed content, includes the title (linebreak after title)
                        $tags, // tags for faceted search
                        $params, // typolink params for singleview
                        $teaser, // abstract; shown in result list if not empty
                        $event['sys_language_uid'], // language uid
                        $event['starttime'], // starttime
                        $event['endtime'], // endtime
                        $event['fe_group'], // fe_group
                        false, // debug only?
                        $additionalFields // additionalFields
                    );
                }
                $eventIndexerMessage = 'Success: ' . count($event) . ' event has been indexed.';
            } else {
                $eventIndexerMessage = 'Warning: No event found in configured Storage Pids.';
            }
        }

        return '<p><b>' . $eventIndexerName . '</b><br/><strong>' . $eventIndexerMessage . '</strong></p>';
    }
}
