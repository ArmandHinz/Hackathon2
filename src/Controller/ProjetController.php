<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetType;
use App\Repository\ProjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * @Route("/projet", name="projet_")
 */
class ProjetController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ProjetRepository $projetRepository): Response
    {
        return $this->render('projet/index.html.twig', [
            'projets' => $projetRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="new")
     * @IsGranted("ROLE_BUSINESS")
     */
    public function new(Request $request): Response
    {
        $projet = new Projet;
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projet->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($projet);
            $entityManager->flush();

            return $this->redirectToRoute('projet_index');
        }

        return $this->render('projet/new.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/{id}/edit", name="edit")
     * @IsGranted("ROLE_BUSINESS")
     */
    public function edit(Request $request, Projet $projet): Response
    {
        if (!($this->getUser() == $projet->getUser())) {
            // If not the owner, throws a 403 Access Denied exception
            throw new AccessDeniedException('Only the owner can edit the program!');
        }
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('projet_index');
        }

        return $this->render('projet/edit.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Projet $projet): Response
    {
        return $this->render('projet/show.html.twig', ['projet' => $projet]);
    }

    /**
     * @Route("/{id}/delete", name="delete")
     * @IsGranted("ROLE_BUSINESS")
     */
    public function delete(Request $request, Projet $projet): Response
    {
        if (!($this->getUser() == $projet->getUser())) {
            // If not the owner, throws a 403 Access Denied exception
            throw new AccessDeniedException('Only the owner can edit the program!');
        }
        if ($this->isCsrfTokenValid('delete'.$projet->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($projet);
            $entityManager->flush();
        }
        return $this->redirectToRoute('projet_index');
    }
}
