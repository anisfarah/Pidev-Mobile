<?php

namespace App\Security;


use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Core\Exception\AuthenticationException;


use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    
    

    public function __construct(private UtilisateurRepository $userRepository,private UserPasswordHasherInterface $passwordHasher,private UrlGeneratorInterface $urlGenerator,private LoggerInterface $logger)
    {
        
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $request->getSession()->set(Security::LAST_USERNAME, $email);
        $password = $request->request->get('password', '');
         $csrfTokenBadge = new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token'));
        

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($password),
            [
                $csrfTokenBadge,
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        
        $user=$token->getUser();
        
        if ($user instanceof Utilisateur) {
            
        if(strpos($user->getIdRole()->getRole(), 'Client') !== false) {
            return new RedirectResponse($this->urlGenerator->generate('app_panier_index'));
        }
        elseif(strpos($user->getIdRole()->getRole(), 'Admin') !== false){
            
            return new RedirectResponse($this->urlGenerator->generate('app_dashboard_admin'));

        }
       
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
        // return new RedirectResponse($this->urlGenerator->generate('some_route'));
        throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

        // For example:
        return new RedirectResponse($this->urlGenerator->generate('afficher'));
        throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        
        $username = $request->request->get('email');
    $password = $request->request->get('password');
    $this->logger->error(sprintf('Authentication failure for user "%s" with password "%s": %s', $username, $password, $exception->getMessage()));
    
    $url = $this->urlGenerator->generate('app_login', ['error' => 'Votre authentification a échoué, veuillez réessayer, merci!']);
    return new RedirectResponse($url);
            
    }
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}