<?php
class Reservas_Ctrl
{

    public $M_Reserva = null;
    public $M_Asiento = null;

    public function __construct()
    {
        $this->M_Reserva = new M_Reservas();
        $this->M_Asiento = new M_Asientos();
    }

    //inicio newReserva
    public function nuevoReserva($f3)
    {
        $asientos = [];
        $asientos = $f3->get('POST.idAsientos');
        $idHorario = $f3->get('POST.idHorario');
        $idUsuario = $f3->get('POST.idUsuario');
        $tarifa = $f3->get('POST.tarifa');
        $fecha = $f3->get('POST.fecha');
        $idBus = $f3->get('POST.idBus');


        if (count($asientos) < 0) {
            echo json_encode([
                'estado' => true,
                'mensaje' => 'Selecciona Asientos'
            ]);
        } else {

            foreach ($asientos as $valor) {
              //$this->M_Asiento = new M_Asientos();


                //echo $valor['idAsiento'];
                $this->M_Asiento->load(['id_bus=? AND detalle=?', $idBus, $valor['idAsiento']]);
                if ($this->M_Asiento->loaded() > 0) {
                    $this->M_Asiento->set('estado', 'O');
                    $this->M_Asiento->save();
                }
                $this->M_Reserva = new M_Reservas();
                $this->M_Reserva->set('id_Horario', $idHorario);
                $this->M_Reserva->set('id_Usuario', $idUsuario);
                $this->M_Reserva->set('monto', $tarifa);
                $this->M_Reserva->set('fecha', $fecha);
                $this->M_Reserva->set('id_asiento', $valor['idAsiento']);
                $this->M_Reserva->save(); 
            }
        }
    }



    //funcion de consulta de reservas por ID Usuario

    public function listarReservas($f3){
        $id_usuario=$f3->get('PARAMS.id_usuario');
        $cadenaSql = "SELECT COUNT(r.id_asiento) AS numeroAsientos, hv.fecha, hv.hora,b.disco as discoBus,r.id_Reserva as idReserva, hv.id_viaje FROM reserva r 
                    INNER JOIN horario_viaje hv on hv.id_viaje=r.id_Horario 
                    INNER JOIN buses b on b.id_bus=hv.id_bus WHERE id_Usuario='".$id_usuario."' 
                    GROUP BY r.id_Horario;";
        $items=$f3->DB->exec($cadenaSql);
        echo json_encode([
           'cantidad'=>count($items),
           'data'=>[
               'info'=> $items
           ]
        ]); 
      }

      public function infoReserva($f3){
        $id_usuario=$f3->get('PARAMS.id_usuario');
        $id_viaje=$f3->get('PARAMS.id_viaje');
        $cadenaSql = "  SELECT cp.detalle as compania, cp.alias, b.disco as disco,c.detalle as destino,hv.fecha,hv.hora  FROM reserva rv
                        INNER JOIN horario_viaje hv on hv.id_viaje=rv.id_Horario
                        INNER JOIN buses b on b.id_bus=hv.id_bus
                        INNER JOIN companias cp on cp.id_compania=b.id_Compania
                        INNER JOIN ciudades c on c.id_ciudad=hv.id_destino 
                        WHERE rv.id_Usuario=".$id_usuario." AND hv.id_viaje=".$id_viaje."
                        GROUP BY rv.id_Usuario";
        $items=$f3->DB->exec($cadenaSql);
        echo json_encode([
           'cantidad'=>count($items),
           'data'=>[
               'info'=> $items
           ]
        ]); 
      }


      public function infoAsientos($f3){
        $id_usuario=$f3->get('PARAMS.id_usuario');
        $id_horario=$f3->get('PARAMS.id_horario');
        $cadenaSql = "SELECT * FROM reserva rv WHERE rv.id_Horario='".$id_horario."'AND rv.id_Usuario='".$id_usuario."'";
        $items=$f3->DB->exec($cadenaSql);
        echo json_encode([
           'cantidad'=>count($items),
           'data'=>[
               'info'=> $items
           ]
        ]); 
      }





    //conuslta mis reservas 
    /* SELECT COUNT(r.id_asiento) AS numeroAsientos, r.id_Usuario FROM reserva r WHERE id_Usuario=1 AND id_Horario=1 GROUP BY id_Usuario; */ //global informacion
    /* SELECT rv.id_asiento FROM reserva rv WHERE rv.id_Horario=1 AND rv.id_Usuario=1; */ //asientos ocupados por persona

    /* SELECT rv.id_Reserva,rv.fecha,b.disco,ciu.nombre_terminal as t_o, ciud.nombre_terminal as t_d 
    FROM reserva rv INNER JOIN horario_viaje hv ON hv.id_viaje=rv.id_Horario 
    INNER JOIN buses b ON b.id_bus=hv.id_bus INNER JOIN ciudades ciu ON ciu.id_ciudad=hv.id_origen 
    INNER JOIN ciudades ciud ON ciud.id_ciudad=hv.id_destino 
    INNER JOIN usuarios u on U.id_usuario=rv.id_Usuario GROUP BY rv.id_Usuario; */



} 