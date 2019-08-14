<?php

/**
 *
 * NOTICE OF LICENSE
 *
 * @category   Sp
 * @package    Sp_Productrelater
 */

class Sp_Productrelater_Block_Catalog_Product extends Mage_Adminhtml_Block_Catalog_Product
{
    public function __construct()
    {
        parent::__construct();
        $this->_headerText = Mage::helper('productrelater')->__('Mass Product Relater');
    }
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->setTemplate('sp/productrelater/catalog/product.phtml');
        $this->setChild('grid', $this->getLayout()->createBlock('productrelater/catalog_product_grid', 'product.productrelater'));
    }
}