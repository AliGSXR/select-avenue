<?php

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use App\Entity\User;

require 'vendor/autoload.php';
require 'config/bootstrap.php';


$kernel = new \App\Kernel('dev', true);
$kernel->boot();
$container = $kernel->getContainer();
$passwordHasher = $container->get(UserPasswordHasherInterface::class);


$user = new User();
$plainPassword = "SelectAvenue32";

$hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);

echo "Mot de passe encodé : " . $hashedPassword . "\n";
