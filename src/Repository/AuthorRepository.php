<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }
    public function findAllAuthorsOrderedByEmail()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.email', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function findAll3()
    {
      

       $em=$this->getEntityManager();
      $req=$em->createquery('SELECT a FROM App\entity\Author a');

       return $req ->getResult();
    }
//    /**
//     * @return Author[] Returns an array of Author objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Author
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function findAuthorsByBookCountRange($minBooks, $maxBooks)
    {
        $queryBuilder = $this->createQueryBuilder('a');

        if ($minBooks) {
            $queryBuilder->andWhere('a.nb_books >= :minBooks')
                        ->setParameter('minBooks', $minBooks);
        }

        if ($maxBooks) {
            $queryBuilder->andWhere('a.nb_books <= :maxBooks')
                        ->setParameter('maxBooks', $maxBooks);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
