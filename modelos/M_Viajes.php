<?php
class M_Viajes extends \DB\SQL\Mapper{
    public function __construct()
    {
        parent::__construct(\Base::instance()->get('DB'),'horario_viaje');
    }
}