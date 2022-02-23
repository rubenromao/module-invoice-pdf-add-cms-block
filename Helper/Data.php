<?php
/**
 * @package RubenRomao_InvoicePdfAddCmsBlock
 * @author Ruben Romao <rubenromao@gmail.com>
 */
declare(strict_types=1);

namespace RubenRomao\InvoicePdfAddCmsBlock\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Filesystem;
use Magento\Framework\View\LayoutInterface;
use Magento\Sales\Model\Order\Pdf\Invoice;
use Psr\Log\LoggerInterface;

/**
 * Create the PDF footer layout
 */
class Data extends AbstractHelper
{
    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * @var Filesystem\Directory\ReadInterface
     */
    private $rootDirectory;

    /**
     * @var LayoutInterface
     */
    private $layout;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param Invoice $invoice
     * @param Filesystem $fileSystem
     * @param LayoutInterface $layout
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        Invoice $invoice,
        Filesystem $fileSystem,
        LayoutInterface $layout,
        LoggerInterface $logger
    ) {
        $this->fileSystem = $fileSystem;
        $this->rootDirectory = $fileSystem->getDirectoryRead(DirectoryList::ROOT);
        $this->layout = $layout;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * @param Invoice $invoice
     * @param Zend_Pdf_Page $page
     * @return void
     */
    public function drawFooter(Invoice $invoice, \Zend_Pdf_Page $page): void
    {
        try {
            $invoice->y -= 10;
            $page->setFillColor(new \Zend_Pdf_Color_RGB(0, 0, 0));
            $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.5));
            $page->drawLine(25, $invoice->y - 20, 570, $invoice->y - 20);
            $page->drawText($this->getFooterContent(), 180, $invoice->y - 50, 'UTF-8');
            $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));

            $invoice->y -= 20;
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
    }

    /**
     * @return string
     */
    public function getFooterContent(): string
    {
        return $this->layout
            ->createBlock('Magento\Cms\Block\Block')
            ->setBlockId('invoice_pdf_add_cms_block_with_text')->toHtml();
    }
}
