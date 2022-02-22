<?php

namespace Fisheyehq\Pdfinvoicetext\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Sales\Model\Order\Pdf\Invoice;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\View\LayoutInterface;


/**
 * Class Data
 * @package Fisheyehq\Pdfinvoicetext\Helper
 */
class Data extends AbstractHelper
{

    /**
     * @var Filesystem
     */
    protected $_fileSystem;

    /**
     * @var Filesystem\Directory\ReadInterface
     */
    protected $_rootDirectory;

    /**
     * @var LayoutInterface
     */
    protected $_layout;


    /**
     * Data constructor.
     * @param Context $context
     * @param Invoice $invoice
     * @param Filesystem $fileSystem
     */
    public function __construct(Context $context, Invoice $invoice, Filesystem $fileSystem, LayoutInterface $layout)
    {
        $this->_fileSystem = $fileSystem;
        $this->_rootDirectory = $fileSystem->getDirectoryRead(DirectoryList::ROOT);
        $this->_layout = $layout;
        parent::__construct($context);
    }


    /**
     * @param Invoice $invoice
     * @param \Zend_Pdf_Page $page
     * @param String $content
     */
    public function drawFooter(Invoice $invoice, \Zend_Pdf_Page $page)
    {
        try {
            $this->_setFontRegular($page, 20);
            $invoice->y -= 10;
            $page->setFillColor(new \Zend_Pdf_Color_RGB(0, 0, 0));
            $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.5));
            $page->drawLine(25, $invoice->y - 20, 570, $invoice->y - 20);
            $page->drawText($this->getFooterContent(), 180, $invoice->y - 50, 'UTF-8');
            $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));

            $invoice->y -= 20;
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }


    }

    /**
     * @param $object
     * @param int $size
     * @return \Zend_Pdf_Resource_Font
     * @throws \Zend_Pdf_Exception
     */
    protected function _setFontRegular(\Zend_Pdf_Page $object, $size = 7)
    {
        $font = \Zend_Pdf_Font::fontWithPath(
            $this->_rootDirectory->getAbsolutePath('lib/internal/LinLibertineFont/LinLibertine_Re-4.4.1.ttf')
        );
        $object->setFont($font, $size);
        return $font;
    }

    /**
     * @return string
     */
    public function getFooterContent()
    {
        return strip_tags($this->_layout->createBlock('Magento\Cms\Block\Block')->setBlockId('pdf_invoice_text')->toHtml());
    }
}