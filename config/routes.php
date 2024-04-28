<?php

declare(strict_types=1);

use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/:id', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Mezzio\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {

    // root

    $app->get('/', [
        App\Handler\Termin\DefTerminCalendarHandler::class,
    ], 'default.root');

    // app cleanup

    $app->route('/cleanup', [
        Mezzio\Authentication\AuthenticationMiddleware::class,
        Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Middleware\TemplateDefaultsMiddleware::class,
        App\Handler\Home\DefAppCleanupHandler::class,
    ], ['GET'], 'default.app.cleanup');

    // app media

    $app->route('/media/{p1:[0-9]+}', [
        App\Handler\Media\DefMediaIndexHandler::class,
    ], ['GET'], 'default.media.index');

    // app termin

    $app->route('/show/{p1:[0-9]+}', [
        App\Handler\Termin\DefTerminShowHandler::class,
    ], ['GET'], 'default.termin.show');

    $app->get('/search', [
        App\Handler\Termin\DefTerminSearchHandler::class,
    ], 'default.termin.search');

    $app->route('/ics[/{p1:[0-9]+}]', [
        App\Handler\Termin\DefTerminIcsHandler::class,
    ], ['GET'], 'default.termin.ics');

    // manage home

    $app->route('/manage/home-read[/{action:index|initconfig|phpinfo|tabellen|stats|test}]', [
        App\Page\Home\MngHomeReadPage::class,
    ], ['GET'], 'manage.home.read');

    // manage media

    $app->route('/manage/media-read[/{action:index|version}[/{p1:[0-9]+}]]', [
        App\Page\Media\MngMediaReadPage::class,
    ], ['GET'], 'manage.media.read');

    $app->route('/manage/media-write[/{action:insert|update|delete}[/{p1:[0-9]+}]]', [
        App\Page\Media\MngMediaWritePage::class,
    ], ['GET', 'POST'], 'manage.media.write');

    // manage termin

    $app->route('/manage/termin-calendar[/{action:index}]', [
        App\Page\Termin\MngTerminCalendarPage::class,
    ], ['GET'], 'manage.termin.calendar');

    $app->route('/manage/termin-search[/{action:index}]', [
        App\Page\Termin\MngTerminSearchPage::class,
    ], ['GET'], 'manage.termin.search');

    $app->route('/manage/termin-write[/{action:insert|update|delete|copy}[/{p1:[0-9\-]+}]]', [
        App\Page\Termin\MngTerminWritePage::class,
    ], ['GET', 'POST'], 'manage.termin.write');

    // app auth

    $app->route('/app-login', [
        App\Handler\Auth\DefAppLoginHandler::class,
    ], ['GET', 'POST'], 'default.app.login');

    $app->route('/app-logout', [
        App\Handler\Auth\DefAppLogoutHandler::class,
    ], ['GET'], 'default.app.logout');
};
