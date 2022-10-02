<?php

namespace App\Controller;

use App\Entity\Expense;
use App\Form\ExpenseFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ExpenseController extends AbstractController
{


    #[Route('/expense', name: 'app_expense')]
    public function show(ManagerRegistry $doctrine, UserInterface $user): Response
    {
        #recupere toutes les depenses de l'utilisateur
        $userEmail = $user->getUserIdentifier();
        $expenses = $doctrine->getRepository(Expense::class)->findBy(['user'=> $userEmail]);
        var_dump($expenses);
        return $this->render('expense/show.html.twig', [
            'controller_name' => 'ExpenseController',
            'expenses' => $expenses
        ]);
    }

    #[Route('/add-expense',name: 'app_add-expense')]
    public function add(UserInterface $user,Request $request, EntityManagerInterface $entityManager): Response{

        #ajoute une depense a l'utilisateur

        $userEmail = $user->getUserIdentifier();
        $expense = new Expense();

        $form = $this->createForm(ExpenseFormType::class,$expense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $expense = $form->getData();
            $entityManager->persist($expense);
            $entityManager->flush();

            $this->redirectToRoute('app_expense');
        }

        return $this->renderForm('expense/add.html.twig',[
            'form' => $form,
            'user' => $userEmail,
        ]);
    }

    #[Route('/edit-expense/{id}',name: 'app_edit-expense')]
    public function edit(ManagerRegistry $doctrine, int $id): Response{

        #modifie une depense de l'utilisateur

        $expense= $doctrine->getRepository(Expense::class)->find($id);
        $nExpense = new Expense();
        $form = $this->createForm(ExpenseFormType::class,$nExpense);
        #if ($expense){

        #}
        if ($form->isSubmitted() && $form->isValid()){
            $nExpense = $form->getData();
            $doctrine->getManager()->persist($nExpense);
            $doctrine->getManager()->flush();

            $this->redirectToRoute('app_expense');
        }
        return $this->renderForm('expense/edit.html.twig',[
            'expense' => $expense
        ]);
    }

    #[Route('/del-expense/{id}',name: 'app_del-expense')]
    public function delete(ManagerRegistry $managerRegistry,int $id): void{
        $expense = $managerRegistry->getRepository(Expense::class)->find($id);
        if ($expense) {
            $managerRegistry->getManager()->remove($expense);
            $managerRegistry->getManager()->flush();
        }
        $this->redirectToRoute("app_expense");
    }
}
