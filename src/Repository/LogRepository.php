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
     * Find log by criteria; properties levelName, clientIp, clientPort, uri, method, and token
     * will use the LIKE operator, all other properties will use equality operator.
     *
     * Notice: Date format for date_from and date_to are YYYY-MM-DD.
     * 
     * @param array $criteria
     * @param boolean $allow_null_values
     * @return void
     */
    public function findLogsBy(array $criteria, bool $allow_null_values=false)
    {
        $query = $this->createQueryBuilder('l')->select('l');
        $logMetadata = $this->getClassMetadata(Log::class);

        foreach ($criteria as $key => $value) {
            if (!$allow_null_values && $value === null)
                continue;
            
            switch($key) {
                case 'level':
                    $query->andWhere("l.level = :level");
                    $query->setParameter('level', $value);
                    break;
                case 'levelName':
                    $query->andWhere("l.levelName LIKE :levelName");
                    $query->setParameter('levelName', $value);
                    break;
                case 'clientIp':
                    $query->andWhere("l.clientIp LIKE :clientIp");
                    $query->setParameter('clientIp', '%' . $value . '%');
                    break;
                case 'clientPort':
                    $query->andWhere("l.clientPort LIKE :clientPort");
                    $query->setParameter('clientPort', '%' . $value . '%');
                    break;
                case 'uri':
                    $query->andWhere("l.uri LIKE :uri");
                    $query->setParameter('uri', '%' . $value . '%');
                    break;
                case 'method':
                    $query->andWhere("l.method LIKE :method");
                    $query->setParameter('method', $value);
                    break;
                case 'token':
                    $query->andWhere("l.token LIKE :token");
                    $query->setParameter('token', '%' . $value . '%');
                    break;
                case 'message':
                    $query->andWhere("l.message LIKE :message");
                    $query->setParameter('message', '%' . $value . '%');
                    break;
                case 'date_range':
                    if ($value[0] !== null && $value[1] !== null) {
                        // range is from date_from to date_to
                        $query->andWhere("l.createdAt BETWEEN :date_from AND :date_to");
                        $query->setParameter('date_from', $value[0]->format('Y-m-d 00:00:00'));
                        $query->setParameter('date_to', $value[1]->format('Y-m-d 23:59:59'));
                    } else if ($value[0] !== null && $value[1] === null) {
                        // range is from date_from
                        $query->andWhere("l.createdAt >= :date_from");
                        $query->setParameter('date_from', $value[0]->format('Y-m-d 00:00:00'));
                    } else if ($value[0] === null && $value[1] !== null) {
                        // range is up to date_to
                        $query->andWhere("l.createdAt <= :date_to");
                        $query->setParameter('date_to', $value[1]->format('Y-m-d 23:59:59'));
                    }
                    break;
                default:
                    if ($logMetadata->hasField($key)) {
                        $query->andWhere(":key = :value");
                        $query->setParameter('key', 'l.' . $key);
                        $query->setParameter('value', $value);
                    }
                    break;
            }
        }
        
        return $query->getQuery()->getResult();
    }
}
