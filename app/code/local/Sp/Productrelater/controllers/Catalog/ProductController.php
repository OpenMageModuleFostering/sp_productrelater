<?php

/**
 *
 * NOTICE OF LICENSE
 *
 * @category   Sp
 * @package    Sp_Productrelater
 */

include_once "Mage/Adminhtml/controllers/Catalog/ProductController.php";
class Sp_Productrelater_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
{
    protected function _construct()
    {
        $this->setUsedModuleName('Sp_Productrelater');
    }
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('catalog/productrelater');
        $this->_addContent($this->getLayout()->createBlock('productrelater/catalog_product'));
        $this->renderLayout();
    }
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody($this->getLayout()->createBlock('productrelater/catalog_product_grid')->toHtml());
    }
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/products');
    }
    public function massUpdateProductsAction()
    {
        $productIds = array_unique($this->getRequest()->getParam('product'));
        if (is_array($productIds)) {
            try {
                $i = 0;
                foreach ($productIds as $itemId => $productId) {
                    $product         = Mage::getModel('catalog/product')->load($productId);
                    $productBefore   = $product;
                    $columnForUpdate = 'product_qtys';
                    $columnValuesForUpdate = $this->getRequest()->getParam($columnForUpdate);
                    $updateqty = (int)$columnValuesForUpdate[$itemId];
                      if($updateqty)
                      {   
                           $stockData = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product);
                           $stockData['qty'] = $updateqty;
                           $stockData['product_id'] = $productId;
                           $stockData->save();
                           $i++;
                      }
                }
                if($i == 0) {
                    $this->_getSession()->addSuccess($this->__('No record(s) were updated.', $i));
                } else {
                    $this->_getSession()->addSuccess($this->__('Total of %d record(s) were successfully updated.', $i));    
                }
                
            }
            catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        } else {
            $this->_getSession()->addError($this->__('Please select product(s)') . '. ' . $this->__('You should select checkboxes for each product row which should be updated. You can click on checkboxes or use CTRL+Click on product row which should be selected.'));
        }
        $this->_redirect('*/*/index');
    }
}
    