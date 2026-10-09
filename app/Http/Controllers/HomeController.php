<?php

namespace App\Http\Controllers;

use App\Models\Booth;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::roots()->where('is_active', true)->orderBy('sort_order')->withCount('booths')->with('children')->get();

        $featuredBooths = Booth::approved()
            ->with('categories')
            ->withCount('products')
            ->inRandomOrder()
            ->limit(6)
            ->get();

        $buildingMaterials = Category::where('slug', 'building-materials')->first();
        $buildingBooths = collect();
        if ($buildingMaterials) {
            $buildingMaterials->load('children');
            $buildingChildIds = $buildingMaterials->children->pluck('id');
            $buildingBooths = Booth::approved()
                ->whereHas('categories', fn($q) => $q->whereIn('categories.id', $buildingChildIds))
                ->with('categories')
                ->withCount('products')
                ->limit(4)
                ->get();
        }

        $discountedProducts = \App\Models\Product::with(['booth','images','category'])->where('status','active')->where('discount_percent','>',0)->inRandomOrder()->limit(8)->get();
        $randomProducts = \App\Models\Product::with(['booth','images','category'])->where('status','active')->inRandomOrder()->limit(8)->get();

        $stats = [
            'booths' => Booth::approved()->count(),
            'categories' => Category::where('is_active', true)->count(),
            'products' => \App\Models\Product::where('status', 'active')->count(),
        ];

        return view('home.index', compact('categories', 'featuredBooths', 'buildingBooths', 'discountedProducts', 'randomProducts', 'stats'));
    }
}
