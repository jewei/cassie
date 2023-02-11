<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [

    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [

    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (\Throwable $e) {
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @return InertiaResponse|SymfonyResponse
     */
    public function render($request, \Throwable $e)
    {
        /** @var \Illuminate\Http\Response $response */
        $response = parent::render($request, $e);
        $status = $response->status();

        return ! in_array($status, [500, 503, 404, 403])
            ? $response
            : Inertia::render('Error/Error', [
                'status' => $status,
                'statusText' => $this->shortErrorMessage($status),
                'message' => $this->friendlyErrorMessages($status),
            ]);
    }

    private function shortErrorMessage(int $code): string
    {
        return match ($code) {
            403 => 'Resource is forbidden',
            404 => 'Page not found',
            500 => 'Server-side error',
            503 => 'Service unavailable',
            default => 'Whoops!',
        };
    }

    private function friendlyErrorMessages(int $code): string
    {
        return match ($code) {
            403 => 'Whoops! It looks like you don’t have permission to access this page. If you think this is a mistake, please contact the website administrator for assistance.',
            404 => 'Oops! We couldn’t find the page you were looking for. It might have been moved, deleted, or simply unavailable. Don’t worry though, there are many other pages on this website that might interest you.',
            500 => 'Whoops! Something went wrong on our end. We’re working on fixing it as quickly as we can. Please try again later or contact our support team if the problem persists.',
            503 => 'Sorry! The website is currently undergoing some maintenance or is experiencing high traffic. We’re working hard to get things back up and running as soon as possible. Please check back later.',
            default => 'Something went wrong',
        };
    }
}
