<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ApiKey;

/**
 * @method ApiKey|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiKey|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiKey[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiKeyRepository extends AbstractRepository
{
}
