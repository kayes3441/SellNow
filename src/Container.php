<?php

namespace SellNow;

use Exception;
use SellNow\Contracts\AuthRepositoryInterface;
use SellNow\Contracts\UserRepositoryInterface;
use SellNow\Contracts\ProductRepositoryInterface;
use SellNow\Contracts\OrderRepositoryInterface;
use SellNow\Contracts\CartRepositoryInterface;
use SellNow\Contracts\PaymentProviderRepositoryInterface;

use SellNow\Repositories\AuthRepository;
use SellNow\Repositories\UserRepository;
use SellNow\Repositories\ProductRepository;
use SellNow\Repositories\OrderRepository;
use SellNow\Repositories\CartRepository;
use SellNow\Repositories\PaymentProviderRepository;

class Container
{
    /**
     * Interface => Concrete class
     */
    protected array $bindings = [];

    public function __construct()
    {
        $this->registerRepositories();
    }

    /**
     * Bind interface to repository class
     */
    private function registerRepositories(): void
    {
        $this->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->bind(CartRepositoryInterface::class, CartRepository::class);
        $this->bind(PaymentProviderRepositoryInterface::class, PaymentProviderRepository::class);
    }

    public function bind(string $interface, string $concrete): void
    {
        $this->bindings[$interface] = $concrete;
    }

    public function resolve(string $interface)
    {
        if (!isset($this->bindings[$interface])) {
            throw new Exception("{$interface} is not bound.");
        }

        $class = $this->bindings[$interface];

        return new $class();
    }
}
