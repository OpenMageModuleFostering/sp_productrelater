<?php

/**
 *
 * NOTICE OF LICENSE
 *
 * @category   Sp
 * @package    Sp_Productrelater
 */

class Sp_Productrelater_Block_Widget_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function getJsObjectName()
    {
        return $this->getId() . 'JsObjectIKSProductrelater';
    }
}
