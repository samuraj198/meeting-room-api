<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {

                $previous = $e->getPrevious();

                if ($previous instanceof ModelNotFoundException) {
                    $modelName = class_basename($previous->getModel());

                    $replacements = [
                        'User' => 'Пользователь не найден',
                        'Room' => 'Комната не найдена',
                        'Booking' => 'Бронирование не найдено'
                    ];

                    $message = $replacements[$modelName] ?? 'Запись не найдена';
                } else {
                    $message = 'Путь не найден';
                }

                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 404);
            }

            return null;
        });
    })->create();
