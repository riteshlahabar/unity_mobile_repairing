<?php

namespace App\Services;

use App\Models\JobSheet;
use App\Models\Customer;
use App\Services\Contracts\NotificationServiceInterface;
use App\Services\WhatsAppService;
use App\Services\Contracts\PdfServiceInterface;
use Illuminate\Support\Facades\Log;

class JobSheetNotificationService implements NotificationServiceInterface
{
    public function __construct(
        protected WhatsAppService $whatsappService,
        protected PdfServiceInterface $pdfService
    ) {}

    /**
     * Send welcome message to new customer
     * 
     * @param Customer $customer
     * @return array
     */
    public function sendCustomerWelcome(Customer $customer): array
    {
        try {
            $this->whatsappService->sendByTitle('Customer Welcome', $customer->whatsapp_no, [
                'customer_name' => $customer->full_name,
                'customer_id' => $customer->customer_id,
            ]);

            Log::info('Customer Welcome Message Sent', [
                'customer_id' => $customer->customer_id,
                'phone' => $customer->whatsapp_no
            ]);

            return [
                'success' => true,
                'message' => 'Welcome message sent successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Customer Welcome Message Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Send device received message with PDF attachment
     * 
     * @param JobSheet $jobSheet
     * @return array
     */
    public function sendDeviceReceived(JobSheet $jobSheet): array
    {
        try {
<<<<<<< HEAD
           // Generate PDF once and check success
$pdfData = $this->pdfService->generate($jobSheet);

if (!$pdfData['success']) {
    Log::error('PDF Generation Failed in DeviceReceived', ['error' => $pdfData['error']]);
}

// Send WhatsApp message with jobsheet details including PDF URL (optional)
$this->whatsappService->sendByTitle('Device Received', $jobSheet->customer->whatsapp_no, [
    'customer_name' => $jobSheet->customer->full_name,
    'customer_id' => $jobSheet->customer->customer_id,
    'jobsheet_id' => $jobSheet->jobsheet_id,
    'device' => $jobSheet->company . ' ' . $jobSheet->model,
    'problem' => $jobSheet->problem_description,
    'estimated_cost' => number_format($jobSheet->estimated_cost, 2),
    'advance' => number_format($jobSheet->advance, 2),
    'balance' => number_format($jobSheet->balance, 2),
    'pdf_url' => $pdfData['url'] ?? null,
]);

// Send PDF file via WhatsApp separately, if generated successfully
if ($pdfData['success']) {
    $this->whatsappService->sendFile(
        $jobSheet->customer->whatsapp_no,
        $pdfData['path'],
        $pdfData['name']
    );
}

=======
            // Generate PDF
            $pdfData = $this->pdfService->generate($jobSheet);

            // Send WhatsApp message with jobsheet details
            $this->whatsappService->sendByTitle('Device Received', $jobSheet->customer->whatsapp_no, [
                'customer_name' => $jobSheet->customer->full_name,
                'customer_id' => $jobSheet->customer->customer_id,
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'device' => $jobSheet->company . ' ' . $jobSheet->model,
                'problem' => $jobSheet->problem_description,
                'estimated_cost' => number_format($jobSheet->estimated_cost, 2),
                'advance' => number_format($jobSheet->advance, 2),
                'balance' => number_format($jobSheet->balance, 2),
            ]);

            // Send PDF file
            $pdfData = $this->pdfService->generate($jobSheet);
            $this->whatsappService->sendFile(
                $jobSheet->customer->whatsapp_no,
                $pdfData['path'],
                $pdfData['name']
            );
>>>>>>> 0963cebdc0528a837022693382951a181cdac698

            Log::info('Device Received Message Sent', [
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'phone' => $jobSheet->customer->whatsapp_no,
                'pdf' => $pdfData['name'],
            ]);

            return [
                'success' => true,
                'message' => 'Device received message and PDF sent successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Device Received Message Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    public function sendCallInfoMessage(JobSheet $jobSheet): array
    {
        try {
            $this->whatsappService->sendByTitle('Call Info', $jobSheet->customer->whatsapp_no, [
                'customer_name' => $jobSheet->customer->full_name,
                'customer_id' => $jobSheet->customer->customer_id,
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'estimated_cost' => number_format($jobSheet->estimated_cost, 2),
                'device' => $jobSheet->company . ' ' . $jobSheet->model,
            ]);

            Log::info('Call Info Message Sent', [
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'phone' => $jobSheet->customer->whatsapp_no
            ]);

            return [
                'success' => true,
                'message' => 'Call info message sent successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Call Info Message Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Send Approval Pending message
     * 
     * @param JobSheet $jobSheet
     * @return array
     */
    public function sendApprovalPendingMessage(JobSheet $jobSheet): array
    {
        try {
            $this->whatsappService->sendByTitle('Approval Pending', $jobSheet->customer->whatsapp_no, [
                'customer_name' => $jobSheet->customer->full_name,
                'customer_id' => $jobSheet->customer->customer_id,
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'device' => $jobSheet->company . ' ' . $jobSheet->model,
                'estimated_cost' => number_format($jobSheet->estimated_cost, 2),
            ]);

            Log::info('Approval Pending Message Sent', [
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'phone' => $jobSheet->customer->whatsapp_no
            ]);

            return [
                'success' => true,
                'message' => 'Approval pending message sent successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Approval Pending Message Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Send Customer Approved message
     * 
     * @param JobSheet $jobSheet
     * @return array
     */
    public function sendCustomerApprovedMessage(JobSheet $jobSheet): array
    {
        try {
            $this->whatsappService->sendByTitle('Customer Approved', $jobSheet->customer->whatsapp_no, [
                'customer_name' => $jobSheet->customer->full_name,
                'customer_id' => $jobSheet->customer->customer_id,
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'device' => $jobSheet->company . ' ' . $jobSheet->model,
                'estimated_cost' => number_format($jobSheet->estimated_cost, 2),
            ]);

            Log::info('Customer Approved Message Sent', [
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'phone' => $jobSheet->customer->whatsapp_no
            ]);

            return [
                'success' => true,
                'message' => 'Customer approved message sent successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Customer Approved Message Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Send Not Okay Return message
     * 
     * @param JobSheet $jobSheet
     * @return array
     */
    public function sendNotOkayReturnMessage(JobSheet $jobSheet): array
    {
        try {
            $this->whatsappService->sendByTitle('Not Okay Return', $jobSheet->customer->whatsapp_no, [
                'customer_name' => $jobSheet->customer->full_name,
                'customer_id' => $jobSheet->customer->customer_id,
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'device' => $jobSheet->company . ' ' . $jobSheet->model,
            ]);

            Log::info('Not Okay Return Message Sent', [
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'phone' => $jobSheet->customer->whatsapp_no
            ]);

            return [
                'success' => true,
                'message' => 'Not okay return message sent successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Not Okay Return Message Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Send Return message
     * 
     * @param JobSheet $jobSheet
     * @return array
     */
    public function sendReturnMessage(JobSheet $jobSheet): array
    {
        try {
            $this->whatsappService->sendByTitle('Return', $jobSheet->customer->whatsapp_no, [
                'customer_name' => $jobSheet->customer->full_name,
                'customer_id' => $jobSheet->customer->customer_id,
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'device' => $jobSheet->company . ' ' . $jobSheet->model,
            ]);

            Log::info('Return Message Sent', [
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'phone' => $jobSheet->customer->whatsapp_no
            ]);

            return [
                'success' => true,
                'message' => 'Return message sent successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Return Message Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Send In Progress message
     * 
     * @param JobSheet $jobSheet
     * @return array
     */
    public function sendInProgressMessage(JobSheet $jobSheet): array
    {
        try {
            $this->whatsappService->sendByTitle('In Progress', $jobSheet->customer->whatsapp_no, [
                'customer_name' => $jobSheet->customer->full_name,
                'customer_id' => $jobSheet->customer->customer_id,
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'device' => $jobSheet->company . ' ' . $jobSheet->model,
                'technician' => $jobSheet->technician ?? 'Not Assigned',
            ]);

            Log::info('In Progress Message Sent', [
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'phone' => $jobSheet->customer->whatsapp_no
            ]);

            return [
                'success' => true,
                'message' => 'In progress message sent successfully'
            ];

        } catch (\Exception $e) {
            Log::error('In Progress Message Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    
    

    /**
     * Send repair completed notification
     * 
     * @param JobSheet $jobSheet
     * @return array
     */
    public function sendRepairCompleted(JobSheet $jobSheet): array
    {
        try {
            $this->whatsappService->sendByTitle('Ready', $jobSheet->customer->whatsapp_no, [
                'customer_name' => $jobSheet->customer->full_name,
                'customer_id' => $jobSheet->customer->customer_id,
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'device' => $jobSheet->company . ' ' . $jobSheet->model,
                'balance' => number_format($jobSheet->balance, 2),
            ]);

            Log::info('Repair Completed Message Sent', [
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'phone' => $jobSheet->customer->whatsapp_no
            ]);

            return [
                'success' => true,
                'message' => 'Repair completed message sent successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Repair Completed Message Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Send thank you message after delivery
     * 
     * @param JobSheet $jobSheet
     * @return array
     */
    public function sendThankYou(JobSheet $jobSheet): array
    {
        try {
            $this->whatsappService->sendByTitle('Thank You Message', $jobSheet->customer->whatsapp_no, [
                'customer_name' => $jobSheet->customer->full_name,
                'customer_id' => $jobSheet->customer->customer_id,
                'jobsheet_id' => $jobSheet->jobsheet_id,
            ]);

            Log::info('Thank You Message Sent', [
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'phone' => $jobSheet->customer->whatsapp_no
            ]);

            return [
                'success' => true,
                'message' => 'Thank you message sent successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Thank You Message Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate and send OTP for delivery verification
     * 
     * @param JobSheet $jobSheet
     * @return array
     */
    public function sendDeliveryOTP(JobSheet $jobSheet): array
    {
        try {
            // Check if already delivered
            if ($jobSheet->status === 'delivered') {
                return [
                    'success' => false,
                    'message' => 'JobSheet already delivered'
                ];
            }

            // Generate OTP and store in cache for 5 minutes
            $otp = $this->whatsappService->generateAndStoreOTP($jobSheet->jobsheet_id, 5);

            // Send OTP via WhatsApp
            $this->whatsappService->sendByTitle('OTP Notification', $jobSheet->customer->whatsapp_no, [
                'customer_name' => $jobSheet->customer->full_name,
                'otp' => $otp,
                'jobsheet_id' => $jobSheet->jobsheet_id,
            ]);

            Log::info('Delivery OTP Sent', [
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'phone' => $jobSheet->customer->whatsapp_no,
                'otp' => $otp
            ]);

            return [
                'success' => true,
                'message' => 'OTP sent to customer WhatsApp (valid for 5 minutes)',
                'jobsheet_id' => $jobSheet->jobsheet_id
            ];

        } catch (\Exception $e) {
            Log::error('Delivery OTP Send Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify delivery OTP
     * 
     * @param JobSheet $jobSheet
     * @param string $otp
     * @return array
     */
    public function verifyDeliveryOTP(JobSheet $jobSheet, string $otp): array
    {
        try {
            // Check if already verified
            if ($this->isOTPVerified($jobSheet->jobsheet_id)) {
                return [
                    'success' => true,
                    'message' => 'OTP already verified'
                ];
            }

            // Verify OTP
            $verification = $this->whatsappService->verifyOTP($jobSheet->jobsheet_id, $otp);

            if (!$verification['success']) {
                return [
                    'success' => false,
                    'message' => $verification['message']
                ];
            }

            Log::info('Delivery OTP Verified', [
                'jobsheet_id' => $jobSheet->jobsheet_id
            ]);

            return [
                'success' => true,
                'message' => 'OTP verified successfully'
            ];

        } catch (\Exception $e) {
            Log::error('OTP Verification Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Check if OTP is verified for a jobsheet
     * 
     * @param string $jobsheetId
     * @return bool
     */
    public function isOTPVerified(string $jobsheetId): bool
    {
        return $this->whatsappService->isOTPVerified($jobsheetId);
    }

    /**
     * Clear OTP from cache after delivery
     * 
     * @param JobSheet $jobSheet
     * @return void
     */
    public function clearOTP(JobSheet $jobSheet): void
    {
        $this->whatsappService->clearOTP($jobSheet->jobsheet_id);
    }
    
        /**
     * Send status-specific WhatsApp message for every status change
     * Routes to appropriate method based on status
     * 
     * @param JobSheet $jobSheet
     * @param string $status
     * @return array
     */
    public function sendStatusWhatsAppMessage(JobSheet $jobSheet, string $status): array
    {
        try {
            // Route to appropriate method based on status
            $statusMethods = [
                'in_progress' => 'sendInProgressMessage',
                'call_info' => 'sendCallInfoMessage',
                'approval_pending' => 'sendApprovalPendingMessage',
                'customer_approved' => 'sendCustomerApprovedMessage',
                'not_okay_return' => 'sendNotOkayReturnMessage',
                'return' => 'sendReturnMessage',
                'ready' => 'sendRepairCompleted',           
                'delivered' => 'sendDeliveredMessage', 
            ];

            $methodName = $statusMethods[$status] ?? null;

            if (!$methodName || !method_exists($this, $methodName)) {
                return [
                    'success' => false,
                    'message' => "No notification method defined for status: {$status}"
                ];
            }

            // Call the appropriate method
            return $this->$methodName($jobSheet);

        } catch (\Exception $e) {
            Log::error("Status WhatsApp Message Error for {$status}: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    
}