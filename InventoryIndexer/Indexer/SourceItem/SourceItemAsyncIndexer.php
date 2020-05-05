<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\InventoryIndexer\Indexer\SourceItem;

use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\InventoryIndexer\Indexer\Stock\StockIndexer;
use Magento\InventoryIndexer\Indexer\IndexerInterface;

/**
 * Source Asynchronous Item indexer
 *
 * @api
 */
class SourceItemAsyncIndexer implements SourceItemIndexerInterface
{
    public const TOPIC_SOURCE_ITEMS_INDEX = "inventory.indexer.sourceItem";

    /**
     * @var StockIndexer
     */
    private $stockIndexer;

    /**
     * @var PublisherInterface
     */
    private $publisher;

    /**
     * Source Asynchronous Item indexer constructor
     *
     * @param PublisherInterface $publisher
     * @param StockIndexer $stockIndexer
     */
    public function __construct(
        PublisherInterface $publisher,
        StockIndexer $stockIndexer
    ) {
        $this->publisher = $publisher;
        $this->stockIndexer = $stockIndexer;
    }

    /**
     * @return void
     */
    public function executeFull(): void
    {
        $this->stockIndexer->executeFull();
    }

    /**
     * Shedule Reindex of one item by id
     *
     * @param int $sourceItemId
     * @return void
     */
    public function executeRow(int $sourceItemId): void
    {
        $this->executeList([$sourceItemId]);
    }

    /**
     * Shedule Reindex of items list
     *
     * @param array $sourceItemIds
     * @return void
     */
    public function executeList(array $sourceItemIds): void
    {
        $this->publisher->publish(self::TOPIC_SOURCE_ITEMS_INDEX, $sourceItemIds);
    }
}
