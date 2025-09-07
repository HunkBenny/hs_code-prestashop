<?php

namespace PrestaShop\Module\HB_Hscode\Repository;

use PrestaShop\Module\HB_Hscode\Entity\HbProductHSCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;


class ProductHSCodeRepository extends ServiceEntityRepository
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string 
     */
    private $database_prefix;

    public function __construct(ManagerRegistry $registry, Connection $connection, $database_prefix)
    {
        parent::__construct($registry, HbProductHSCode::class);
        $this->connection = $connection;
        $this->database_prefix = $database_prefix;
    }

    /**
     * Adds new producthscode to the database manager
     * @param \PrestaShop\Module\HB_Hscode\Entity\HbProductHSCode $entity
     * @param bool $flush - default `true`; set false to not immediately flush changes
     * @return void
     */
    public function add(HbProductHSCode $entity, bool $flush = true): void
    {
        // Tell the entitymanager that a new entity needs to be added to the database.
        $this->getEntityManager()->persist($entity);
        // Process query
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function flush() : void
    {
        $this->getEntityManager()->flush();
    }

    /**
     * Removes producthscode from the database manager
     * @param \PrestaShop\Module\HB_Hscode\Entity\HbProductHSCode $entity
     * @param bool $flush - default `true`; set false to not immediately flush changes
     * @return void
     */
    private function remove(HbProductHSCode $entity, bool $flush = true): void
    {
        // Tell the entitymanager that an entity needs to be removed from the database.
        $this->getEntityManager()->remove($entity);
        // Process query
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Deletes producthscode from the database.
     * @param \PrestaShop\Module\HB_Hscode\Entity\HbProductHSCode $entity
     * @return void
     */
    public function delete(HbProductHSCode $entity): void
    {
        $this->remove($entity);
        $sql = 'DELETE FROM ' . $this->database_prefix . 'hb_product_hs_codes WHERE id_product = '. (int) $entity->getIdProduct();
        $this->connection->executeStatement($sql);
    }
}
