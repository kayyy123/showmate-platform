<?php

namespace App\Http\Controllers;

use App\Models\CatalogVisit;
use App\Models\Checkout;
use App\Models\Product;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function dashboard(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'merchantCount' => User::where('role', 'merchant')->count(),
                'productCount' => Product::count(),
                'visitCount' => CatalogVisit::count(),
                'checkoutCount' => Checkout::count(),
            ],
        ]);
    }
}
