<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            \Illuminate\Support\Facades\Route::get('storage/{path}', function (string $path) {
                $cleanPath = str_replace(['../', '..\\'], '', $path);
                $filePath = storage_path('app/public/' . $cleanPath);

                if (!file_exists($filePath) || is_dir($filePath)) {
                    $publicFilePath = public_path('storage/' . $cleanPath);
                    if (file_exists($publicFilePath) && !is_dir($publicFilePath)) {
                        $filePath = $publicFilePath;
                    } else {
                        abort(404);
                    }
                }

                $mime = @mime_content_type($filePath) ?: 'application/octet-stream';
                $size = @filesize($filePath);
                $filename = basename($filePath);

                return response()->stream(function () use ($filePath) {
                    $stream = @fopen($filePath, 'rb');
                    if ($stream) {
                        fpassthru($stream);
                        fclose($stream);
                    }
                }, 200, array_filter([
                    'Content-Type' => $mime,
                    'Content-Length' => $size ?: null,
                    'Content-Disposition' => 'inline; filename="' . $filename . '"',
                    'Accept-Ranges' => 'bytes',
                ]));
            })->where('path', '.*')->name('storage.file');
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'login',
            'logout',
        ]);
        
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
