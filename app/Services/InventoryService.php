<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Adjust stock for a product (Input or Output).
     *
     * @param  int  $quantity  (Always positive, type determines direction)
     * @param  string  $type  ('input' or 'output')
     *
     * @throws Exception
     */
    public function adjustStock(Product $product, int $quantity, string $type, string $reason, ?User $user = null): Inventory
    {
        return DB::transaction(function () use ($product, $quantity, $type, $reason, $user) {
            $inventory = $product->inventory()->firstOrCreate([
                'product_id' => $product->id,
            ], [
                'current_stock' => 0,
                'min_stock' => 0,
            ]);

            if ($type === 'output') {
                if ($inventory->current_stock < $quantity) {
                    throw new \App\Exceptions\InsufficientStockException("Stock insuficiente para el producto: {$product->name}. Stock actual: {$inventory->current_stock}, Solicitado: {$quantity}");
                }
                $inventory->current_stock -= $quantity;
            } elseif ($type === 'input') {
                $inventory->current_stock += $quantity;
            } else {
                throw new Exception("Tipo de movimiento inválido: {$type}");
            }

            $inventory->save();

            $inventory->movements()->create([
                'user_id' => $user?->id,
                'type' => $type,
                'quantity' => $quantity,
                'reason' => $reason,
            ]);

            return $inventory;
        });
    }
}
