<?php

namespace App\Services;

use App\Customer;
use App\Repositories\CustomerRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CustomerService
{
    protected $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Get all customers with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function paginateCustomers(): LengthAwarePaginator
    {
        return $this->customerRepository->paginateCustomers();
    }

    /**
     * Get all customers.
     *
     * @return Collection
     */
    public function getAllCustomers(): Collection
    {
        return $this->customerRepository->getAllCustomers();
    }

    /**
     * Create a new customer.
     *
     * @param array $data
     * @return Customer
     */
    public function createCustomer(array $data): Customer
    {
        return $this->customerRepository->createCustomer($data);
    }

    /**
     * Find a customer by its model instance.
     *
     * @param Customer $customer
     * @return Customer
     */
    public function findCustomer(Customer $customer): Customer
    {
        return $this->customerRepository->findCustomer($customer);
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
        return $this->customerRepository->updateCustomer($customer, $data);
    }

    /**
     * Delete a customer.
     *
     * @param Customer $customer
     * @return bool|null
     */
    public function deleteCustomer(Customer $customer): ?bool
    {
        return $this->customerRepository->deleteCustomer($customer);
    }
}
