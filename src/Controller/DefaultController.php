<?php

namespace App\Controller;

use App\Entity\Estados;
use App\Entity\Usuarios;
use App\Entity\Tareas;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;



class DefaultController extends AbstractController {

    /**
     * @Route ("/")
     * muesta la plantilla de la pag principal 
     */
    public function inicio() { 
        
        return $this->render('inicio.html.twig');
    }

    /**
     * @Route ("*")
     * la ruta no existe
     */
    public function noexiste() { 
        
        return $this->render('inicio.html.twig');
    }
      /**
     * @Route ("/login")
     * muesta la plantilla de la pag principal 
     */
    public function login() { 
        return $this->render('login.html.twig');
    }

    //log user
    
    /**
     * @Route ("/logout")
     */
    public function logout() {
        
        $session= new Session();
        $session->start();
        $session->remove('usuario');
        
        return $this->render('login.html.twig');


    }
    /**
         * @Route("/login_usuario"), name="login_usuario"
         * Funcion que comprueba si hay algun usuario con la contraseña introducida y si lo hay busca si el usuario coincide con el que ha introducido el usuario 
         */
        public function login_usuario(Request $request){
                $pag='login.html.twig';
            $em= $this->getDoctrine()->getRepository(Usuarios::class);
            $usuario_login= new Usuarios();
            $usuario= new Usuarios();

            if ($em->findOneByUsuario($request->get('usuario'))){
            $usuario_login= $em->findOneByUsuario($request->get('usuario'));
            if ($usuario_login->getPass() == md5($request->get('pass'))){
                if ($usuario_login->getEstado() == 'habilitado' || $usuario_login->getEstado() =='Admin') {
                $usuario=$usuario_login;
                // inicio sesion          
                $session = new Session();
                $session->start();
                $session->set('usuario', $usuario->getUsuario());
                $pag='inicio.html.twig';
                } else {
                    echo 'Lo sentimos. Este usuario no esta habilitado';
                }
              
            } else {
                echo "Usuario o contraseña no válidos";
            }
        } else {
            echo "Usuario o contraseña no válidos";
        }
            return $this->render($pag) ;
    }

    /**
     * @Route ("/estados")
     */
    public function ver_estados() {
        $con = $this->getDoctrine()->getManager();
         $db = $con->getRepository(Estados::class);
         $estados = $db->findAll();

        return $this->render('estados.html.twig', array (
            'estados' => $estados
        ));
    }

    /**
     * @Route ("/usuarios")
     */
    public function ver_usuarios() {
 
        $con = $this->getDoctrine()->getManager();
        $db = $con->getRepository(Usuarios::class);
        $usuarios = $db->findAll();

       return $this->render('usuarios.html.twig', array (
           'usuarios' => $usuarios
       ));
    }

    /**
     * @Route ("/tareas")
     */
    public function ver_tareas() {
        $con = $this->getDoctrine()->getManager();
         $db = $con->getRepository(Tareas::class);
         $tareas = $db->findAll();

         $dbuser = $con->getRepository(Usuarios::class);
         $usuarios = $dbuser->findAll();

         $dbstatus = $con->getRepository(Estados::class);
         $estados = $dbstatus->findAll();

        return $this->render('tareas.html.twig', array (
            'tareas' => $tareas,
            'usuarios' => $usuarios,
            'estados' => $estados
        ));
    }
    /**
     * @Route("/form_estado"), name="form_estado"
     */
    public function form_estado(){
        return $this->render('nuevoestado.html.twig');
    }

    /**
     * @Route("/form_usuario"), name="form_usuario"
     */
    public function form_usuario(){
        return $this->render('nuevousuario.html.twig');
    }
    /**
     * @Route("/form_tareas"), name="form_tareas"
     */
    public function form_tareas(){
        $con = $this->getDoctrine()->getManager();
         $db = $con->getRepository(Usuarios::class);
         $usuarios = $db->findAll();

        return $this->render('nuevatarea.html.twig', array (
            'usuarios' => $usuarios
        ));
    }

    /**
     * @Route("/nuevoestado"), name="nuevoestado"
     */
    public function nuevoestado(Request $request){
        
        $nombre=$request->get('name');
        $descripcion=$request->get('description');

        $estado = new Estados();
        $estado->setNombre($nombre);
        $estado->setDescripcion($descripcion);

        $em= $this->getDoctrine()->getManager();
        
        $em->persist($estado);
        $em->flush();
        return $this->ver_estados();
    }
    
    /**
     * @Route("/nuevousuario"), name="nuevousuario"
     */
    public function nuevousuario(Request $request){
        
        $nombre=$request->get('name');
        $apellido=$request->get('subname');
        $email=$request->get('email');
        $pass=$request->get('pass');
        $direccion=$request->get('address');

        $usuario = new Usuarios();
        $usuario->setNombre($nombre);
        $usuario->setApellido($apellido);
        $usuario->setEmail($email);
        $usuario->setPass(md5($pass));
        $usuario->setDireccion($direccion);
        $usuario->setEstado('habilitado');

        $em= $this->getDoctrine()->getManager();
        
        $em->persist($usuario);
        $em->flush();
        return $this->ver_usuarios();
    }



