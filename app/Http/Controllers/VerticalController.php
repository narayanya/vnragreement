<?php

namespace App\Http\Controllers;

use App\Models\CropType;
use Illuminate\Http\Request;

class VerticalController extends Controller
{
    /**
     * Display a listing of crop types (Vertical data).
     */
    public function index(Request $request)
    {
        $query = CropType::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $verticals = $query->orderBy('name')->paginate(20)->withQueryString();
        $total = CropType::count();

        return view('master.vertical.index', compact('verticals', 'total'));
    }
}
