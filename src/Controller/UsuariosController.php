<?php

namespace App\Controller;

use App\Entity\Usuarios;
use App\Form\UsuariosType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/usuarios")
 */
class UsuariosController extends AbstractController
{
    /**
     * @Route("/", name="usuarios_index", methods="GET")
     */
    public function index(): Response
    {
        $usuarios = $this->getDoctrine()
            ->getRepository(Usuarios::class)
            ->findAll();

        return $this->render('usuarios/index.html.twig', ['usuarios' => $usuarios]);
    }

    /**
     * @Route("/new", name="usuarios_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $usuario = new Usuarios();
        $form = $this->createForm(UsuariosType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);
            $em->flush();

            return $this->redirectToRoute('usuarios_index');
        }

        return $this->render('usuarios/new.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idUsuario}", name="usuarios_show", methods="GET")
     */
    public function show(Usuarios $usuario): Response
    {
        return $this->render('usuarios/show.html.twig', ['usuario' => $usuario]);
    }

    /**
     * @Route("/{idUsuario}/edit", name="usuarios_edit", methods="GET|POST")
     */
    public function edit(Request $request, Usuarios $usuario): Response
    {
        $form = $this->createForm(UsuariosType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('usuarios_edit', ['idUsuario' => $usuario->getIdUsuario()]);
        }

        return $this->render('usuarios/edit.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idUsuario}", name="usuarios_delete", methods="DELETE")
     */
    public function delete(Request $request, Usuarios $usuario): Response
    {
        if ($this->isCsrfTokenValid('delete'.$usuario->getIdUsuario(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($usuario);
            $em->flush();
        }

        return $this->redirectToRoute('usuarios_index');
    }
}
