<ul class="flex pr-1 {{$class ?? ""}}">
    @for ($i = 1; $i <= $max; $i++)
        <li>
            @if ($i <= $current)
                <i class="fas fa-star fa-sm text-yellow-300"></i>
            @else
                <i class="far fa-star fa-sm text-yellow-300"></i>
            @endif
        </li>
    @endfor
</ul>
