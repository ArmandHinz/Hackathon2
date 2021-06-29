<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Projet;
use App\Entity\Chanel;
use App\Form\ChanelType;

/**
 * @Route("/chanel", name="chanel_")
 */
class ChanelController extends AbstractController
{
    /**
     * @param Projet $projet
     * @Route("/{projet}/addchanel", name="add")
     */
    public function new(Request $request, Projet $projet): Response
    {
        $chanel = new Chanel;
        $form = $this->createForm(ChanelType::class, $chanel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $chanel->setProjet($projet);
            $entityManager->persist($chanel);
            $entityManager->flush();

            return $this->redirectToRoute('projet_index');
        }

        return $this->render('chanel/new.html.twig', [
            'projet' => $projet,
            'chanel' => $chanel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Chanel $chanel): Response
    {
        return $this->render('chanel/show.html.twig', ["chanel" => $chanel]);
    }

    /**
     * @Route("/{id}/edit", name="edit")
     */
    public function edit(Request $request, Chanel $chanel): Response
    {
        $form = $this->createForm(ChanelType::class, $chanel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('chanel_show', ['id' => $chanel->getId()]);
        }

        return $this->render('chanel/edit.html.twig', [
            'chanel' => $chanel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete")
     */
    public function delete(Request $request, Chanel $chanel): Response
    {
        if ($this->isCsrfTokenValid('delete' . $chanel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($chanel);
            $entityManager->flush();
        }
        return $this->redirectToRoute('projet_index');
    }

    /**
     * @Route("/{id}/adduser", name="add_user")
     */
    public function addUser(Chanel $chanel)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $chanel->setIsValidate(1);
        $chanel->setUser($this->getUser());
        $entityManager->flush();

        return $this->redirectToRoute('projet_index');
    }
}
