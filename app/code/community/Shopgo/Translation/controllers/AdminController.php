<?php
/**
 * Admin menu
 *
 * @category   Shopgo
 * @package    Shopgo_Translation
*/


class Shopgo_Translation_AdminController extends Mage_Adminhtml_Controller_Action
{
  public function indexAction()
  {
    $this->loadLayout();
    $this->_setActiveMenu('shopgo_translation');  

    $block = $this->getLayout()
    ->createBlock('core/text', 'shopgo_translation')
    ->setText('<iframe src="' . Mage::getBaseUrl() . 'shopgo_translation' . '" height="910" frameborder="0" scrolling="no" style="margin:0px auto;display:block;width: 100%;"></iframe>');

    $this->_addContent($block);
    $this->renderLayout();
  }

  protected function _isAllowed()
  {
    return Mage::getSingleton('admin/session')->isAllowed('shopgo_translation');
  }
}
