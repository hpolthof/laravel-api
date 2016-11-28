<?php namespace Hpolthof\LaravelAPI\Middleware;

use Hpolthof\LaravelAPI\Exceptions\AccessDeniedException;
use Hpolthof\LaravelAPI\Exceptions\APIException;
use Hpolthof\LaravelAPI\Exceptions\BadRequestException;
use Hpolthof\LaravelAPI\Exceptions\BindingException;
use Hpolthof\LaravelAPI\Exceptions\HttpStatusException;
use Hpolthof\LaravelAPI\Exceptions\NotFoundException;
use Hpolthof\LaravelAPI\Layout;
use Illuminate\Http\Response;

class APIErrors
{
    /**
     * Handle thrown exceptions as part of the API
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        try {
            $response = $next($request);
            if($response->exception instanceof \Exception) {
                throw $response->exception;
            }
        }
        catch (HttpStatusException $e) {
            return Layout::responseMessage($e->getMessage(), $e->getStatusCode());
        }
        catch (BindingException $e) {
            return Layout::responseMessage($e->getMessage(), Response::HTTP_NOT_IMPLEMENTED);
        }
        catch (APIException $e) {
            return Layout::responseMessage($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        catch (\Exception $e) {
            return Layout::responseMessage(Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}