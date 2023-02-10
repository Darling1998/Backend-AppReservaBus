<?php
class Usuarios_Ctrl{

public $M_Usuario=null;

  public function __construct()
  {
    $this->M_Usuario=new M_Usuarios(); 
  }

//espacio para programar mis funciones
  public function login($f3){
    //obtener la cedula y la contraseña (crear 2 variables para cedula y clave)
    $cedula=$f3->get('POST.cedula');
    $clave=$f3->get('POST.password');
    $estado="A";
    $msg="";
    $items=array();
    $id=0;
    $this->M_Usuario->load(['cedula=? AND password=?',$cedula,$clave]);
    if($this->M_Usuario->loaded()>0){
        $id=$this->M_Usuario->get('id_usuario');
        $msg="Usuario encontrado";
        $items=$this->M_Usuario->cast();
    }else{
        $id=0;
        $msg="No existe el Usuario con estos datos";
    
    }
    echo json_encode([
    'mensaje'=> $msg,
    'id'=>$id,
    'info'=>['items'=>$items],
    ]);
  
  }


   //inicio newUsuario
   public function nuevoUsuario($f3){
    $id=0;
    $msg="";
    $usuario=new M_Usuarios();
    $usuario->load(['cedula=?',$f3->get('POST.cedula')]);
        if($usuario->loaded()>0){
            $msg="Cédula ya existe registrada";
        }else{
            //setear el resto de campos
            $this->M_Usuario->set('cedula',$f3->get('POST.cedula'));
            $this->M_Usuario->set('nombre',$f3->get('POST.nombre'));
            $this->M_Usuario->set('apellido',$f3->get('POST.apellido'));
            $this->M_Usuario->set('password',$f3->get('POST.password'));
            //grabar
            $this->M_Usuario->save();
            $id=$this->M_Usuario->get('id_usuario');
            $msg="Usuario creado con éxito";
        }
        echo json_encode([
            'mensaje'=>$msg,
            'info'=>[
                'id'=>$id
            ]
        ]);
    }


    public function actualizarUsuario($f3){
        $id_usuario=$f3->get('PARAMS.id_usuario');
        $cadenaSql = "SELECT * FROM usuarios WHERE id_usuario='".$id_usuario."'";
        $items=$f3->DB->exec($cadenaSql);
        echo json_encode([
           'cantidad'=>count($items),
           'data'=>[
               'info'=> $items
           ]
        ]); 

    }

    public function actualizarInfoUsuario($f3){
        $id_usuario=$f3->get('POST.id_usuario');
        $nombre=$f3->get('POST.nombre');
        $apellido=$f3->get('POST.apellido');
        $cedula=$f3->get('POST.cedula');
        $password=$f3->get('POST.password');
        
        $this->M_Usuario->load(['id_usuario = ?', $id_usuario]);
        
        if($this->M_Usuario->loaded() > 0) {  
            $this->M_Usuario->set('cedula', $cedula);
            $this->M_Usuario->set('nombre', $nombre);
            $this->M_Usuario->set('apellido', $apellido);
            $this->M_Usuario->set('password', $password);
            $this->M_Usuario->save(); 
            $msj = "Perfil Actualizado";
        }

        echo json_encode([
            'msj'=>$msj
        ]);  
    }
  

}//fin Clase