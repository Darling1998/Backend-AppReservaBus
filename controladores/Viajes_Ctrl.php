<?php
class Viajes_Ctrl
{

  public $Viaje = null;

  public function __construct()
  {
    $this->Viaje = new M_Viajes();
  }

  public function getDisponibilidad($f3)
  {
    $origen = $f3->get('POST.origen');
    $destino = $f3->get('POST.destino');
    $fecha = $f3->get('POST.fecha');
    $hora = $f3->get('POST.hora');
    $horaA = $f3->get('POST.horaA');

    $cadenaSql = "SELECT b.disco as disco, b.capacidad as capacidad,b.id_bus as id_bus, comp.alias as alias,comp.imagen, fecha,hora,tarifa,hv.id_viaje,ciu.nombre_terminal as t_salida, ciud.nombre_terminal as t_llegada FROM horario_viaje hv 
    INNER JOIN buses b on b.id_bus=hv.id_bus 
    INNER JOIN ciudades ciu on hv.id_origen=ciu.id_ciudad
    INNER JOIN ciudades ciud on hv.id_destino=ciud.id_ciudad 
    INNER JOIN companias comp ON b.id_Compania=comp.id_compania 
    WHERE hv.id_origen='".$origen."' AND hv.id_destino='".$destino."' AND hv.fecha='".$fecha."' AND (hv.hora <= '".$horaA."' and hv.hora>='".$hora."' );";

    $items = $f3->DB->exec($cadenaSql);
    echo json_encode([
      'cantidad' => count($items),
      'data' => [
        'info' => $items
      ]
    ]);
  }

  //funcion para llenar los select de ciudades
  public function getCiudades($f3){
    $cadenaSql='';
    $cadenaSql= $cadenaSql.'SELECT * FROM ciudades';
    $items=$f3->DB->exec($cadenaSql);
    echo json_encode([
       'cantidad'=>count($items),
       'data'=>[
           'info'=> $items
       ]
    ]); 
  }

  public function listarAsientos($f3){
    $id_bus=$f3->get('PARAMS.id_bus');
    $cadenaSql = "SELECT * FROM asientos WHERE id_bus='".$id_bus."'";
    $items=$f3->DB->exec($cadenaSql);
    echo json_encode([
       'cantidad'=>count($items),
       'data'=>[
           'info'=> $items
       ]
    ]); 
  }

}