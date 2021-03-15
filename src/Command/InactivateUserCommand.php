<?php


namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InactivateUserCommand
 */
class InactivateUserCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * InactivateUserCommand constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }


    protected function configure()
    {
        $this->setName('user:inactivate');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var UserRepository $repo */
        $repo = $this->em->getRepository(User::class);
        $inactiveUsers = $repo->getInactiveUsers();
        /** @var User $user */
        foreach ($inactiveUsers as $user) {
            $user->setActive(false);
            $this->em->persist($user);
            $this->em->flush();
        }

        return Command::SUCCESS;
    }
}