<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Imdhemy\Purchases\Events\AppStore\DidRenew;

class AutoRenewAppStoreSubscription
{
    /**
    * @param DidRenew $event
    */
    public function handle(DidRenew $event)
    {
       $subscription = $event->getSubscriptionId();
        Log::info($subscription);
    }
}
