<?php


namespace App\Controller;

use App\Constant\Role;
use App\Entity\User;
use App\Form\UserType;
use App\Model\UserModel;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 * Class UserController
 */
class UserController extends AbstractController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var UserModel
     */
    private $model;
    /**
     * @var EntityManagerInterface
     */
    private $em;


    /**
     * UserController constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserModel $model
     * @param EntityManagerInterface $em
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, UserModel $model, EntityManagerInterface $em)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->model = $model;
        $this->em = $em;
    }


    /**
     * @Route("/create-new", name="create_new_user")
     * @Route("/edit-user/{id}", name="edit_user")
     * @param Request $request
     * @param User|null $user
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createNewUserAction(Request $request,?User $user): Response
    {
        $new = !$user instanceof User;
        $user = $new ? new User : $user;
        $form = $this->createForm(UserType::class, $user, ['new' => $new]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $passwordFromForm = $user->getPassword();
            if (!$new && $form->get('password')->getNormData() !== null) {
                $passwordFromForm = $form->get('password')->getNormData();
            }
            $password = $this->passwordEncoder->encodePassword($user, $passwordFromForm);
            $user
                ->setRoles([Role::ROLE_USER])
                ->setPassword($password);
            $this->em->persist($user);
            $this->em->flush();
            $this->model->sendUserLoginDetails($passwordFromForm, $user->getEmail());

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/manage.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/list", name="user_list")
     * @return Response
     */
    public function masterAction(): Response
    {
        $admin = (new User)
            ->setName('admin')
            ->setEmail('admin@admin')
            ->addRole(Role::ROLE_ADMIN);
        $password = $this->passwordEncoder->encodePassword($admin,'demo');
        $admin->setPassword($password);
        $this->em->persist($admin);
        $this->em->flush();

        /** @var UserRepository $repo */
        $repo = $this->em->getRepository(User::class);
        $users = $repo->getAllUsers();

        return $this->render('user/user.list.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/delete-user/{id}", name="delete_user")
     * @param User $user
     * @return RedirectResponse
     */
    public function deleteUser(User $user): Response
    {
        $this->em->remove($user);
        $this->em->flush();

        return $this->redirectToRoute('user_list');
    }
}