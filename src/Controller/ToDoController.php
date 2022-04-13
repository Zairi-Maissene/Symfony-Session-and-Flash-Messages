<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ToDoController extends AbstractController
{
    #[Route('/ToDoList', name: 'List')]
    public function index(SessionInterface $session): Response
    {
        if (!$session->has('todos')) {
            $todos = [
                "Monday" => 'Shopping',
                "Tuesday" => 'Cleaning',
                "Wednesday" => 'Coding',
            ];
            $session->set('todos', $todos);
        }


        return $this->render('to_do/index.html.twig', [
            'controller_name' => 'ToDoController',
        ]);
    }
    #[Route('/ToDoList/add/{day}/{action}', name: 'add_to_do')]
    public function add(SessionInterface $session, $day, $action): Response
    {
        if ($session->has('todos')) {
            $newtodos = $session->get('todos');
            $newtodos[$day] = $action;
            $session->set('todos', $newtodos);
            $this->addFlash('init', 'ajout de ToDo: ' . $day . ' effectué avec succés');
        } else
            $this->addFlash('NotInit', 'La liste n est pas encore initialisé!');
        return $this->redirecttoRoute('List');
    }
    #[Route('ToDoList/delete/{day}', name: 'delete_to_do')]
    public function delete(SessionInterface $session, $day): Response
    {
        if (!$session->has('todos'))
            $this->addFlash('NotInit', 'La liste n est pas encore initialisé!');
        else {
            if (isset($session->get('todos')[$day])) {
                $newtodos = $session->get('todos');
                unset($newtodos[$day]);
                $session->set('todos', $newtodos);

                $this->addFlash('successDelete', 'ToDo: ' . $day . ' supprimé avec succés');
            } else
                $this->addFlash('errorDelete', "l element " . $day . " n'existe pas dans la liste!");
        }
        return $this->redirectToRoute('List');
    }
    #[Route('ToDoList/reset', name: 'reset')]
    public function reset(SessionInterface $session): Response
    {
        if (!$session->has('todos'))
            $this->addFlash('NotInit', 'La liste n est pas encore initialisé!');
        else {
            unset($session);
            $this->addFlash('successReset', 'reset effectué avec succés!');
        }
        return
            $this->redirectToRoute('List');
    }
}
