<?php

namespace App\Controller\Account;

use App\Form\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class PasswordController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/compte/modif-pwd', name: 'app_account_modif_pwd')]
    public function index(\Symfony\Component\HttpFoundation\Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {

        $user = $this->getUser();


        $form = $this->createForm(PasswordUserType::class, $user, [
            'passwordHasher' => $passwordHasher,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash(
                'success',
                'Votre mot de passe a été mis a jour avec succès'
            );
            $this->entityManager->flush();
        }
        return $this->render('account/Password/index.html.twig',[
            'modifPwd' => $form ->createView(),
        ]);
    }
}
?>