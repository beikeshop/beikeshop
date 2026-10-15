<?php

namespace Beike\Admin\Http\Controllers;

use Beike\Models\CartAddEvent;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CartObservationController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'keyword' => ['nullable', 'string', 'max:100'],
            'visitor' => ['nullable', Rule::in(['all', 'customer', 'guest'])],
            'from'    => ['nullable', 'date_format:Y-m-d'],
            'to'      => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
        ]);

        $filters = array_merge([
            'visitor' => 'all',
            'from'    => now()->subDays(29)->toDateString(),
            'to'      => now()->toDateString(),
        ], $filters);
        $filters['visitor'] = $filters['visitor'] ?: 'all';
        $filters['from']    = $filters['from'] ?: now()->subDays(29)->toDateString();
        $filters['to']      = $filters['to'] ?: now()->toDateString();

        $events = CartAddEvent::query()
            ->with('customer')
            ->where('created_at', '>=', $filters['from'] . ' 00:00:00')
            ->where('created_at', '<=', $filters['to'] . ' 23:59:59');

        if ($filters['visitor'] === 'customer') {
            $events->whereNotNull('customer_id');
        } elseif ($filters['visitor'] === 'guest') {
            $events->whereNull('customer_id');
        }

        if (! empty($filters['keyword'])) {
            $keyword = trim($filters['keyword']);
            $events->where(function ($query) use ($keyword): void {
                $query->where('product_name', 'like', '%' . $keyword . '%')
                    ->orWhere('product_sku', 'like', '%' . $keyword . '%')
                    ->orWhere('sku_model', 'like', '%' . $keyword . '%')
                    ->orWhere('visitor_key', 'like', '%' . $keyword . '%')
                    ->orWhereHas('customer', function ($customerQuery) use ($keyword): void {
                        $customerQuery->withTrashed()
                            ->where('name', 'like', '%' . $keyword . '%')
                            ->orWhere('email', 'like', '%' . $keyword . '%');
                    });
            });
        }

        $events = $events->orderByDesc('id')->paginate(30)->withQueryString();

        return view('admin::pages.customer_carts.index', compact('events', 'filters'));
    }
}
