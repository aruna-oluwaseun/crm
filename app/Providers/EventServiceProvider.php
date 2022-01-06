<?php

namespace App\Providers;

use App\Events\InvoiceCreated;
use App\Events\ManualStockAdded;
use App\Events\OrderCreated;
use App\Events\OrderUpdated;
use App\Events\PaymentReceived;
use App\Events\ProductionOrderProcessed;
use App\Events\PurchaseOrderCreated;
use App\Events\PurchaseOrderUpdated;
use App\Events\RefundUpdated;
use App\Events\SalesOrderDispatched;
use App\Listeners\CheckPayment;
use App\Listeners\ProcessRefund;
use App\Listeners\SendCustomerDispatchEmail;
use App\Listeners\UpdateDispatchWeight;
use App\Listeners\UpdateInvoiceTotals;
use App\Listeners\UpdateOrderTotals;
use App\Listeners\UpdateRefundTotals;
use App\Listeners\UpdatePurchaseOrderTotals;
use App\Listeners\UpdateStock;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Listeners\UpdateLastLogin;
use App\Listeners\SetIpLocation;

class
EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [
            UpdateLastLogin::class,
            SetIpLocation::class
        ],
        OrderUpdated::class => [
            UpdateOrderTotals::class
        ],
        OrderCreated::class => [
            UpdateOrderTotals::class
        ],
        SalesOrderDispatched::class => [
            UpdateStock::class,
            SendCustomerDispatchEmail::class,
            UpdateDispatchWeight::class
        ],
        ProductionOrderProcessed::class => [
            UpdateStock::class
        ],
        InvoiceCreated::class => [
            UpdateInvoiceTotals::class
        ],
        PaymentReceived::class => [
            CheckPayment::class
        ],
        PurchaseOrderCreated::class => [
            UpdatePurchaseOrderTotals::class
        ],
        PurchaseOrderUpdated::class => [
            UpdatePurchaseOrderTotals::class
        ],
        RefundUpdated::class => [
            UpdateRefundTotals::class,
            ProcessRefund::class
        ],
        ManualStockAdded::class => [
            UpdateStock::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
