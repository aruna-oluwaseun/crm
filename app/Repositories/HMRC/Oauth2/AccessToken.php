<?php

namespace App\Repositories\HMRC\Oauth2;

use App\Models\Franchise;
use App\Models\GovCredential;
use App\Repositories\HMRC\Environment\Environment;
use App\Repositories\HMRC\Exceptions\InvalidVariableTypeException;
use App\Repositories\HMRC\Exceptions\MissingAccessTokenException;
use League\OAuth2\Client\Token\AccessTokenInterface;

class AccessToken
{
    const SESSION_KEY = 'hmrc_access_token';

    public static function exists(): bool
    {
        return session()->exists(self::SESSION_KEY);
    }

    /**
     * @return AccessTokenInterface|null
     */
    public static function get()
    {
        if(!self::exists()) {
            // Attempt refresh if token exists
            self::refreshToken();
        }

        return session()->exists(self::SESSION_KEY) ? unserialize(session()->get(self::SESSION_KEY)) : null;
    }

    /**
     * @param $accessToken
     * @param Franchise|null $db_instance
     * @throws InvalidVariableTypeException
     */
    public static function set($accessToken, Franchise $db_instance = null)
    {
        if ($accessToken instanceof AccessTokenInterface) {
            $accessToken = serialize($accessToken);
        }

        if (gettype($accessToken) !== 'string') {
            throw new InvalidVariableTypeException('Access token must be string or implement AccessTokenInterface.');
        }

        session()->put(self::SESSION_KEY, $accessToken);

        if($db_instance) {

            $environment = Environment::getInstance();
            if($environment->isSandbox())
            {
                $db_instance->update(['hmrc_test_access_token' => $accessToken]);
            }
            else
            {
                $db_instance->update(['hmrc_access_token' => $accessToken]);
            }
        }
    }

    /**
     * @throws MissingAccessTokenException
     *
     * @return bool
     */
    public static function hasExpired(): bool
    {
        /** @var \League\OAuth2\Client\Token\AccessToken $accessToken */
        $accessToken = self::get();

        if (is_null($accessToken)) {
            throw new MissingAccessTokenException("Access token doesn't exists.");
        }

        return $accessToken->hasExpired();
    }

    /**
     * Refresh token
     * @throws InvalidVariableTypeException
     * @throws MissingAccessTokenException
     */
    public static function refreshToken()
    {
        $franchise = Franchise::find(current_user_franchise_id());

        if(!$franchise) {
            return;
        }

        $environment = Environment::getInstance();
        if($environment->isLive() && $franchise->hmrc_access_token) {
            self::set($franchise->hmrc_access_token);

            $provider = new Provider(
                env('HMRC_CLIENT'),
                env('HMRC_SECRET'),
                url('vat/handle-response')
            );
        }

        if($environment->isSandbox() && $franchise->hmrc_test_access_token) {
            self::set($franchise->hmrc_test_access_token);

            $provider = new Provider(
                env('TEST_HMRC_CLIENT'),
                env('TEST_HMRC_SECRET'),
                url('vat/handle-response')
            );
        }

        if(isset($provider) && $token = self::get()) {
            if($token->hasExpired()) {
                $newAccessToken = $provider->getAccessToken('refresh_token', [
                    'refresh_token' => $token->getRefreshToken(),
                ]);

                self::set($newAccessToken, $franchise);
            }
        }

    }

    /**
     * Remove token from session
     */
    public static function destroyToken()
    {
        if(self::exists())
        {
            session()->forget(self::SESSION_KEY);
        }
    }
}
