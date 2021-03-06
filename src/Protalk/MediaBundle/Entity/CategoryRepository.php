<?php

/**
 * ProTalk
 *
 * Copyright (c) 2012-2013, ProTalk
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Protalk\MediaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Protalk\MediaBundle\Entity\Media;
use Doctrine\ORM\Query;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends EntityRepository
{
    /**
     * Get the most used categories
     *
     * @param  int      $max
     * @return Doctrine Collection
     */
    public function getMostUsedCategories($max = 20, $hydrator = Query::HYDRATE_OBJECT)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('c.slug', 'c.name', 'COUNT(m.id) as mediaCount');
        $qb->from('ProtalkMediaBundle:Category', 'c');
        $qb->join('c.languageCategories', 'lc');
        $qb->join('lc.medias','mlc' );
        $qb->join('mlc.media', 'm');
        $qb->where('m.status = :status');
        $qb->groupBy('c.slug');
        $qb->orderBy('mediaCount', 'DESC');
        $qb->setMaxResults($max);

        $query = $qb->getQuery();
        $query->setParameter("status", Media::STATUS_PUBLISHED);

        return $query->execute();
    }

    /**
     * Get all used categories
     *
     * @return Doctrine Collection
     */
    public function getAllCategories($hydrator = Query::HYDRATE_OBJECT)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('c.slug', 'c.name', 'COUNT(m.id) as mediaCount');
        $qb->from("ProtalkMediaBundle:Category", 'c');
        $qb->join('c.languageCategories', 'lc');
        $qb->join('lc.medias', 'mlc');
        $qb->join('mlc.media', 'm');
        $qb->where('m.status = :status');
        $qb->orderBy('c.name', 'ASC');

        $query = $qb->getQuery($hydrator);
        $query->setParameter("status", Media::STATUS_PUBLISHED);

        return $query->execute();
    }
}
