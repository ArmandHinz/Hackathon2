<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\UserType;
use App\Form\TechnoType;
use Vich\UploaderBundle\Form\Type\VichFileType;


/**
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * Creat a new form in order to add a new subject
     *
     * @Route("/{id}/edit", name="edit")
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('user_show', ["id" => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', ["form" => $form->createView()]);
    }

    /**
     * Show all comment of a subjet
     * @Route("/{id}", name="show")
     * @return Response A response instance
     */
    public function show(User $user): Response
    {
        $avatars = $user->getAvatar();
        return $this->render(
            'user/show.html.twig',
            [
                'user' => $user,
                'avatars' => $avatars
            ]
        );
    }
}
