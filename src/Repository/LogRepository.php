<?php

namespace App\Repository;

use App\Entity\Log;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Log|null find($id, $lockMode = null, $lockVersion = null)
 * @method Log|null findOneBy(array $criteria, array $orderBy = null)
 * @method Log[]    findAll()
 * @method Log[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Log::class);
    }

    /**
     * Find Log by level, level name, client ip, uri, and/or method.
     *
     * @param integer|null $level
     * @param string|null $levelName
     * @param string|null $clientIp
     * @param string|null $uri
     * @param string|null $method
     * @return void
     */
    public function findLogsBy(?int $level=null, ?string $levelName=null, ?string $clientIp=null, ?int $clientPort=null, 
        ?string $uri=null, ?string $method=null, ?string $token)
    {
        $query = $this->createQueryBuilder('l')->select('l');

        if ($level !== null) {
            $query->andWhere("l.level = :level");
            $query->setParameter('level', $level);
        }

        if ($levelName !== null) {
            $query->andWhere("l.levelName LIKE :levelName");
            $query->setParameter('levelName', $levelName);
        }

        if ($clientIp !== null) {
            $query->andWhere("l.clientIp LIKE :clientIp");
            $query->setParameter('clientIp', '%' . $clientIp . '%');
        }

        if ($clientPort !== null) {
            $query->andWhere("l.clientPort LIKE :clientPort");
            $query->setParameter('clientPort', '%' . $clientPort . '%');
        }

        if ($uri !== null) {
            $query->andWhere("l.uri LIKE :uri");
            $query->setParameter('uri', '%' . $uri . '%');
        }

        if ($method !== null) {
            $query->andWhere("l.method LIKE :method");
            $query->setParameter('method', $method);
        }

        if ($token !== null) {
            $query->andWhere("l.token LIKE :token");
            $query->setParameter('token', '%' . $token . '%');
        }

        return $query->getQuery()->getResult();
    }
}
