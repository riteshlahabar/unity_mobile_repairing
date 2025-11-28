<?php

namespace App\Services;

use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Services\Contracts\NotificationServiceInterface;
use Illuminate\Support\Facades\Log;

class CustomerService
{
    public function __construct(
        protected CustomerRepositoryInterface $repository,
        protected NotificationServiceInterface $notification
    ) {}

    /**
     * Create a new customer and send welcome message
     * 
     * @param array $validated
     * @return array
     */
    public function createCustomer(array $validated): array
    {
        try {
            // Generate customer ID
            $validated['customer_id'] = $this->repository->generateCustomerId();
            
            // Create customer
            $customer = $this->repository->create($validated);

            Log::info('Customer Created:', ['customer_id' => $customer->customer_id]);

            // Send WhatsApp welcome message
            $this->notification->sendCustomerWelcome($customer);

            return [
                'success' => true,
                'message' => 'Customer created successfully!',
                'customer_id' => $customer->customer_id,
                'customer' => $customer
            ];

        } catch (\Exception $e) {
            Log::error('Customer Creation Error:', ['message' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Update customer details
     * 
     * @param string $customerId
     * @param array $validated
     * @return array
     */
    public function updateCustomer(string $customerId, array $validated): array
    {
        $customer = $this->repository->findByCustomerId($customerId);
        $updated = $this->repository->update($customer->id, $validated);

        return [
            'success' => true,
            'message' => 'Customer updated successfully!',
            'customer_id' => $updated->customer_id,
            'customer' => $updated
        ];
    }

    /**
     * Delete customer
     * 
     * @param string $customerId
     * @return void
     */
    public function deleteCustomer(string $customerId): void
    {
        $customer = $this->repository->findByCustomerId($customerId);
        $this->repository->delete($customer->id);
    }

    /**
     * Check if customer exists by contact number
     * 
     * @param string $contactNo
     * @return array
     */
    public function checkContactExists(string $contactNo): array
    {
        $customer = $this->repository->findByContactNo($contactNo);
        
        if ($customer) {
            return [
                'exists' => true,
                'customer' => $customer
            ];
        }
        
        return [
            'exists' => false
        ];
    }
}
