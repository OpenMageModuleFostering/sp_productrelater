<?php

/**
 *
 * NOTICE OF LICENSE
 *
 * @category   Sp
 * @package    Sp_Productrelater
 */

class Sp_Productrelater_Block_Widget_Grid_Column_Renderer_Number extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Number
{
    public function render(Varien_Object $row)
    {
        if($row->getTypeId() == 'simple'){
            $html = '<input type="text" ';
            $html .= 'name="' . $this->getColumn()->getId() . '" ';
            $html .= 'value="' . parent::_getValue($row) . '"';
            $html .= 'class="input-text ' . $this->getColumn()->getInlineCss() . '"/>';
            return $html;    
        }
        
    }
}
