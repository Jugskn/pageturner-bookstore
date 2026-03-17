@extends('layouts.app')

@section('title', 'View Orders')

@section('content')

<div class="mb-6">
    <h1 class="text-xl font-bold text-gray-900">All Orders</h1>
    <p class="text-sm text-gray-500">{{ $orders->count() }} order(s) from customers</p>
</div>

@if($orders->isNotEmpty())
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Order</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Customer</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Items</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Total</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Status</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Date</th>
                    <th class="text-right px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900">#{{ $order->id }}</td>
                        <td class="px-4 py-3">
                            <p class="text-gray-900">{{ $order->user->name ?? 'Deleted user' }}</p>
                            <p class="text-xs text-gray-400">{{ $order->user->email ?? '' }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $order->items->count() }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900">₱{{ number_format($order->total_amount, 0) }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-block px-2 py-0.5 text-xs rounded-full
                                @if($order->status === 'completed') bg-green-50 text-green-700 border border-green-200
                                @elseif($order->status === 'pending') bg-yellow-50 text-yellow-700 border border-yellow-200
                                @elseif($order->status === 'shipped') bg-blue-50 text-blue-700 border border-blue-200
                                @elseif($order->status === 'paid') bg-emerald-50 text-emerald-700 border border-emerald-200
                                @elseif($order->status === 'cancelled') bg-red-50 text-red-700 border border-red-200
                                @else bg-gray-50 text-gray-600 border border-gray-200
                                @endif
                            ">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-xs text-gray-500 hover:text-gray-900">View &rarr;</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="border border-dashed border-gray-300 rounded-lg py-10 text-center text-sm text-gray-400">
        No orders yet.
    </div>
@endif

@endsection
