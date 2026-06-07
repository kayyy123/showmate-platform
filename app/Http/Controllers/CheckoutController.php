<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function store(Request $request, User $user)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'buyer_name' => 'required|string|max:255',
            'buyer_phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::where('id', $validated['product_id'])
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->firstOrFail();

        $totalPrice = $product->price * $validated['quantity'];

        $checkout = Checkout::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'buyer_name' => $validated['buyer_name'],
            'buyer_phone' => $validated['buyer_phone'],
            'notes' => $validated['notes'] ?? null,
            'quantity' => $validated['quantity'],
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        // Nomor WhatsApp dummy — akan diganti setelah field whatsapp ditambahkan ke users
        $whatsappNumber = '6281291743817';

        if ($product->pricing_type === 'negotiable') {
            $message = "Halo, saya {$checkout->buyer_name} tertarik dengan produk Anda:\n\n";
            $message .= "*{$product->name}*\n";
            $message .= "Jumlah: {$checkout->quantity} pcs\n";
            $message .= "Harga tercantum: Rp " . number_format($product->price, 0, ',', '.') . "/pcs\n";
            if ($checkout->notes) {
                $message .= "Catatan: {$checkout->notes}\n";
            }
            $message .= "\nMohon info harga terbaru. Terima kasih.";
        } else {
            $message = "Halo, saya {$checkout->buyer_name} ingin memesan:\n\n";
            $message .= "*{$product->name}*\n";
            $message .= "Jumlah: {$checkout->quantity} pcs\n";
            $message .= "Total: Rp " . number_format($totalPrice, 0, ',', '.') . "\n";
            if ($checkout->notes) {
                $message .= "Catatan: {$checkout->notes}\n";
            }
            $message .= "\nNomor WA saya: {$checkout->buyer_phone}";
        }

        $waUrl = "https://wa.me/{$whatsappNumber}?text=" . urlencode($message);

        return redirect()->back()->with('wa_url', $waUrl);
    }
}
