<?php
/**
 * @package RubenRomao_InvoicePdfAddCmsBlock
 * @author Ruben Romao <rubenromao@gmail.com>
 */
declare(strict_types=1);

namespace RubenRomao\InvoicePdfAddCmsBlock\Plugin\Magento\Sales\Model\Order\Pdf;

use Exception;
use Magento\Sales\Model\Order\Pdf\Invoice as CoreClass;
use Psr\Log\LoggerInterface;
use RubenRomao\InvoicePdfAddCmsBlock\Helper\Data;
use Zend_Pdf;

/**
 * Plugin to add the CMS block text to the bottom of the invoice PDF.
 */
class Invoice
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Invoice constructor.
     *
     * @param Data $helper
     * @param LoggerInterface $logger
     */
    public function __construct(
        Data $helper,
        LoggerInterface $logger
    ) {
        $this->helper = $helper;
        $this->logger = $logger;
    }

    /**
     * @param CoreClass $subject
     * @param Zend_Pdf $result
     * @return Zend_Pdf
     */
    public function afterGetPdf(CoreClass $subject, Zend_Pdf $result): Zend_Pdf
    {
        $page = end($result->pages);

        try {
            // add text to the bottom of the invoice
            $this->helper->drawFooter($subject, $page);
        } catch (Exception $e) {
            $this->logger->critical($e);
        }

        return $result;
    }
}
