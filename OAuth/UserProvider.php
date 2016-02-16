<?php

namespace Dywee\UserBundle\OAuth;

use FOS\UserBundle\Model\UserInterface as FOSUserInterface;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider;

use Dywee\UserBundle\Entity\User;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Loading and ad-hoc creation of a user by an OAuth sign-in provider account.
 *
 * @author Fabian Kiss <fabian.kiss@ymc.ch>
 */
class UserProvider extends FOSUBUserProvider
{
    /**
     * {@inheritDoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        try {
            return parent::loadUserByOAuthUserResponse($response);
        } catch (UsernameNotFoundException $e) {
            if (null === $user = $this->userManager->findUserByEmail($response->getEmail())) {
                return $this->createUserByOAuthUserResponse($response);
            }

            return $this->updateUserByOAuthUserResponse($user, $response);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $providerName = $response->getResourceOwner()->getName();
        $uniqueId = $response->getUsername();
        $user->addOAuthAccount($providerName, $uniqueId);

        $this->userManager->updateUser($user);
    }

    /**
     * Ad-hoc creation of user
     *
     * @param UserResponseInterface $response
     *
     * @return User
     */
    protected function createUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $user = $this->userManager->createUser();
        $this->updateUserByOAuthUserResponse($user, $response);

        $data = $response->getResponse();

        $user->setFirstName($data['first_name']); //$response->getFirstname());
        $user->setLastName($data['last_name']);
        $user->setGender($data['gender']== 'male'?1:0);
        //$user->addRole('ROLE_ADMIN');

        // set default values taken from OAuth sign-in provider account
        if (null !== $email = $response->getEmail()) {
            $user->setEmail($email);
            $user->setEmailCanonical($email);
        }

        if (null === $this->userManager->findUserByUsername($response->getNickname())) {
            $user->setUsername($response->getNickname());
        }

        /*echo '<pre>';
        print_r($user);
        echo '</pre>';
        exit;*/

        $user->setEnabled(true);

        return $user;
    }

    /**
     * Attach OAuth sign-in provider account to existing user
     *
     * @param FOSUserInterface      $user
     * @param UserResponseInterface $response
     *
     * @return FOSUserInterface
     */
    protected function updateUserByOAuthUserResponse(FOSUserInterface $user, UserResponseInterface $response)
    {
        $providerName = $response->getResourceOwner()->getName();
        $providerNameSetter = 'set'.ucfirst($providerName).'Id';
        $user->$providerNameSetter($response->getUsername());

        if(!$user->getPassword()) {
            // generate unique token
            $secret = md5(uniqid(rand(), true));
            $user->setPassword($secret);
        }

        return $user;
    }
}