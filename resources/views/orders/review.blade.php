@extends('layouts.app')

@section('title', 'Review Your Order')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('orders.show', $order) }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Back to Order #{{ $order->id }}</a>
        @php $isEditing = $existingReviews->isNotEmpty(); @endphp
        <h1 class="text-xl font-bold text-gray-900 mt-2">{{ $isEditing ? 'Edit Your Reviews' : 'Review Your Books' }}</h1>
        <p class="text-sm text-gray-500 mt-1">Leave a rating and comment for each book in your order.</p>
    </div>

    <form method="POST" action="{{ route('orders.review.store', $order) }}" class="space-y-4">
        @csrf

        @foreach ($order->items as $index => $item)
            @php
                $existing = $existingReviews->get($item->book_id);
                $currentRating = old("reviews.{$index}.rating", $existing->rating ?? 0);
                $currentComment = old("reviews.{$index}.comment", $existing->comment ?? '');
            @endphp
            <div class="bg-white border border-gray-200 rounded-lg p-5">
                <input type="hidden" name="reviews[{{ $index }}][book_id]" value="{{ $item->book_id }}">

                <div class="flex items-start gap-4 mb-4">
                    <div class="w-12 h-16 flex-shrink-0 bg-gray-100 rounded overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400&q=80" alt="{{ $item->book->title ?? 'Book' }}" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $item->book->title ?? 'Deleted Book' }}</p>
                        <p class="text-xs text-gray-500">by {{ $item->book->author ?? 'Unknown' }}</p>
                        @if ($existing)
                            <p class="text-xs text-blue-600 mt-0.5">Editing your review</p>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-600 mb-2">Rating</label>
                    <div class="star-rating flex gap-0.5" data-index="{{ $index }}">
                        @for ($star = 1; $star <= 5; $star++)
                            <input type="radio"
                                   name="reviews[{{ $index }}][rating]"
                                   value="{{ $star }}"
                                   id="star-{{ $index }}-{{ $star }}"
                                   class="sr-only"
                                   {{ (int)$currentRating === $star ? 'checked' : '' }}>
                            <label for="star-{{ $index }}-{{ $star }}"
                                   class="star-label cursor-pointer text-3xl leading-none select-none transition-colors duration-100 {{ $star <= (int)$currentRating ? 'text-yellow-400' : 'text-gray-300' }}"
                                   data-star="{{ $star }}">&#9733;</label>
                        @endfor
                    </div>
                    @error("reviews.{$index}.rating")
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Comment (optional)</label>
                    <textarea name="reviews[{{ $index }}][comment]" rows="2" class="w-full rounded border-gray-300 text-sm" placeholder="What did you think of this book?">{{ $currentComment }}</textarea>
                </div>
            </div>
        @endforeach

        <button type="submit" class="w-full bg-gray-900 text-white rounded-md py-2.5 text-sm font-medium hover:bg-gray-800 transition">
            {{ $isEditing ? 'Update Reviews' : 'Submit Reviews' }}
        </button>
    </form>
</div>

<script>
document.querySelectorAll('.star-rating').forEach(function(group) {
    var labels = group.querySelectorAll('.star-label');
    var inputs = group.querySelectorAll('input[type="radio"]');

    function highlight(upTo) {
        labels.forEach(function(label) {
            var s = parseInt(label.getAttribute('data-star'));
            if (s <= upTo) {
                label.classList.remove('text-gray-300');
                label.classList.add('text-yellow-400');
            } else {
                label.classList.remove('text-yellow-400');
                label.classList.add('text-gray-300');
            }
        });
    }

    function getCheckedValue() {
        var checked = group.querySelector('input[type="radio"]:checked');
        return checked ? parseInt(checked.value) : 0;
    }

    labels.forEach(function(label) {
        label.addEventListener('mouseenter', function() {
            highlight(parseInt(label.getAttribute('data-star')));
        });

        label.addEventListener('click', function() {
            var starVal = parseInt(label.getAttribute('data-star'));
            inputs.forEach(function(input) {
                if (parseInt(input.value) === starVal) {
                    input.checked = true;
                }
            });
            highlight(starVal);
        });
    });

    group.addEventListener('mouseleave', function() {
        highlight(getCheckedValue());
    });
});
</script>
@endsection
