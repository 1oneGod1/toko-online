@props(['rating'])

<div class="star-rating d-inline-block">
    @for ($i = 1; $i <= 5; $i++)
        @if ($i <= floor($rating))
            <i class="bi bi-star-fill text-warning"></i>
        @elseif ($i - 0.5 <= $rating)
            <i class="bi bi-star-half text-warning"></i>
        @else
            <i class="bi bi-star text-warning"></i>
        @endif
    @endfor
</div>