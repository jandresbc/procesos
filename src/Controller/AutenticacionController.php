<?php

namespace App\Controller;

use App\Entity\Usuarios;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\ParameterBag;

class AutenticacionController extends AbstractController
{
    private $mensajes = null;
    private $_tokenpass = "QWe89..//&&";

    public function index(Request $request)
    {
        //Instancias de los objetos a utilizar.
        $usuario = new Usuarios();
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        //Se crea el formulario de login
        $formLogin = $this->createFormBuilder($usuario)
        ->add('identificacion',null,array('label'=>'Identificación: ','attr'=>array('class'=>'form-control','autofocus'=>true)))
        ->add('contrasena',PasswordType::class,array('label'=>'Clave de Ingreso: ','attr'=>array('class'=>'form-control')))
        ->getForm();

        $formLogin->handleRequest($request);

        if($formLogin->isSubmitted() && $formLogin->isValid()){
            //Consultar información del Usuario que se desea logear.
            $infUs = $this->getInfoUser($request->request->get('form')['identificacion']);

            if(count($infUs) > 0){
              //Encripta el password recibido por el request
              //desde el formulario de inicio de sesion.
              $password = $this->encryptPass($request->request->get('form')['contrasena']);

              if(trim($password) === trim($infUs[0]->getContrasena()) && trim($usuario->getIdentificacion()) === trim($infUs[0]->getIdentificacion())){

                  $session = new Session();
                  $session->invalidate();//Elimina la session si esta esta activa.
                  $session->start();//Inicia la sesion.

                  //Setear las variables de sesion para la aplicación.
                  $session->set('auth','1');
                  $session->set('identificacion',$infUs[0]->getIdentificacion());
                  $session->set('idUsuario',$infUs[0]->getIdUsuario());
                  $session->set('nombreUsuario',$infUs[0]->getNombreCompleto());
                  
                  $session->set('urllogout',$this->generateUrl('logout',
                    array(
                      'usuario'=>$infUs[0]->getIdentificacion()
                  )));

                  //Actualiza la base de datos en la tabla usuario en el campo auth,
                  //para identificar que usuario esta autenticado en el sistema.
                  $us = $em->getRepository('App:Usuarios')->findBy(
                    array(
                      'identificacion'=>$infUs[0]->getIdentificacion()
                    )
                  );
                  //print_r($us);
                  $us[0]->setAuth('1');
                  //persistimos el objeto.
                  //$em->persist($us[0]);
                  //Actualizamos la base de datos.
                  $em->flush($us[0]);

                  return $this->redirectToRoute('procesos_index');
              }else{
                $this->mensajes = "Ocurrio un error al iniciar sesión en el sistema. Verifique su ID y contraseña e intente nuevamente.";
              }
            }else{
              $this->mensajes = "Hay un error al iniciar sesion. Por favor revise su ID y contraseña y vuelva a intentarlo. Si sigue sin poder Iniciar Sesión es posible que su usuario este Inactivo, para resolver este problema contacte al Administrador del Sistema.";
            }
        }

        if($session->get("auth") == 1){//Tiene una session iniciada.
          //Redirege al usuario al dashboard.
          return $this->redirectToRoute('procesos_index', array());
        }else{
          return $this->render('auth/auth.html.twig', array(
              'formlogin' => $formLogin->createView(),
              'mensajes_sistema' => $this->mensajes,
              'login'=>true,
          ));
        }
    }

    private function getInfoUser($identificacion){
      $session = new Session();
      $em = $this->getDoctrine()->getManager();
      //Obtiene la información del usuario que sea un usuario Inactivo
      //y que el funcionario este activo para poder obtener su información.

      //JOIN AppBundle:EmpresasSedesAgencias ESA WITH U.idEmpresaSedeAgencia = ESA.idEmpresaSedeAgencia
      //JOIN AppBundle:Empresas E WITH ESA.idEmpresa = E.idEmpresa
      $query = $em->createQuery(
        "SELECT U
        FROM App:Usuarios U
        WHERE U.identificacion = :ident
        AND U.activo <> 0
        ORDER BY U.identificacion ASC"
      )->setParameter('ident',$identificacion);

      return $products = $query->getResult();
  }

    public function logout(Request $request,$usuario)
    {
        //Instancia los objetos.
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();

        //Consultar información del Usuario que se desea logear.
        $infUs = $this->getInfoUser($usuario);

        //Actualiza la base de datos en la tabla usuario en el campo auth,
        //para identificar que usuario esta autenticado en el sistema.
        $us = $em->getRepository('App:Usuarios')->findBy(
          [
            'identificacion'=>$infUs[0]->getIdentificacion()
          ]
        );
        //print_r($us);
        $us[0]->setAuth('0');
        //Actualizamos la base de datos.
        $em->flush($us[0]);

        //Eliminar la sesion.
        $session->invalidate();

        //Redirigir al Route.
        return $this->redirectToRoute("auth");
    }

    //Función que sirve para validar si existe la relación
    //entre un cliente y un contrato.
    public function encryptPass($pass)
    {
        $encrypt = md5($this->_tokenpass.$pass.$this->_tokenpass);

        return $encrypt;
    }

}
