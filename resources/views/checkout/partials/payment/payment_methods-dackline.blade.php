<tbody>
<tr>
    <td style="width:40%">Metod</td>
    <td>Information</td>
</tr>
@foreach($paymentMethods as $paymentMethod)
    @if(Session::get('orderInfo.deliveryId') == 2 && $loop->iteration == 2)
        <?php continue ?>
    @endif

    @if($loop->iteration == 1)
        <tr>
            <td><label class="radio">
                <input type="radio" name="paymentMethod" id="paymentMethod1"
                       value="{{$paymentMethod->id}}" checked>
                {{ $paymentMethod->label }}
                </td>
            <td>{{ $paymentMethod->information }}</td>
        </tr>
    @else
        <tr>
            <td><label class="radio">
                <input type="radio" name="paymentMethod"
                       value="{{$paymentMethod->id}}">
                {{ $paymentMethod->label }}
                </td>
            <td>{{ $paymentMethod->information }}</td>
        </tr>
    @endif
@endforeach
</tbody>