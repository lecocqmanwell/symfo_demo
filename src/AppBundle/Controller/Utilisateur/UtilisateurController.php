<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller\Utilisateur;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Repository;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * Controller used to manage blog contents in the backend.
 *
 * Please note that the application backend is developed manually for learning
 * purposes. However, in your real Symfony application you should use any of the
 * existing bundles that let you generate ready-to-use backends without effort.
 *
 * See http://knpbundles.com/keyword/admin
 *
 * @Route("/")
 *
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class UtilisateurController extends Controller
{
    /**
     * Lists all Users entities.
     * @Route("/utilisateur", name="utilisateur_index")
     *
     */
    public function indexAction(UserInterface $user)
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        return $this->render('utilisateur/page/index.html.twig', ['user' => $user]);
    }

    /**
     * Creates a new Post entity.
     *
     * @Route("/utilisateur/new", name="utilisateur_new")
     * @Method({"GET", "POST"})
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function newAction(Request $request)
    {
        $user = new User();
        // // See http://symfony.com/doc/current/book/forms.html#submitting-forms-with-multiple-buttons
        $form = $this->createForm(UserType::class, $user)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See http://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See http://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'post.created_successfully');

            // if ($form->get('saveAndCreateNew')->isClicked()) {
            //     return $this->redirectToRoute('user_new');
            // }

            return $this->redirectToRoute('utilisateur_index');
        }

        return $this->render('utilisateur/page/new.html.twig', [
            'user' => $user,
            'form' => $form->createView()

        ]);
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/utilisateur/{id}", name="user_show")
     * @Method("GET")
     */
    public function showAction(User $user){
        // This security check can also be performed
        // using an annotation: @Security("is_granted('show', post)")
        // $this->denyAccessUnlessGranted('show', $user, 'Posts can only be shown to their authors.');

        return $this->render('admin/user/show.html.twig', [
            'user' => $user,

        ]);
    }

    /**
     * Displays a form to edit an existing Post entity.
     *
     * @Route("/utilisateur/{id}/edit", name="utilisateur_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(User $user, Request $request)
    {
        // $this->denyAccessUnlessGranted('edit', $user, 'User can only be edited by their authors.');

        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(UserType::class, $user)
            ->add('saveAndCreateNew', SubmitType::class);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'post.updated_successfully');

            return $this->redirectToRoute('utilisateur_index', ['id' => $user->getId()]);
        }

        return $this->render('utilisateur/page/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}


