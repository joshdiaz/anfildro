<?php

use App\File;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

defined('ANFILDRO_VERSION') || define('ANFILDRO_VERSION', '1.1.0');

$app->get('/', function () {
    if (env('ANFILDRO_CLIENT_ENABLED', false)) {
        return view('client');
    }

    return
        (new Response(
            implode(' ', ['joshdiaz/anfildro', ANFILDRO_VERSION]),
            200
        ))
        ->withHeaders(['Content-Type', 'text/plain'])
    ;
});

$app->get('files', function () {
    return File::all();
});

$app->post('files', function (Request $request) {
    return File::createFromRequest($request);
});

$app->get('files/{file_uuid}', function ($file_uuid) {
    return File::findByUuidOrFail($file_uuid)->asBinaryFileResponse();
});

$app->delete('files/{file_uuid}', function ($file_uuid, Request $request) {
    $file = File::findByUuidOrFail($file_uuid);
    if ($file->deletionPermitted($request->input('password')) == false) {
        throw new Exception('Incorrect password for deletion.', 403);
    }

    $file->delete();
    return new Response('', 204);
});
