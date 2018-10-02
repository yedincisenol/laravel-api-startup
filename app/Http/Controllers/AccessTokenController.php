<?php

namespace App\Http\Controllers;

use Laravel\Passport\Http\Controllers\AccessTokenController as PassportAccessTokenController;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response as Psr7Response;
use Exception;
use Throwable;
use Illuminate\Http\Response;
use Illuminate\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class AccessTokenController extends PassportAccessTokenController
{
    /**
     * Perform the given callback with exception handling.
     *
     * @param  \Closure  $callback
     * @return \Illuminate\Http\Response|\Psr\Http\Message\ResponseInterface
     */
    protected function withErrorHandling($callback)
    {
        try {
            return $callback();
        } catch (OAuthServerException $e) {

            if ($e->getCode() == 6) {
                response([
                    'message'   => trans('user.wrong_password')
                ], 401)->send();

                exit();
            }

            $this->exceptionHandler()->report($e);

            return $e->generateHttpResponse(new Psr7Response);
        } catch (Exception $e) {
            $this->exceptionHandler()->report($e);

            return new Response($e->getMessage(), 500);
        } catch (Throwable $e) {
            $this->exceptionHandler()->report(new FatalThrowableError($e));

            return new Response($e->getMessage(), 500);
        }
    }

    /**
     * Get the exception handler instance.
     *
     * @return \Illuminate\Contracts\Debug\ExceptionHandler
     */
    protected function exceptionHandler()
    {
        return Container::getInstance()->make(ExceptionHandler::class);
    }

    /**
     * Authorize a client to access the user's account.
     *
     * @param  ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     */
    public function issueToken(ServerRequestInterface $request)
    {
        return $this->withErrorHandling(function () use ($request) {
            return $this->server->respondToAccessTokenRequest($request, new Psr7Response);
        });
    }
}