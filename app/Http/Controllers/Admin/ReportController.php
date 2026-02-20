<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function stockReport(Request $request)
    {
        $query = Product::with('category');
        
        if ($request->category) {
            $query->where('category_id', $request->category);
        }
        
        if ($request->status == 'menipis') {
            $query->whereColumn('stock', '<=', 'min_stock');
        } elseif ($request->status == 'kritis') {
            $query->where('stock', '<=', 0);
        }

        $products = $query->get();
        
        if ($request->ajax()) {
            return response()->json(['data' => $products]);
        }

        return view('reports.stock', compact('products'));
    }

    public function transactionsReport(Request $request)
    {
        $query = Transaction::with('product', 'user')
                    ->when($request->start_date, function($q, $date) {
                        $q->whereDate('created_at', '>=', $date);
                    })
                    ->when($request->end_date, function($q, $date) {
                        $q->whereDate('created_at', '<=', $date);
                    })
                    ->when($request->type, function($q, $type) {
                        $q->where('type', $type);
                    })
                    ->when($request->status, function($q, $status) {
                        $q->where('status', $status);
                    });

        $transactions = $query->latest()->get();

        if ($request->ajax()) {
            return response()->json(['data' => $transactions]);
        }

        return view('reports.transactions', compact('transactions'));
    }

    public function exportPdf(Request $request)
    {
        // TODO: Implement PDF export
        return redirect()->back()->with('info', 'Fitur PDF akan segera tersedia');
    }

    public function exportExcel(Request $request)
    {
        // TODO: Implement Excel export
        return redirect()->back()->with('info', 'Fitur Excel akan segera tersedia');
    }
}