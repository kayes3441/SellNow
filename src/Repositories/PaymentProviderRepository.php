<?php

namespace SellNow\Repositories;

use SellNow\Contracts\PaymentProviderRepositoryInterface;
use SellNow\Models\PaymentProvider;

class PaymentProviderRepository implements PaymentProviderRepositoryInterface
{
    public function __construct(
        public PaymentProvider $paymentProvider,
    )
    {

    }
    public function findById(int|string $id): ?array
    {
        return $this->paymentProvider->find($id);
    }

    public function findByParams(array $params, array $relations = []): ?array
    {
        return $this->paymentProvider->findBy(array_keys($params)[0], array_values($params)[0]);
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = []): array
    {
        return $this->paymentProvider->whereUsingArray($filters);

    }

    public function add(array $data): string
    {
        return $this->paymentProvider->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->paymentProvider->updateById($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->paymentProvider->destroy($id);
    }
}
