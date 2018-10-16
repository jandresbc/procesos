<?php

namespace App\Controller;

use App\Entity\Procesos;
use App\Entity\Usuarios;
use App\Form\ProcesosType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @Route("/procesos")
 */
class ProcesosController extends AbstractController
{
    /**
     * @Route("/", name="procesos_index", methods="GET|POST")
     */
    public function index(Request $request): Response
    {
        $session = $request->getSession();

        $session = $request->getSession();

        if($session->get("auth") == 1){
            $procesos = $this->getDoctrine()
                ->getRepository(Procesos::class)
                ->createQueryBuilder("P")
                ->where("P.idUsuario = ".$session->get("idUsuario"))
                ->orderBy("P.idProceso","DESC");

            //Crear formulario de filtros.
            $form = $this->createFormBuilder()
            ->add("nroProceso",TextType::class,[
                "label"=>"Nro. Proceso: ",
                "required"=>false,
                "attr"=>["class"=>"form-control","placeholder"=>"Nro. Proceso"]
            ])->add("fechaCreacion",DateType::class,[
                "label"=>"Fecha Creación: ",
                "required"=>false,
                "widget"=>"single_text",
                "attr"=>["class"=>"form-control"]
            ])->add('sede',ChoiceType::class,[
                "label"=>"Sede: ",
                "placeholder"=>"Seleccione Sede",
                "required"=>false,
                "choices"=>[
                    "Bogotá"=>"Bogotá DC.",
                    "Mexico"=>"Mexico",
                    "Perú"=>"Perú"
                ],
                "attr"=>["class"=>"form-control"]
            ])
            ->getForm();
            
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $fecha = $request->request->get('form')['fechaCreacion'];
                $sede = $request->request->get('form')['sede'];
                $nroProceso = $request->request->get('form')['nroProceso'];

                if($nroProceso != '' && $nroProceso != null){
                    $procesos->andWhere("P.nroProceso like '%".$nroProceso."%'");
                }

                if($fecha != '' && $fecha != null){
                    $procesos->andWhere("P.fechaCreacion = '".$fecha."'");
                }

                if($sede != '' && $sede != null){
                    $procesos->andWhere("P.sede = '".$sede."'");
                }

                $procesos = $procesos->getQuery()->getResult();

                return $this->render('procesos/index.html.twig', [
                    'procesos' => $procesos,
                    'filtros' => $form->createView()
                ]);
            }
            
            $procesos = $procesos->getQuery()->getResult();
            
            return $this->render('procesos/index.html.twig', [
                'procesos' => $procesos,
                'filtros' => $form->createView()
            ]);
        }else{
            return $this->redirectToRoute("auth");
        }
    }

    /**
     * @Route("/new", name="procesos_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $session = $request->getSession();

        if($session->get("auth") == 1){
            $proceso = new Procesos();
            $form = $this->createForm(ProcesosType::class, $proceso);
            $form->handleRequest($request);

            //Generamos el número único del proceso.
            $nroProceso = $this->nroDocumento($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                //Ingresmos el número de proceso único consecutivo, generado.
                $proceso->setNroProceso($nroProceso);
                //Fecha Actual.
                $fechaNow = new \DateTime("now",new \DateTimeZone('America/Bogota'));
                //Se ingresa la fecha actual como fecha de creación del proceso.
                $proceso->setFechaCreacion($fechaNow);
                //Le asignamos este proceso al usuario autenticado.
                $us = $em->getRepository(Usuarios::class)->findBy(["idUsuario"=>$session->get("idUsuario")]);
                $proceso->setIdUsuario($us[0]);
                $em->persist($proceso);
                $em->flush();

                return $this->redirectToRoute('procesos_index');
            }

            return $this->render('procesos/new.html.twig', [
                'proceso' => $proceso,
                'form' => $form->createView(),
                "nroProceso"=>$nroProceso
            ]);
        }else{
            return $this->redirectToRoute("auth");
        }
    }

    /**
     * @Route("/{idProceso}", name="procesos_show", methods="GET")
     */
    public function show(Request $request, Procesos $proceso): Response
    {   
        $session = $request->getSession();
        if($session->get("auth") == 1){
            return $this->render('procesos/show.html.twig', ['proceso' => $proceso]);
        }else{
            return $this->redirectToRoute("auth");
        }
    }

    /**
     * @Route("/{idProceso}/edit", name="procesos_edit", methods="GET|POST")
     */
    public function edit(Request $request, Procesos $proceso): Response
    {
        $session = $request->getSession();
        if($session->get("auth") == 1){
            $form = $this->createForm(ProcesosType::class, $proceso);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('procesos_index');
            }

            return $this->render('procesos/edit.html.twig', [
                'proceso' => $proceso,
                'form' => $form->createView(),
            ]);
        }else{
            return $this->redirectToRoute("auth");
        }
    }

    /**
     * @Route("/{idProceso}", name="procesos_delete", methods="DELETE")
     */
    public function delete(Request $request, Procesos $proceso): Response
    {
        $session = $request->getSession();
        if($session->get("auth") == 1){
            if ($this->isCsrfTokenValid('delete'.$proceso->getIdProceso(), $request->request->get('_token'))) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($proceso);
                $em->flush();
            }

            return $this->redirectToRoute('procesos_index');
        }else{
            return $this->redirectToRoute("auth");
        }
    }

    private function nroDocumento(Request $request): ?String
    {
        $session = $request->getSession();
        $nro = 1;
        $em = $this->getDoctrine()->getManager();

        $procesos = $em->createQuery(
        "
        SELECT P.nroProceso FROM App:Procesos P WHERE 
        P.idProceso = (SELECT MAX(P2.idProceso) as IDProceso FROM App:Procesos P2 Where P2.idUsuario = ".$session->get('idUsuario').")
        "
        )->getResult();

        $len = count($procesos);
        
        if($len > 0){
            $nro = $procesos[0]["nroProceso"] + 1;
        }

        return str_pad($nro, 8, "0", STR_PAD_LEFT);
    }
}
