<?php

namespace SellNow;

use Exception;
use ReflectionClass;
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
    
    /**
     * Resolved instances (singletons)
     */
    protected array $instances = [];

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

    public function get(string $interface)
    {
        // Return cached instance if exists
        if (isset($this->instances[$interface])) {
            return $this->instances[$interface];
        }

        // Resolve and cache
        $instance = $this->resolve($interface);
        $this->instances[$interface] = $instance;
        
        return $instance;
    }

    public function resolve(string $interface)
    {
        if (!isset($this->bindings[$interface])) {
            throw new Exception("{$interface} is not bound.");
        }

        $class = $this->bindings[$interface];

        return $this->build($class);
    }

    /**
     * Build an instance with automatic dependency injection
     */
    public function build(string $class)
    {
        $reflection = new ReflectionClass($class);

        // If no constructor, just instantiate
        $constructor = $reflection->getConstructor();
        if (!$constructor) {
            return new $class();
        }

        // Get constructor parameters
        $parameters = $constructor->getParameters();
        if (empty($parameters)) {
            return new $class();
        }

        // Resolve dependencies
        $dependencies = [];
        foreach ($parameters as $parameter) {
            $type = $parameter->getType();
            
            if (!$type || $type->isBuiltin()) {
                throw new Exception("Cannot auto-resolve parameter \${$parameter->getName()} in {$class}");
            }

            $typeName = $type->getName();
            
            // Try to resolve the dependency
            if (isset($this->bindings[$typeName])) {
                $dependencies[] = $this->get($typeName);
            } elseif (class_exists($typeName)) {
                // Try to instantiate the class directly
                $dependencies[] = $this->build($typeName);
            } else {
                throw new Exception("Cannot resolve dependency {$typeName} for {$class}");
            }
        }

        return $reflection->newInstanceArgs($dependencies);
    }
}
