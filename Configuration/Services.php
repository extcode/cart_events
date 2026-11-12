<?php

declare(strict_types=1);

use Extcode\CartEvents\Hooks\DataHandler;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator, ContainerBuilder $containerBuilder) {
    $services = $containerConfigurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
    ;

    $services
        ->load(
            'Extcode\\CartEvents\\',
            '../Classes/*'
        )
    ;

    $services
        ->set(DataHandler::class)
        ->public()
    ;

    $containerConfigurator->import('Services/EventListeners.php');
};
