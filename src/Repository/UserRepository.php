<?php


namespace App\Repository;

use App\Constant\Role;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class UserRepository
 */
class UserRepository extends ServiceEntityRepository
{

    /**
     * UserRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return array
     */
    public function getAllUsers(): array
    {
        $qb = $this->createQueryBuilder('u');
        $qb
            ->andWhere('JSON_CONTAINS(u.roles, :role) = 1')
            ->setParameter('role', '"' . Role::ROLE_USER . '"');

        return $qb->getQuery()->getResult();
    }

    /**
     * @return array
     */
    public function getInactiveUsers(): array
    {
        $oneMonthBefore = new \DateTime('-1 month');
        $qb = $this->createQueryBuilder('u');
        $expr = $qb->expr();
        $qb
            ->andWhere('JSON_CONTAINS(u.roles, :role) = 1')
            ->setParameter('role', '"' . Role::ROLE_USER . '"')
            ->andWhere($expr->lt('u.lastLoginDate', $expr->literal($oneMonthBefore->format('Y-m-d H:i:s'))))
            ->andWhere($expr->eq('u.active', 1));

        return $qb->getQuery()->getResult();
    }
}