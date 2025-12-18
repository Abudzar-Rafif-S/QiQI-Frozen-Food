<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Snap;

class CheckoutService
{
    public function getSnapToken(Order $order)
    {
        $customer = $order->customer;
        $user = $customer->user;

        // Bangun item_details seperti di checkout awal
        $items = [];
        foreach ($order->items as $item) {
            $items[] = [
                'id'       => (string) $item->product_id,
                'price'    => (int) round($item->price),
                'quantity' => (int) $item->qty,
                'name'     => $item->product->product_name ?? $item->product->name,
            ];
        }

        // Tambahkan ongkir
        $shippingCost = 0;
        if ($order->shipping) {
            $shippingCost = (int) round($order->shipping->shipping_cost);
        } else {
            // Jika tidak ada relasi shipping, hitung ulang dari weight (opsional)
            // Tapi idealnya, order sudah punya shipping saat status 'pending'
        }

        $items[] = [
            'id'       => 'SHIPPING',
            'price'    => $shippingCost,
            'quantity' => 1,
            'name'     => 'Ongkos Kirim',
        ];

        $params = [
            'transaction_details' => [
                'order_id'     => $order->invoice_number, 
                'gross_amount' => (int) round($order->gross_amount),
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => $customer->fullname ?? $user->name,
                'email'      => $user->email,
                'phone'      => $customer->phone,
                'shipping_address' => [
                    'address' => $customer->address,
                ],
            ],
            'expiry' => [
                'unit' => 'minute',
                'duration' => 15, // sesuai default Midtrans
            ]
        ];

        return Snap::getSnapToken($params);
    }
}
