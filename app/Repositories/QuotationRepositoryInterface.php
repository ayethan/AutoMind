<?php

namespace App\Repositories;

use App\Quotation;
use Illuminate\Database\Eloquent\Collection;

interface QuotationRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Quotation;
    public function create(array $data): Quotation;
    public function update(int $id, array $data): ?Quotation;
    public function delete(int $id): bool;
}
