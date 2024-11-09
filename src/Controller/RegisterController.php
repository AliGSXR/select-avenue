<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {

        $user = new User();

        $form = $this->createForm(RegisterUserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Votre compte a été créé avec succès.'
            );


            return $this->redirectToRoute('app_login');
        }

        $mail = new Mail();
        $mail->send($user->getEmail(), $user->getFirstname(), 'Bienvenue chez Select Avenue', 'Mon premier mail');

        return $this->render('register/index.html.twig', [
            'registerform' => $form->createView()
        ]);
    }
}
