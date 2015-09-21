<?php

namespace Dywee\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Dywee\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Dywee\UserBundle\Form\Type\ProfileFormType;

class UserAdminController extends Controller
{
    public function editAction(User $user, Request $request)
    {
        $form = $this->get('form.factory')->create(new ProfileFormType(), $user);

        $form
            ->remove('current_password')
            ->add('roles', 'choice', array('choices' => array('ROLE_USER' => 'ROLE_USER', 'ROLE_ADMIN' => 'ROLE_ADMIN'), 'multiple' => true));

        if($form->handleRequest($request)->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Utilisateur bien modifié');

            return $this->redirect($this->generateUrl('dywee_user_table'));
        }
        return $this->render('DyweeUserBundle:User:edit.html.twig', array('form' => $form->createView(), 'user_id' => $user->getId()));
    }
}
