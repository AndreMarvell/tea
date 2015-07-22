<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Entity;
use FOS\MessageBundle\Model\ParticipantInterface;
use Sonata\UserBundle\Entity\BaseUser as BaseUser;

/**
 * This file has been generated by the Sonata EasyExtends bundle ( https://sonata-project.org/bundles/easy-extends )
 *
 * References :
 *   working with object : http://www.doctrine-project.org/projects/orm/2.0/docs/reference/working-with-objects/en
 *
 * @author <yourname> <youremail>
 */
class User extends BaseUser implements ParticipantInterface
{
    /**
     * @var integer $id
     */
    protected $id;
    
    private $badges;
    
    protected $facebook_id;

    protected $facebook_access_token;

    protected $google_id;

    protected $google_access_token;
    
    protected $passwordUpdated = true;
    
    protected $profileUpdated = false;
    
    protected $teacher = false;

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     */
    private $avatar;


    /**
     * Set avatar
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $avatar
     *
     * @return User
     */
    public function setAvatar(\Application\Sonata\MediaBundle\Entity\Media $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Add badge
     *
     * @param \Application\Sonata\UserBundle\Entity\Badge $badge
     *
     * @return User
     */
    public function addBadge(\Application\Sonata\UserBundle\Entity\Badge $badge)
    {
        $this->badges[] = $badge;

        return $this;
    }

    /**
     * Remove badge
     *
     * @param \Application\Sonata\UserBundle\Entity\Badge $badge
     */
    public function removeBadge(\Application\Sonata\UserBundle\Entity\Badge $badge)
    {
        $this->badges->removeElement($badge);
    }

    /**
     * Get badges
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBadges()
    {
        return $this->badges;
    }

    /**
     * Set facebookId
     *
     * @param string $facebookId
     *
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebook_id = $facebookId;

        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * Set facebookAccessToken
     *
     * @param string $facebookAccessToken
     *
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebook_access_token = $facebookAccessToken;

        return $this;
    }

    /**
     * Get facebookAccessToken
     *
     * @return string
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }

    /**
     * Set googleId
     *
     * @param string $googleId
     *
     * @return User
     */
    public function setGoogleId($googleId)
    {
        $this->google_id = $googleId;

        return $this;
    }

    /**
     * Get googleId
     *
     * @return string
     */
    public function getGoogleId()
    {
        return $this->google_id;
    }

    /**
     * Set googleAccessToken
     *
     * @param string $googleAccessToken
     *
     * @return User
     */
    public function setGoogleAccessToken($googleAccessToken)
    {
        $this->google_access_token = $googleAccessToken;

        return $this;
    }

    /**
     * Get googleAccessToken
     *
     * @return string
     */
    public function getGoogleAccessToken()
    {
        return $this->google_access_token;
    }

    /**
     * Set passwordUpdated
     *
     * @param boolean $passwordUpdated
     *
     * @return User
     */
    public function setPasswordUpdated($passwordUpdated)
    {
        $this->passwordUpdated = $passwordUpdated;

        return $this;
    }

    /**
     * Get passwordUpdated
     *
     * @return boolean
     */
    public function getPasswordUpdated()
    {
        return $this->passwordUpdated;
    }

    /**
     * Set profileUpdated
     *
     * @param boolean $profileUpdated
     *
     * @return User
     */
    public function setProfileUpdated($profileUpdated)
    {
        $this->profileUpdated = $profileUpdated;

        return $this;
    }

    /**
     * Get profileUpdated
     *
     * @return boolean
     */
    public function getProfileUpdated()
    {
        return $this->profileUpdated;
    }

    /**
     * Set teacher
     *
     * @param boolean $teacher
     *
     * @return User
     */
    public function setTeacher($teacher)
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * Get teacher
     *
     * @return boolean
     */
    public function getTeacher()
    {
        return $this->teacher;
    }
    
    public function getFullname() {
        if(is_null($this->getFirstname()) || is_null($this->getLastname()) )
            return $this->getUsername ();
        else
            return sprintf('%s %s', $this->getFirstname(), $this->getLastname());
    }
}
