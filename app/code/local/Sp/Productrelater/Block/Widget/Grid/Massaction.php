<?php

/**
 *
 * NOTICE OF LICENSE
 *
 * @category   Sp
 * @package    Sp_Productrelater
 */

class Sp_Productrelater_Block_Widget_Grid_Massaction extends Mage_Adminhtml_Block_Widget_Grid_Massaction
{
    public function getJsObjectName()
    {
        return $this->getHtmlId() . 'JsObjectIKSProductrelater';
    }
    public function getJavaScript()
    {
        return " var {$this->getJsObjectName()} = new varienGridMassactionSpProductrelater('{$this->getHtmlId()}', " . "{$this->getGridJsObjectName()}, '{$this->getSelectedJson()}'" . ", '{$this->getFormFieldNameInternal()}', '{$this->getFormFieldName()}');" . "{$this->getJsObjectName()}.setItems({$this->getItemsJson()}); " . "{$this->getJsObjectName()}.setGridIds('{$this->getGridIdsJson()}');" . ($this->getUseAjax() ? "{$this->getJsObjectName()}.setUseAjax(true);" : '') . ($this->getUseSelectAll() ? "{$this->getJsObjectName()}.setUseSelectAll(true);" : '') . "{$this->getJsObjectName()}.errorText = '{$this->getErrorText()}';";
    }
}
