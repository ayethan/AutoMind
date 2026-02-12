<?php

namespace App\Repositories;

use App\Customer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CustomerRepository
{
    /**
     * Get all customers with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function paginateCustomers(): LengthAwarePaginator
    {
        return Customer::paginate(config('tinyerp.default-pagination'));
    }

    /**
     * Get all customers.
     *
     * @return Collection
     */
    public function getAllCustomers(): Collection
    {
        return Customer::all();
    }

    /**
     * Create a new customer.
     *
     * @param array $data
     * @return Customer
     */
    public function createCustomer(array $data): Customer
    {
        return Customer::create($data);
    }

    /**
     * Find a customer by its model instance.
     *
     * @param Customer $customer
     * @return Customer
     */
    public function findCustomer(Customer $customer): Customer
    {
        return $customer;
    }

    /**
     * Update an existing customer.
     *
     * @param Customer $customer
     * @param array $data
     * @return Customer
     */
    public function updateCustomer(Customer $customer, array $data): Customer
    {
        $customer->fill($data)->save();
        return $customer;
    }

    /**
     * Delete a customer.
     *
     * @param Customer $customer
     * @return bool|null
     */
    public function deleteCustomer(Customer $customer): ?bool
    {
        return $customer->delete();
    }
}
