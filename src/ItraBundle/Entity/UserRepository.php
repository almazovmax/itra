<?php
namespace ItraBundle\Entity;

use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return parent::findOneBy($criteria, $orderBy);
    }

    public function saveToken($token, $username)
    {
        $user = $this->loadUserByUsername($username);
        $user->setToken($token);

        $em = $this->getEntityManager();
        $em->flush();
    }

    public function removeToken($user)
    {
        $user->setToken(null);

        $em = $this->getEntityManager();
        $em->flush();
    }

    public function saveNewPassword()
    {
        $em= $this->getEntityManager();
        $em->flush();
    }
}