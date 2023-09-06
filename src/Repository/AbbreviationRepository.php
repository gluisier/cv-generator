<?php

namespace App\Repository;

use App\Entity\Abbreviation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Abbreviation|null find($id, $lockMode = null, $lockVersion = null)]
 * @method Abbreviation|null findOneBy(array $criteria, array $orderBy = null)]
 * @method Abbreviation[]    findAll()]
 * @method Abbreviation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbbreviationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Abbreviation::class);
    }
}
