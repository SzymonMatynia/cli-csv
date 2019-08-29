<?php

namespace SzymonMatyniaRekrutacjaHRtec\Repository;

use SzymonMatyniaRekrutacjaHRtec\Entity\Feed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use FeedIo\FeedIo;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;

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

    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var EncoderInterface
     */
    private $encoder;

    public function __construct(ManagerRegistry $registry, FeedIo $feedIo, KernelInterface $kernel, EncoderInterface $encoder)
    {
        parent::__construct($registry, Feed::class);
        $this->feedIo = $feedIo;
        $this->kernel = $kernel;
        $this->encoder = $encoder;
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

    /**
     * @param string $path
     * @param array $data
     * @param string $writeMode
     * @throws \Exception
     */
    public function writeToCSVFile(string $path, array $data, string $writeMode) : void
    {
        $fullPath = $this->kernel->getProjectDir() . $path;

        $data = $this->getDataToEncodeFromFeedObjects($data);

        try {
            $fp = fopen($fullPath, $writeMode);
            if (!$fp) {
                throw new \Exception('File open failed.');
            }
            $dataEncoded = $this->encodeDataToCSV($fullPath, $data, $writeMode);

            fputs($fp, $dataEncoded);
            fclose($fp);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param string $fullPath
     * @param array $data
     * @param string $writeMode
     * @return bool|float|int|string
     */
    private function encodeDataToCSV(string $fullPath, array $data, string $writeMode)
    {
        if ($writeMode === 'a' && filesize($fullPath) !== 0) {
            return $this->encoder->encode($data, 'csv', [CsvEncoder::NO_HEADERS_KEY => true]);
        }
        return $this->encoder->encode($data, 'csv');
    }

    /**
     * @param array $data
     * @return array
     */
    private function getDataToEncodeFromFeedObjects(array $data) : array
    {
        $dataToEncode = [];
        foreach ($data as $feedObj) {
            $dataToEncode[] = $feedObj->getDataToEncode();
        }
        return $dataToEncode;
    }
}
