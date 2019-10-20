@foreach ($product['dimensions'] as $dimension) 
    @if ($dimension == reset($product['dimensions']))
    {{-- @if ($dimension == $product['selectedDimension'])) --}}
        <a href="{{ url('sok/reg/kompletta-hjul/dack') }}" class="btn btn-primary selectDimensions active" role="button" data-dimension="{{ $dimension }}">{{ $dimension }}</a>
    @else 
        <a href="{{ url('sok/reg/kompletta-hjul/dack') }}" class="btn btn-primary selectDimensions" role="button" data-dimension="{{ $dimension }}">{{ $dimension }}</a>
    @endif
@endforeach