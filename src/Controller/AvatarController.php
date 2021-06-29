<?php

namespace App\Controller;

use App\Entity\Avatar;
use App\Entity\File;
use App\Entity\User;
use App\Form\AvatareType;
use App\Form\AvatarType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AvatarController extends AbstractController
{
    /**
     * @Route("/avatar", name="change_avatar")
     */
    public function index(Request $request, EntityManagerInterface $em): Response 
    {
        $avatar = new Avatar();
        $form = $this->createForm(AvatareType::class, $avatar);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {
            $em->persist($avatar);
            $em->flush();

            return $this->redirectToRoute('home_index');
        }
        return $this->render('avatar/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
