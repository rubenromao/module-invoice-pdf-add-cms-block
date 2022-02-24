<?php
/**
 * @package RubenRomao_InvoicePdfAddCmsBlock
 * @author Ruben Romao <rubenromao@gmail.com>
 */
declare(strict_types=1);

namespace RubenRomao\InvoicePdfAddCmsBlock\Plugin\Magento\Sales\Model\Order\Pdf;

use Magento\Cms\Block\Block;
use Magento\Framework\View\LayoutInterface;
use Magento\Sales\Model\Order\Pdf\Invoice as CoreClass;
use Psr\Log\LoggerInterface;
use Zend_Pdf;
use Zend_Pdf_Page;

/**
 * Plugin to add the CMS block text to the bottom of the invoice PDF.
 * It receives the Invoice PDF after it is done processed and adds to the bottom
 * of its last page the text that is set in a CMS Block.
 */
class Invoice
{
    /**
     * @var LayoutInterface
     */
    private $layout;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Invoice Plugin constructor.
     *
     * @param LayoutInterface $layout
     * @param LoggerInterface $logger
     */
    public function __construct(
        LayoutInterface $layout,
        LoggerInterface $logger
    ) {
        $this->layout = $layout;
        $this->logger = $logger;
    }

    /**
     * This is the interceptor that adds the custom cms block
     *  text content to the bottom of the last page of the invoice PDF.
     *
     * @param CoreClass $subject
     * @param Zend_Pdf $result
     * @return Zend_Pdf
     */
    public function afterGetPdf(CoreClass $subject, Zend_Pdf $result): Zend_Pdf
    {
        try {
            /**
             * At this point the invoice has been generated having an array of pages.
             * Since I don't know if the PDF has one or more than one pages I'm going to
             *  take advantage of the PHP end() function to be sure that I'm placing the
             *  cms block in the last page only.
             * I don't like the use of PHP functions like this, but it is much better
             *  than using a preference to achieve this.
             */
            $lastPage = end($result->pages);

            // add the cms block to the bottom of the last page of the invoice pdf.
            $this->addCmsBlockContentToInvoicePdfBottom($subject, $lastPage);
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }

        return $result;
    }

    /**
     * Draw the cms block text in the invoice PDF.
     *
     * @param CoreClass $invoice
     * @param Zend_Pdf_Page $page
     * @return void
     */
    private function addCmsBlockContentToInvoicePdfBottom(CoreClass $invoice, Zend_Pdf_Page $page): void
    {
        try {
            // I could've added fancy stuff here, but I'm going to keep it simple.
            $page->drawText(
                $this->layout
                    ->createBlock(Block::class)
                    ->setBlockId('invoice_pdf_add_cms_block_with_text')->toHtml(),
                250,
                $invoice->y - 50,
                'UTF-8'
            );
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
    }
}
