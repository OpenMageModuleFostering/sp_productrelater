<?php

/**
 *
 * NOTICE OF LICENSE
 *
 * @category   Sp
 * @package    Sp_Productrelater
 */

class Sp_Productrelater_Block_Catalog_Product_Grid extends Sp_Productrelater_Block_Widget_Grid
{
    protected static $columnType = array('id' => array('type' => 'number'), 'product' => array('type' => 'checkbox'), 'name' => array('type' => 'text'), 'type_id' => array('type' => 'text'), 'attribute_set_id' => array('type' => 'text'), 'sku' => array('type' => 'text'), 'price' => array('type' => 'text'), 'qty' => array('type' => 'text'), 'is_in_stock' => array('type' => 'text'), 'visibility' => array('type' => 'text'), 'status' => array('type' => 'text'), 'websites' => array('type' => 'text'), 'product_qtys' => array('type' => 'input'));
    public function __construct()
    {
        parent::__construct();
        $this->setId('productGrid');
        $this->prepareDefaults();
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setTemplate('sp/productrelater/catalog/product/grid.phtml');
        $this->setMassactionBlockName('productrelater/widget_grid_massaction');
    }
    private function prepareDefaults()
    {
        $this->setDefaultLimit(20);
        $this->setDefaultPage(1);
        $this->setDefaultSort('id');
        $this->setDefaultDir('desc');
    }
    protected function _prepareCollection()
    {
        $collection = $this->getCollection();
        $collection = !$collection ? Mage::getModel('catalog/product')->getCollection() : $collection;
        $store      = $this->_getStore();
        $collection->joinField('qty', 'cataloginventory/stock_item', 'qty', 'product_id=entity_id', '{{table}}.stock_id=1', 'left');
        $collection->groupByAttribute('entity_id');
        if ($store->getId()) {
            $collection->addStoreFilter($store);
            $collection->joinAttribute('custom_name', 'catalog_product/name', 'entity_id', null, 'inner', $store->getId());
            $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner', $store->getId());
            $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner', $store->getId());
            $collection->joinAttribute('price', 'catalog_product/price', 'entity_id', null, 'left', $store->getId());
        } else {
            $collection->addAttributeToSelect('price');
            $collection->addAttributeToSelect('status');
            $collection->addAttributeToSelect('visibility');
        }
        foreach (self::$columnType as $col => $true) {
                $collection->addAttributeToSelect($col);
        }
        $this->setCollection($collection);
        parent::_prepareCollection();
        $collection->addWebsiteNamesToResult();
        return $this;
    }
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }
    protected function _addColumnFilterToCollection($column)
    {
        if ($this->getCollection()) {
            if ($column->getId() == 'websites') {
                $this->getCollection()->joinField('websites', 'catalog/product_website', 'website_id', 'product_id=entity_id', null, 'left');
            }
        }
        return parent::_addColumnFilterToCollection($column);
    }
    
    public function _applyMyFilter($column)
    {
    
    }
    
    protected function _prepareColumns()
    {
        $store = $this->_getStore();
        $this->addColumn('id', array(
            'header' => Mage::helper('catalog')->__('ID'),
            'width' => '50px',
            'type' => 'number',
            'index' => 'entity_id'
        ));
        $this->addColumn('name', array(
            'header' => Mage::helper('catalog')->__('Name'),
            'name' => 'pu_name[]',
            'index' => 'name'
        ));
        $store = $this->_getStore();
        if ($store->getId()) {
            $this->addColumn('custom_name', array(
                'header' => Mage::helper('catalog')->__('Name In %s', $store->getName()),
                'index' => 'custom_name',
                'width' => '150px'
            ));
        }
        $this->addColumn('sku', array(
            'header' => Mage::helper('catalog')->__('SKU'),
            'width' => '80px',
            'index' => 'sku',
            'name' => 'pu_sku[]'
        ));
        $this->addColumn('price', array(
            'header' => Mage::helper('catalog')->__('Price'),
            'type' => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'index' => 'price',
            'name' => 'pu_price[]'
        ));
        $this->addColumn('qty', array(
            'header' => Mage::helper('catalog')->__('Qty'),
            'width' => '100px',
            'type' => 'number',
            'index' => 'qty',
            'name' => 'pu_qty[]'
        ));
        $this->addColumn('product_qtys', array(
            'type' => 'input',
            'index' => 'product_qtys',
            'width' => '80px',
            'filter'    => false,
            'filter_condition_callback' => array(
                $this,
                '_applyMyFilter'
            ),
            'renderer' => 'Sp_Productrelater_Block_Widget_Grid_Column_Renderer_Number',
            'header' => Mage::helper('catalog')->__('Update Qty')
        ));
        $this->addColumn('visibility', array(
            'header' => Mage::helper('catalog')->__('Visibility'),
            'width' => '70px',
            'index' => 'visibility',
            'type' => 'options',
            'options' => Mage::getModel('catalog/product_visibility')->getOptionArray()
        ));
        $this->addColumn('status', array(
            'header' => Mage::helper('catalog')->__('Status'),
            'width' => '70px',
            'index' => 'status',
            'type' => 'options',
            'options' => Mage::getSingleton('catalog/product_status')->getOptionArray()
        ));
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('websites', array(
                'header' => Mage::helper('catalog')->__('Websites'),
                'width' => '100px',
                'sortable' => false,
                'index' => 'websites',
                'type' => 'options',
                'options' => Mage::getModel('core/website')->getCollection()->toOptionHash()
            ));
        }
      
        $this->addColumn('action', array(
            'header' => Mage::helper('catalog')->__('Action'),
            'width' => '50px',
            'type' => 'action',
            'getter' => 'getId',
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'actions' => array(
                array(
                    'caption' => Mage::helper('catalog')->__('Edit'),
                    'id' => "editlink",
                    'url' => array(
                        'base' => 'adminhtml/*/edit',
                        'params' => array(
                            'store' => $this->getRequest()->getParam('store')
                        )
                    ),
                    'field' => 'id'
                )
            )
        ));
        $this->addRssList('rss/catalog/notifystock', Mage::helper('catalog')->__('Notify Low Stock RSS'));
        $this->setDestElementId('edit_form');
        return parent::_prepareColumns();
    }
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('product');
        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('catalog')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('catalog')->__('Are you sure?')
        ));
        $statuses = Mage::getSingleton('catalog/product_status')->getOptionArray();
        array_unshift($statuses, array(
            'label' => '',
            'value' => ''
        ));
        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('catalog')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array(
                '_current' => true
            )),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('catalog')->__('Status'),
                    'values' => $statuses
                )
            )
        ));
        $this->getMassactionBlock()->addItem('attributes', array(
            'label' => Mage::helper('catalog')->__('Update attributes'),
            'url' => $this->getUrl('*/catalog_product_action_attribute/edit', array(
                '_current' => true
            ))
        ));
        
        $this->getMassactionBlock()->addItem('save', array(
            'label' => Mage::helper('catalog')->__('Update Qty'),
            'url' => $this->getUrl('*/*/massUpdateProducts', array(
                '_current' => true
            )),
            'fields' => array(
                0 => 'product',
                1 => 'product_qtys'
            )
        ));
       
        return $this;
    }
    public function getRowUrl($row)
    {
        return $this->getUrl('adminhtml/catalog_product/edit', array(
            'store' => $this->getRequest()->getParam('store'),
            'id' => $row->getId()
        ));
    }
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array(
            '_current' => true
        ));
    }
}
    