<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Middleware\HandleCors;

return Application::configure(basePath: dirname(__DIR__))->withRouting(web: __DIR__ . '/../routes/web.php', api: __DIR__ . '/../routes/api.php', commands: __DIR__ . '/../routes/console.php', health: '/up')
    ->withMiddleware(function (Middleware $middleware): void {
    //
})
    ->withExceptions(function (Exceptions $exceptions): void {
    // Handle ModelNotFoundException
    $exceptions->renderable(function (Throwable $e, $request) {
        Log::info('Exception caught: ' . get_class($e));
        Log::info('Message: ' . $e->getMessage());
    });
    $exceptions->render(function (ModelNotFoundException $e, $request) {
        return response()->json([
            'message' => 'Resource not found'
        ], 404);
    });
    $exceptions->render(function (NotFoundHttpException $e, $request) {
        if ($e->getPrevious() instanceof ModelNotFoundException) {
            Log::info('Wrapped NotFoundHttpException triggered from ModelNotFoundException');
            return response()->json([
                'message' => 'Resource not found'
            ], 404);
        }
    });
})
    ->create();
