<?php

namespace App\Repository;

use App\Entity\Bookk;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bookk>
 *
 * @method Bookk|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bookk|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bookk[]    findAll()
 * @method Bookk[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bookk::class);
    }

//    /**
//     * @return Bookk[] Returns an array of Bookk objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Bookk
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function showAllbooksByAuthor($id)

{ return $this->createQueryBuilder('b')

->join('b.author','a')

->addSelect('a')

->where('a.id = :id')

->setParameter('id', $id)

->getQuery()->getResult() ;
}
public function findByRef($ref)
{
    return $this->createQueryBuilder('b')
        ->where('b.ref = :ref')
        ->setParameter('ref', $ref)
        ->getQuery()
        ->getResult();
}
public function findBooksByCriteria()
{
    $entityManager = $this->getEntityManager();

    $query = $entityManager->createQuery(
        'SELECT b
        FROM App\Entity\Bookk b
        JOIN b.author a
        WHERE b.publicationDate < :year
        GROUP BY a.id
        HAVING COUNT(b) > 35'
    )->setParameter('year', new \DateTime('2023-01-01'));

    return $query->getResult();
}

public function countBooksByCategory(string $category): int
    {
        return $this->createQueryBuilder('b')
            ->select('COUNT(b.ref)')
            ->where('LOWER(b.category) = :category')
            ->setParameter('category', strtolower($category))
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function findBooksBetweenDates(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.publicationDate BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();
    }

}
