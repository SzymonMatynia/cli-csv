<?php

namespace App\Repository;

use App\Entity\Feed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use FeedIo\FeedIo;

/**
 * @method Feed|null find($id, $lockMode = null, $lockVersion = null)
 * @method Feed|null findOneBy(array $criteria, array $orderBy = null)
 * @method Feed[]    findAll()
 * @method Feed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedRepository extends ServiceEntityRepository
{

    /**
     * @var FeedIo
     */
    private $feedIo;

    public function __construct(ManagerRegistry $registry, FeedIo $feedIo)
    {
        parent::__construct($registry, Feed::class);
        $this->feedIo = $feedIo;
    }

    // /**
    //  * @return Feed[] Returns an array of Feed objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Feed
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @param string $url
     * @return array
     */
    public function getArrayOfFeedObjects(string $url) : array
    {
        $feed = $this->feedIo->read($url)->getFeed();
        $data = [];
        foreach ($feed as $item) {
            $feedObj = (new Feed())
                ->setTitle($item->getTitle())
                ->setDescription(strip_tags($item->getDescription()))
                ->setGuid($item->getPublicId())
                ->setLink($item->getLink())
                ->setCreator($item->getAuthor())
                ->setPubDate($item->getLastModified());

            $data[] = $feedObj;
        }
        return $data;
    }
}
