<?php

namespace App\Console\Commands;

use App\Models\UserSubscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Imdhemy\Purchases\Facades\Subscription;

class AppStoreDataSynCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app-store-data:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A simple cron job to syn App store data.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $subscriptions = UserSubscription::all();
        foreach ($subscriptions as $subscription) {
            try {
                $receipt_response = Subscription::appStore()->receiptData($subscription['latest_receipt'])->verifyRenewable();
                $receipt_status = $receipt_response->getStatus();
                if ($receipt_status->isValid()) {
                    $latestReceiptInfo = $receipt_response->getLatestReceiptInfo();
                    $receiptInfo = $latestReceiptInfo[0];
                    $purchase_date = $receiptInfo->getPurchaseDate()->getCarbon();
                    $purchase_date = Carbon::parse($purchase_date)->format('Y-m-d H:i:s');
                    $original_purchase_date = $receiptInfo->getOriginalPurchaseDate()->getCarbon();
                    $original_purchase_date = Carbon::parse($original_purchase_date)->format('Y-m-d H:i:s');
                    $expiry_date =  $receiptInfo->getExpiresDate()->getCarbon();
                    $expiry_date = Carbon::parse($expiry_date)->format('Y-m-d H:i:s');
                    $receipt_data = [
                        'product_id' => $receiptInfo->getProductId(),
                        'subscription_group' => $receiptInfo->getSubscriptionGroupIdentifier(),
                        'environment' => $receipt_response->getEnvironment(),
                        'original_transaction_id' => $receiptInfo->getOriginalTransactionId(),
                        'transaction_id' => $receiptInfo->getTransactionId(),
                        'purchase_date' => $purchase_date,
                        'original_purchase_date' => $original_purchase_date,
                        'expiry_date' => $expiry_date,
                        'is_trial' => $receiptInfo->getIsTrialPeriod() == 0 ? 'false' : 'true',
                        'quantity' => $receiptInfo->getQuantity(),
                    ];
                    $subscription->update($receipt_data);
                    Log::info('Updated. Subscription ID:' . $subscription->id);
                } else {
                    Log::info('No Valid Subscription. Subscription ID:' . $subscription->id);
                }
            } catch (\Exception $e) {
                Log::info('Can\'t read data against Subscription ID:' . $subscription->id);
            }
        }
    }
}
