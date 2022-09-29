<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(ManagerRegistry $doctrine, UserInterface $user): Response
    {

        $userEmail = $user->getUserIdentifier();
        #var_dump($userName);
        $user_obj = $doctrine->getRepository(User::class)->findOneBy(array("email" => $userEmail));
        #var_dump($user_obj->getName());
        $userName = $user_obj->getName();
        $userAge = $user_obj->getAge();
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'userEmail' => $userEmail,
            'userName' => $userName,
            'userAge' => $userAge
        ]);
    }
}