    /**
     * @Route("/nuevatarea"), name="nuevatarea"
     */
    public function nuevatarea(Request $request){
        
        $nombre=$request->get('name');
        $descripcion=$request->get('description');
        $id_usuario=$request->get('userid');
        
        $tarea = new Tareas();
        $tarea->setNombre($nombre);
        $tarea->setDescripcion($descripcion);
        $tarea->setIdUsuario($id_usuario);
        $tarea->setIdEstado('1');
        $fecha = date('Y-m-d');
        $tarea->setFechaCreacion($fecha);
        $em= $this->getDoctrine()->getManager();
        
        $em->persist($tarea);
        $em->flush();
        return $this->ver_tareas();
    }


//EDITAR

    /**
     * @Route("/form_edituser/{id}"), name="form_edituser/{id}"
     */
    public function form_edituser($id){

        // Preparo la conexion a entidad
        $con = $this->getDoctrine()->getManager();
        $estados= $con->getRepository(Usuarios::class);
        $dato=$estados->find($id);

     return $this->render('form_edituser.html.twig', array (
         'usuario' => $dato
     ));
 }
    /**
     * @Route("/editestado/{id}"), name="editestado/{id}"
     */
    public function editestado($id){

           // Preparo la conexion a entidad
           $con = $this->getDoctrine()->getManager();
           $estados= $con->getRepository(Estados::class);
           $dato=$estados->find($id);

        return $this->render('editestado.html.twig', array (
            'estado' => $dato
        ));
    }
    
    /**
     * @Route("/edittarea/{id}"), name="edittarea/{id}"
     */
    public function edittarea($id){

        // Preparo la conexion a entidad
        $con = $this->getDoctrine()->getManager();
        $tareas= $con->getRepository(Tareas::class);
        $dbestados= $con->getRepository(Estados::class);
        $estados = $dbestados->findAll();
        $dbusuarios= $con->getRepository(Usuarios::class);
        $usuarios = $dbusuarios->findAll();
        $dato=$tareas->find($id);

     return $this->render('edittarea.html.twig', array (
         'tarea' => $dato,
         'usuarios' => $usuarios,
         'estados' => $estados
     ));
 }

     /**
         * @Route ("editarestado/{id}")
         */
        public function editarestado(Request $request , $id){
            // Preparo la conexion a entidad
            $con = $this->getDoctrine()->getManager();
            $estadoOld= $con->getRepository(Estados::class);
             // modificar
            $estado = new Estados();
            $estado=$estadoOld->find($id);
            
            $estado->setNombre(''.$request->get('nombre'));
            $estado->setDescripcion(''.$request->get('descripcion'));

            $con->persist($estado);
            $con->flush();
            return $this->ver_estados();
        }

     /**
         * @Route ("editartarea/{id}")
         */
        public function editartarea(Request $request , $id){
            // Preparo la conexion a entidad
            $con = $this->getDoctrine()->getManager();
            $tareaOld= $con->getRepository(Tareas::class);
             // modificar
            $tarea = new Tareas();
            $tarea=$tareaOld->find($id);
            
            $tarea->setIdUsuario(''.$request->get('usuario'));
            $tarea->setIdEstado(''.$request->get('estado'));

            $con->persist($tarea);
            $con->flush();
            return $this->ver_tareas();
        }

        
        
     /**
         * @Route ("editarusuario/{id}")
         */
        public function editarusuario(Request $request , $id){
            // Preparo la conexion a entidad
            $con = $this->getDoctrine()->getManager();
            $usuarioOld= $con->getRepository(Usuarios::class);
             // modificar
            $usuario = new Usuarios();
            $usuario=$usuarioOld->find($id);
            
            $usuario->setNombre(''.$request->get('nombre'));
            $usuario->setApellido(''.$request->get('apellido'));
            $usuario->setEmail(''.$request->get('email'));
            $usuario->setPass(''.md5($request->get('pass')));
            $usuario->setDireccion(''.$request->get('direccion'));
            if ($request->get('habilitado')){
                $usuario->setEstado('habilitado');
            } elseif ($request->get('deshabilitado')){
                $usuario->setEstado('deshabilitado');
            } 
            $con->persist($usuario);
            $con->flush();
            return $this->ver_usuarios();
        }

        
     /**
         * @Route ("/buscarusuario")
         */
        public function buscarusuario(Request $request){
            $nombre=$request->get('buscar');
            echo $nombre;

             $con = $this->getDoctrine()->getManager();
             $usuarios= $con->getRepository(Usuarios::class);

             $datos=$usuarios->findByNombre($nombre);

            return $this->render('usuarios.html.twig', array (
                'usuarios' => $datos
            ));
        }
           
        
     /**
         * @Route ("/buscartareas")
         */
        public function buscartareas(Request $request){
            $nombre=$request->get('buscar');

             $con = $this->getDoctrine()->getManager();
             $tareas= $con->getRepository(Tareas::class);

             $datos=$tareas->findByNombre($nombre);

             $dbuser = $con->getRepository(Usuarios::class);
             $usuarios = $dbuser->findAll();
    
             $dbstatus = $con->getRepository(Estados::class);
             $estados = $dbstatus->findAll();
    
            return $this->render('tareas.html.twig', array (
                'tareas' => $datos,
                'usuarios' => $usuarios,
                'estados' => $estados
            ));
        }
}
?>