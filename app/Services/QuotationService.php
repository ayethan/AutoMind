<?php

namespace App\Services;

use App\Repositories\QuotationRepositoryInterface;
use App\Product;
use App\Service;
use App\Quotation;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class QuotationService
{
    protected $quotationRepository;

    public function __construct(QuotationRepositoryInterface $quotationRepository)
    {
        $this->quotationRepository = $quotationRepository;
    }

    public function getAllQuotations()
    {
        return $this->quotationRepository->all();
    }

    public function getQuotationById(int $id)
    {
        return $this->quotationRepository->find($id);
    }

    public function createQuotation(array $data): Quotation
    {
        DB::beginTransaction();
        try {
            // Basic quotation data
            $quotationData = [
                'customer_id' => $data['customer_id'],
                'user_id' => $data['user_id'] ?? auth()->id(), // Use authenticated user if not provided
                'quotation_date' => $data['quotation_date'] ?? now(),
                'expiration_date' => $data['expiration_date'] ?? null,
                'status' => $data['status'] ?? 'draft',
                'title' => $data['title'] ?? null,
                'notes' => $data['notes'] ?? null,
            ];

            $quotation = $this->quotationRepository->create($quotationData);

            // Attach products and services if provided
            $this->syncQuotationItems($quotation, $data);

            // Calculate totals
            $this->calculateTotals($quotation);

            DB::commit();
            return $quotation;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new InvalidArgumentException('Unable to create quotation: ' . $e->getMessage());
        }
    }

    public function updateQuotation(int $id, array $data): Quotation
    {
        DB::beginTransaction();
        try {
            $quotation = $this->quotationRepository->find($id);
            if (!$quotation) {
                throw new ModelNotFoundException('Quotation not found.');
            }

            $quotation->update([
                'customer_id' => $data['customer_id'] ?? $quotation->customer_id,
                'user_id' => $data['user_id'] ?? $quotation->user_id,
                'quotation_date' => $data['quotation_date'] ?? $quotation->quotation_date,
                'expiration_date' => $data['expiration_date'] ?? $quotation->expiration_date,
                'status' => $data['status'] ?? $quotation->status,
                'title' => $data['title'] ?? $quotation->title,
                'notes' => $data['notes'] ?? $quotation->notes,
            ]);

            // Sync products and services if provided
            $this->syncQuotationItems($quotation, $data);

            // Recalculate totals
            $this->calculateTotals($quotation);

            DB::commit();
            return $quotation;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new InvalidArgumentException('Unable to update quotation: ' . $e->getMessage());
        }
    }

    public function deleteQuotation(int $id): bool
    {
        return $this->quotationRepository->delete($id);
    }

    /**
     * Helper to sync products and services for a quotation.
     */
    protected function syncQuotationItems(Quotation $quotation, array $data)
    {
        $productsToAttach = [];
        $servicesToAttach = [];

        if (isset($data['products']) && is_array($data['products'])) {
            foreach ($data['products'] as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) {
                    throw new InvalidArgumentException("Product with ID {$item['product_id']} not found.");
                }
                $price = $item['price'] ?? $product->price; // Use provided price or default product price
                $quantity = $item['quantity'] ?? 1;
                $discount = $item['discount'] ?? 0;
                $total = ($price * $quantity) - $discount;

                $productsToAttach[$item['product_id']] = [
                    'quantity' => $quantity,
                    'price' => $price,
                    'discount' => $discount,
                    'total' => $total,
                ];
            }
        }
        $quotation->products()->sync($productsToAttach);

        if (isset($data['services']) && is_array($data['services'])) {
            foreach ($data['services'] as $item) {
                $service = Service::find($item['service_id']);
                if (!$service) {
                    throw new InvalidArgumentException("Service with ID {$item['service_id']} not found.");
                }
                $price = $item['price'] ?? $service->price; // Use provided price or default service price
                $quantity = $item['quantity'] ?? 1;
                $discount = $item['discount'] ?? 0;
                $total = ($price * $quantity) - $discount;

                $servicesToAttach[$item['service_id']] = [
                    'quantity' => $quantity,
                    'price' => $price,
                    'discount' => $discount,
                    'total' => $total,
                ];
            }
        }
        $quotation->services()->sync($servicesToAttach);
    }

    /**
     * Helper to calculate and update quotation totals.
     */
    protected function calculateTotals(Quotation $quotation)
    {
        $subtotal = 0;
        $discountAmount = 0;
        $taxAmount = 0; // Assuming tax calculation might be external or a fixed rate
        $totalAmount = 0;

        foreach ($quotation->products as $product) {
            $subtotal += $product->pivot->price * $product->pivot->quantity;
            $discountAmount += $product->pivot->discount;
        }

        foreach ($quotation->services as $service) {
            $subtotal += $service->pivot->price * $service->pivot->quantity;
            $discountAmount += $service->pivot->discount;
        }

        // Apply a global discount if exists in $quotation (not implemented yet, but for future)
        // $discountAmount += $quotation->global_discount_percentage ? ($subtotal * $quotation->global_discount_percentage / 100) : 0;

        // Apply tax (example: fixed 10% tax on subtotal after discount)
        // For a real application, tax would be more complex (e.g., tax types, regional taxes)
        // $taxAmount = ($subtotal - $discountAmount) * 0.10;

        $totalAmount = $subtotal - $discountAmount + $taxAmount;

        $quotation->update([
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
        ]);
    }
}
