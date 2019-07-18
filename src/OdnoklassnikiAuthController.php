<?php

namespace Dem13n\Auth\Odnoklassniki;

use Exception;
use Flarum\Forum\Auth\Registration;
use Flarum\Forum\Auth\ResponseFactory;
use Flarum\Http\UrlGenerator;
use Flarum\Settings\SettingsRepositoryInterface;
use Aego\OAuth2\Client\Provider\Odnoklassniki;
use Aego\OAuth2\Client\Provider\OdnoklassnikiResourceOwner;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Behat\Transliterator\Transliterator;

class OdnoklassnikiAuthController implements RequestHandlerInterface
{
    protected $response;

    protected $settings;

    protected $url;

    public function __construct(ResponseFactory $response, SettingsRepositoryInterface $settings, UrlGenerator $url)
    {
        $this->response = $response;
        $this->settings = $settings;
        $this->url = $url;
    }

    public function handle(Request $request): ResponseInterface
    {
        $redirectUri = $this->url->to('forum')->route('auth.ok');

        $provider = new Odnoklassniki([
            'clientId' => $this->settings->get('dem13n-auth-ok.app_id'),
            'clientPublic' => $this->settings->get('dem13n-auth-ok.app_public'),
            'clientSecret' => $this->settings->get('dem13n-auth-ok.app_secret'),
            'redirectUri' => $redirectUri
        ]);

        $session = $request->getAttribute('session');
        $queryParams = $request->getQueryParams();

        $code = array_get($queryParams, 'code');

        if (!$code) {
            $authUrl = $provider->getAuthorizationUrl();
            $session->put('oauth2state', $provider->getState());

            return new RedirectResponse($authUrl);
        }

        $state = array_get($queryParams, 'state');

        if (!$state || $state !== $session->get('oauth2state')) {
            $session->remove('oauth2state');

            throw new Exception('Invalid state');
        }

        $token = $provider->getAccessToken('authorization_code', compact('code'));

        $user = $provider->getResourceOwner($token);

        return $this->response->make(
            'ok', $user->getId(),
            function (Registration $registration) use ($user) {
                $username = ucwords(Transliterator::transliterate($user->getName()), '-');
                $registration
                    ->suggestEmail('')
                    ->provideAvatar($user->getImageUrl())
                    ->suggestUsername($username)
                    ->setPayload($user->toArray());
            }
        );
    }
}
