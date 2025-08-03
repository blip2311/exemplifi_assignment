<?php
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('tasks/{task}/comments', [
    CommentController::class,
    'store'
])->name('tasks.comments.store');
Route::get('tasks/{task}/comments', [
    CommentController::class,
    'index'
])->name('tasks.comments.index');
Route::apiResource('tasks', TaskController::class)->except([
    'show'
]);
Route::get('route-map', function () {
    $routes = collect(Route::getRoutes())->map(function ($route) {
        return [
            $route->getName() => [
                'url' => $route->uri,
                'methods' => $route->methods()
            ]
        ];
    })->toArray();

    return response()->json($routes);
})->name('route-map');
