<?php

namespace App\Repositories;

use App\Quotation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EloquentQuotationRepository implements QuotationRepositoryInterface
{
    public function all(): Collection
    {
        return Quotation::all();
    }

    public function find(int $id): ?Quotation
    {
        return Quotation::find($id);
    }

    public function create(array $data): Quotation
    {
        return Quotation::create($data);
    }

    public function update(int $id, array $data): ?Quotation
    {
        $quotation = Quotation::find($id);
        if (!$quotation) {
            return null;
        }
        $quotation->update($data);
        return $quotation;
    }

    public function delete(int $id): bool
    {
        $quotation = Quotation::find($id);
        if (!$quotation) {
            return false;
        }
        return $quotation->delete();
    }
}
