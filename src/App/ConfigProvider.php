<?php

declare(strict_types=1);

namespace App;

use Mezzio\Authentication\AuthenticationInterface;
use Mezzio\Authentication\Session\PhpSession;
use Mezzio\Authentication\UserRepository\PdoDatabase;
use Mezzio\Authentication\UserRepositoryInterface;
use Mezzio\Authorization\AuthorizationInterface;
use Mezzio\Authorization\Rbac\LaminasRbacFactory;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'aliases'    => [
                Model\DbRunnerInterface::class                => Model\DbRunner::class,
                Model\Media\MediaCommandInterface::class      => Model\Media\MediaCommand::class,
                Model\Media\MediaRepositoryInterface::class   => Model\Media\MediaRepository::class,
                Model\Termin\TerminCommandInterface::class    => Model\Termin\TerminCommand::class,
                Model\Termin\TerminRepositoryInterface::class => Model\Termin\TerminRepository::class,
                // auth
                AuthenticationInterface::class => PhpSession::class,
                UserRepositoryInterface::class => PdoDatabase::class,
            ],
            'invokables' => [
                //Handler\PingHandler::class => Handler\PingHandler::class,
            ],
            'factories'  => [
                // Auth
                AuthorizationInterface::class => LaminasRbacFactory::class,
                // DB
                Model\DbRunner::class => Model\DbRunnerFactory::class,
                // Model
                Model\Media\MediaCommand::class      => Model\Media\MediaCommandFactory::class,
                Model\Media\MediaRepository::class   => Model\Media\MediaRepositoryFactory::class,
                Model\Termin\TerminCommand::class    => Model\Termin\TerminCommandFactory::class,
                Model\Termin\TerminRepository::class => Model\Termin\TerminRepositoryFactory::class,
                // Service
                Service\HistoryService::class => Service\HistoryServiceFactory::class,
                Service\UrlpoolService::class => Service\UrlpoolServiceFactory::class,
                // Extension
                Plates\Extension\MediaExtension::class => Plates\Extension\MediaExtensionFactory::class,
                Plates\Extension\QuoteExtension::class => Plates\Extension\QuoteExtensionFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
                'app'         => ['templates/app'],
                'error'       => ['templates/error'],
                'layout'      => ['templates/layout'],
                'helper'      => ['templates/helper'],
                'partial'     => ['templates/partial'],
                'app-partial' => ['templates/app/partial'],
            ],
        ];
    }
}
