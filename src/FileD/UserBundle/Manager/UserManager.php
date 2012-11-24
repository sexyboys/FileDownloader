<?php

namespace FileD\UserBundle\Manager;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Entity\UserManager;
use FileD\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use FOS\UserBundle\Util\CanonicalizerInterface;

class MyUserManager extends UserManager
{
	public function __construct(
			EncoderFactoryInterface $encoderFactory,
			CanonicalizerInterface $usernameCanonicalizer,
			CanonicalizerInterface $emailCanonicalizer,
			EntityManager $em,
			$class
	) {
		parent::__construct($encoderFactory, $usernameCanonicalizer, $emailCanonicalizer, $em);
	}

   /**
     * {@inheritDoc}
     */
    public function deleteUser(User $user)
    {
        $this->objectManager->remove($user);
        $this->objectManager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function findUserBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }
    
    /**
     * Find all active users
     * @return array of User
     */
    public function findActiveUsers()
    {
        return $this->repository->findActiveUsers();
    }

    /**
     * {@inheritDoc}
     */
    public function findUsers()
    {
        return $this->repository->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function reloadUser(User $user)
    {
        $this->objectManager->refresh($user);
    }

    /**
     * Updates a user.
     *
     * @param User $user
     * @param Boolean       $andFlush Whether to flush the changes (default true)
     */
    public function updateUser(User $user, $andFlush = true)
    {
        $this->updateCanonicalFields($user);
        $this->updatePassword($user);

        $this->objectManager->persist($user);
        if ($andFlush) {
            $this->objectManager->flush();
        }
    }
}
