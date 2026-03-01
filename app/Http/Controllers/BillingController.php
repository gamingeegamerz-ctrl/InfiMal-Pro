<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user->hasPaid()) {
            return view('billing.payment', [
                'user' => $user,
                'price' => 299.00,
                'currency' => 'USD',
                'payment_status' => $user->payment_status ?? 'free',
                'plan' => $user->plan_name ?? 'Free',
                'paid_at' => $user->paid_at,
            ]);
        }

        return view('billing.index', [
            'user' => $user,
            'plan' => $user->plan_name ?? 'Free',
            'paid_at' => $user->paid_at,
            'payment_status' => $user->payment_status ?? 'paid',
            'status' => 'active',
            'payment_date' => $user->paid_at ?? $user->payment_date,
            'expiry_date' => $user->plan_expiry_date,
            'amount_paid' => $user->payment_amount,
            'transaction_id' => $user->transaction_id,
            'invoices' => $this->getInvoices($user->id),
        ]);
    }

    private function getInvoices($userId)
    {
        return DB::table('invoices')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:stripe,paypal,razorpay',
            'amount' => 'required|numeric|min:299'
        ]);

        $user = Auth::user();
        $amount = 299.00;

        $paymentResult = $this->processPaymentGateway(
            $request->payment_method,
            $amount,
            $user
        );

        if ($paymentResult['success']) {
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'payment_status' => 'paid',
                    'plan_name' => 'Lifetime',
                    'paid_at' => now(),
                    'payment_date' => now(),
                    'payment_amount' => $amount,
                    'transaction_id' => $paymentResult['transaction_id'],
                    'plan_expiry_date' => now()->addYears(100),
                    'updated_at' => now()
                ]);

            $this->createInvoice($user->id, $amount, $paymentResult);

            return response()->json([
                'success' => true,
                'message' => 'Payment successful! Your account is now active.',
                'redirect' => '/dashboard'
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => $paymentResult['error'] ?? 'Payment failed'
        ], 400);
    }

    private function processPaymentGateway($method, $amount, $user)
    {
        $transactionId = 'TXN-' . strtoupper(uniqid());

        return [
            'success' => true,
            'transaction_id' => $transactionId,
            'gateway_response' => [
                'status' => 'completed',
                'method' => $method,
                'amount' => $amount,
                'currency' => 'USD'
            ]
        ];
    }

    private function createInvoice($userId, $amount, $paymentResult)
    {
        $invoiceId = 'INV-' . date('Ymd') . '-' . strtoupper(uniqid());

        DB::table('invoices')->insert([
            'user_id' => $userId,
            'invoice_id' => $invoiceId,
            'transaction_id' => $paymentResult['transaction_id'],
            'amount' => $amount,
            'currency' => 'USD',
            'status' => 'paid',
            'billing_details' => json_encode([
                'product' => 'InfiMal Lifetime License',
                'description' => 'One-time payment for lifetime access',
                'features' => [
                    'Unlimited Lists',
                    'Unlimited Subscribers',
                    'Unlimited Campaigns',
                    'Priority Support',
                    'All Features Included'
                ]
            ]),
            'payment_method' => $paymentResult['gateway_response']['method'],
            'paid_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function downloadInvoice($invoiceId)
    {
        $invoice = DB::table('invoices')
            ->where('invoice_id', $invoiceId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$invoice) {
            abort(404);
        }

        $html = view('billing.invoice', ['invoice' => $invoice])->render();

        return response($html)
            ->header('Content-Type', 'text/html');
    }
}
