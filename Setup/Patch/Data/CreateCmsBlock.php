<?php
/**
 * @package RubenRomao_InvoicePdfAddCmsBlock
 * @author Ruben Romao <rubenromao@gmail.com>
 */
declare(strict_types=1);

namespace RubenRomao\InvoicePdfAddCmsBlock\Setup\Patch\Data;

use Magento\Cms\Model\Block;
use Magento\Cms\Model\ResourceModel\Block\CollectionFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Store\Model\Store;
use Psr\Log\LoggerInterface;

/**
 * Create cms block to be added to the bottom of the invoice.
 */
class CreateCmsBlock implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @var Block
     */
    private $blockModel;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * CreateCmsBlock constructor.
     *
     * @param Block $blockModel
     * @param LoggerInterface $logger
     */
    public function __construct(
        Block $blockModel,
        LoggerInterface $logger
    ) {
        $this->blockModel = $blockModel;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->createCmsBlock();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '2.0.0';
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * This function creates the static block with the text.
     *
     * @return void
     */
    public function createCmsBlock(): void
    {
        $content = 'Thank you for buying from us!';
        $identifier = 'invoice_pdf_add_cms_block_with_text';
        $title = 'PDF Invoice Bottom Text';

        try {
            // set static block
            $model = $this->blockModel;
            $model->setIdentifier($identifier);
            $model->setStores([Store::DEFAULT_STORE_ID]);
            $model->setTitle($title);
            $model->setContent($content);
            $model->setIsActive(1);
            $model->save();
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
    }
}
