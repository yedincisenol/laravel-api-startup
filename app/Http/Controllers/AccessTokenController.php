<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Response;
use Laravel\Passport\Http\Controllers\AccessTokenController as PassportAccessTokenController;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Throwable;
use Zend\Diactoros\Response as Psr7Response;

class AccessTokenController extends PassportAccessTokenController
{
    /**
     * Perform the given callback with exception handling.
     *
     * @param \Closure $callback
     *
     * @return \Illuminate\Http\Response|\Psr\Http\Message\ResponseInterface
     */
    protected function withErrorHandling($callback)
    {
        try {
            return $callback();
        } catch (OAuthServerException $e) {
            if ($e->getCode() == 6) {
                response([
                    'message'   => trans('user.wrong_password'),
                ], 401)->send();

                exit();
            }

            $this->exceptionHandler()->report($e);

            return $e->generateHttpResponse(new Psr7Response());
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
     * @param ServerRequestInterface $request
     *
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function issueToken(ServerRequestInterface $request)
    {
        return $this->withErrorHandling(function () use ($request) {
            return $this->server->respondToAccessTokenRequest($request, new Psr7Response());
        });
    }
}
