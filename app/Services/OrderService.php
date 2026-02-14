<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    /**
     * Create an order and deduct stock atomically.
     *
     * @param  array  $itemsData  [['product_id' => 1, 'quantity' => 2], ...]
     *
     * @throws Exception
     */
    public function createOrder(array $orderData, array $itemsData, User $user): Order
    {
        return DB::transaction(function () use ($orderData, $itemsData, $user) {
            // Verify stock for all items first (optional optimization, but adjustStock throws anyway)
            // We just process them and let rollback handle failures.

            $order = Order::create([
                'client_id' => $orderData['client_id'],
                'user_id' => $user->id,
                'status' => 'pending',
                'total_amount' => 0, // Will calculate from items
            ]);

            $totalAmount = 0;

            foreach ($itemsData as $item) {
                $product = Product::findOrFail($item['product_id']);
                $quantity = $item['quantity'];

                // Deduct stock
                $this->inventoryService->adjustStock(
                    $product,
                    $quantity,
                    'output',
                    "Order #{$order->id}",
                    $user
                );

                // Create Order Item
                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price_at_time' => $product->sale_price,
                ]);

                $totalAmount += $product->sale_price * $quantity;
            }

            // Update order total
            $order->update(['total_amount' => $totalAmount]);

            return $order->load('items');
        });
    }
}
