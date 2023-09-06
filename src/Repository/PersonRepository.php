<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)]
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)]
 * @method Person[]    findAll()]
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function findComplete($id)
    {
        $qb = $this->createQueryBuilder('p');
        $qb ->leftJoin('p.skills', 's')->addSelect('s')
                ->addOrderBy('s.lft', 'ASC')
            ->leftJoin('p.experiences', 'e')->addSelect('e')
                ->leftJoin('e.company', 'com')->addSelect('com')
                    ->leftJoin('e.realisations', 'r2_')->addSelect('r2_')
                        ->leftJoin('r2_.technologies', 't2_')->addSelect('t2_')
            ->leftJoin('p.trainings', 't')->addSelect('t')
                ->leftJoin('t.company', 'com2_')->addSelect('com2_')
            ->leftJoin('p.referrals', 'r')->addSelect('r')
                ->leftJoin('r.experiences', 'e2_')->addSelect('e2_')
                    ->leftJoin('e2_.company', 'com3_')->addSelect('com3_')
            ->leftJoin('p.languages', 'l')->addSelect('l')
            ->where($qb->expr()->eq('p.id', ':id'))
            ->setParameter('id', $id)
        ;

        return $qb->getQuery()->getSingleResult();
    }
}
