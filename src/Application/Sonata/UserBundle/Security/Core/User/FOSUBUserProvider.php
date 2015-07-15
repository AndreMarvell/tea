<?php
namespace Application\Sonata\UserBundle\Security\Core\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use TeaCampus\CommonBundle\Services\ImageDownloader;

class FOSUBUserProvider extends BaseClass
{
     private $downloader;
    
    public function __construct(ImageDownloader $downloader, UserManagerInterface $userManager, array $properties)
    {
        $this->downloader = $downloader;
        parent::__construct( $userManager, $properties);
    }

    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();

        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();
        
        

        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';

        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }

        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());

        $this->userManager->updateUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        if ($response->getResponse() == null) {
          throw new \Exception('Couldn\'t retrieve the API response of : '. $response->getResourceOwner()->getName());
        }
        $username   = $response->getUsername();
        
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));
        
        //when the user is registrating
        if (null === $user) {
            $user = $this->userManager->findUserBy(array('email' => $response->getEmail()));
            if(null === $user){
                $service = $response->getResourceOwner()->getName();
                $setter = 'set'.ucfirst($service);
                $setter_id = $setter.'Id';
                $setter_token = $setter.'AccessToken';
                // create new user here
                $user = $this->userManager->createUser();
                $user->$setter_id($username);
                $user->$setter_token($response->getAccessToken());
                //I have set all requested data with the user's username
                //modify here with relevant data
                
                // Pour avoir un username stylé
                $customUsername = explode("@",$response->getEmail())[0].ucfirst($service);
                
                $user->setUsername($customUsername);
                $user->setEmail($response->getEmail());
                $user->setFirstName($response->getFirstName());
                $user->setLastName($response->getLastName());
                $user->setEmail($response->getEmail());
                $user->setPasswordUpdated(false);
                if($service=="google"){
                    $user->setAvatar($this->downloader->downloadFile(
                            $response->getProfilePicture(),
                            $response->getRealName(),
                            'avatar',
                            'google_'.$username.'.jpg'
                        ));
                }elseif ($service=="facebook") {
                    $user->setAvatar($this->downloader->downloadFile(
                            'http://graph.facebook.com/'.$username.'/picture?type=large',
                            $response->getRealName(),
                            'avatar',
                            'fb_'.$username.'.jpg'
                        ));    
                }

                $user->setPlainPassword($username);
                $user->setEnabled(true);
                $this->userManager->updateUser($user);
                return $user;
            }
        }

        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);

        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';

        //update access token
        $user->$setter($response->getAccessToken());

        return $user;
    }


}