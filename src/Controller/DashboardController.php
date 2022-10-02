<?php

namespace App\Controller;

use App\Entity\Expense;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(ManagerRegistry $doctrine, UserInterface $user): Response
    {
        $userEmail = $user->getUserIdentifier();
        $expenses = $doctrine->getRepository(Expense::class)->findBy(['user'=> $userEmail]);
        $sumMonth = 0;
        $sumDay = 0;
        //var_dump($expenses[0]->getDate());
        $date = new \DateTime();
        $date->format("Y-m-d H:i:s");
        //var_dump($date);
        foreach ($expenses as $expense){
            if ((($expense->getDate())->diff($date))->days == 0){
                //var_dump($expense);
                $sumDay += $expense->getAmount();
            }
        }

        foreach ($expenses as $expense){
            if (($expense->getDate())->format("Y-m")==$date->format("Y-m")){
                //var_dump($expense);
                $sumMonth += $expense->getAmount();
            }
        }
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'sumDay' => $sumDay,
            'sumMonth' => $sumMonth,
            'expenses' => $expenses,
        ]);
    }
}
