<?php
class M_Asientos extends \DB\SQL\Mapper{
    public function __construct()
    {
        parent::__construct(\Base::instance()->get('DB'),'asientos');
    }
}