<?php

declare(strict_types=1);

use Extcode\Cart\Event\CheckProductAvailabilityEvent;
use Extcode\Cart\Event\Order\StockEvent;
use Extcode\Cart\Event\RetrieveProductsFromRequestEvent as CartRetrieveProductsFromRequestEvent;
use Extcode\CartEvents\EventListener\CheckProductAvailability;
use Extcode\CartEvents\EventListener\Order\Stock\FlushCache;
use Extcode\CartEvents\EventListener\Order\Stock\HandleStock;
use Extcode\CartEvents\EventListener\RetrieveProductsFromRequest;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services
        ->set(HandleStock::class)
        ->tag(
            'event.listener',
            [
                'event' => StockEvent::class,
                'identifier' => 'cart-events--order--stock--handle-stock',
            ]
        )
    ;

    $services
        ->set(FlushCache::class)
        ->tag(
            'event.listener',
            [
                'event' => StockEvent::class,
                'identifier' => 'cart-events--order--stock--flush-cache',
                'after' => 'cart-events--order--stock--handle-stock',
            ]
        )
    ;

    $services
        ->set(RetrieveProductsFromRequest::class)
        ->tag(
            'event.listener',
            [
                'event' => CartRetrieveProductsFromRequestEvent::class,
                'identifier' => 'cart-events--retrieve-products-from-request',
            ]
        )
    ;

    $services
        ->set(CheckProductAvailability::class)
        ->tag(
            'event.listener',
            [
                'event' => CheckProductAvailabilityEvent::class,
                'identifier' => 'cart-events--check-product-availability',
            ]
        )
    ;
};
