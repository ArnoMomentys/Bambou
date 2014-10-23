<?php

/**
 * Personalisation du mapper  
 *
 */
class MyMapper extends DB\SQL\Mapper
{
    /**
     * Call the sanitize function
     * @see \DB\SQL\Mapper::insert()
     */
    public function insert()
    {
        $this->_cleanFields();
        
        parent::insert();
    }
    
    /**
     * Call the sanitize function 
     * @see \DB\SQL\Mapper::update()
     */
    public function update()
    {
        $this->_cleanFields();
        
        parent::update();
    }
    
    private function _cleanFields()
    {
        foreach ($this->fields as $key => $value)
        {
            $this->fields[$key]['value'] = Controller::sanitizeDatas($value['value']);
        }
    }
}
