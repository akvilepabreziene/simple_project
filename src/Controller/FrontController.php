<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/")
 * Class FrontController
 */
class FrontController extends AbstractController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * FrontController constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }


    /**
     * @Route("/", name="homepage")
     */
    public function homepageAction()
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        if ($user->isAdmin()) {
            return $this->redirectToRoute('user_list');
        }

        if (!$user->isActive()) {
            return $this->redirectToRoute('app_login');
        }

        $user->setLastLoginDate(new \DateTime('now'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->render('homepage.html.twig');
    }
}