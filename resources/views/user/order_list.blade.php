@extends('layout')

@section('header')
    <!-- styles needed by footable  -->
    <link href="{{ asset('assets/css/footable-0.1.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/footable.sortable-0.1.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')

<div class="container main-container containerOffset">
    <div class="row">
        <div class="breadcrumbDiv col-lg-12">
            <ul class="breadcrumb">
                <li><a href="{{ url('') }}">Hem</a></li>
                <li><a href="{{ url('konto') }}"> Mitt Konto</a></li>
                <li class="active"> Order Lista</li>
            </ul>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-7">
            <h1 class="section-title-inner"><span><i class="fa fa-list-alt"></i> Order List </span></h1>

            <div class="row userInfo">
                <div class="col-lg-12">
                    <h2 class="block-title-2"> Din Order Lista </h2>
                </div>

                <div style="clear:both"></div>

                <div class="col-xs-12 col-sm-12">
                    <table class="footable">
                        <thead>
                        <tr>
                            <th data-class="expand"><span
                                    title="table sorted by this column on load">Order nummer</span></th>
                            <th data-hide="phone,tablet" data-sort-ignore="true">Antal</th>
                            {{-- <th data-hide="phone,tablet" data-sort-ignore="true">Faktura</th>
                            <th data-hide="phone,tablet"><strong>Betalningsmetod</strong></th> --}}
                            <th data-hide="phone,tablet"><strong></strong></th>
                            <th data-hide="default"> Pris</th>
                            <th data-hide="default" data-type="numeric"> Beställningsdatum</th>
                            <th data-hide="default"> Leveranstid</th>
                            <th data-hide="default"> Status</th>
                            <th data-hide="default"> Kommentar</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>
                                    {{count($order->orderDetails)}} <small>artiklar</small>
                                </td>
                                {{-- <td><a target="_blank">-</a></td>
                                <td>VISA</td> --}}
                                <td><a href="{{ url('order_status/'. $order->token )}}" class="btn btn-primary btn-sm">Se info</a></td>
                                <td>{{ $order->total_price_including_tax }} {{ $order->currency_notation }}</td>
                                <td data-value="78025368997">{{ $order->created_at }}</td>

                                <td>{{ $order->delivery_time}}</td>

                                @if ( $order->order_status_id == 1)
                                    <td data-value="3"><span class="label label-primary ">{{$order->orderStatus->label}}</span>
                                @endif

                                @if ($order->order_status_id == 2)
                                    <td data-value="3"><span class="label label-warning  ">{{$order->orderStatus->label}}</span>
                                @endif

                                @if ($order->order_status_id == 3)
                                    <td data-value="3"><span class="label label-danger  ">{{$order->orderStatus->label}}</span>
                                @endif

                                @if ($order->order_status_id == 4)
                                    <td data-value="3"><span class="label label-success ">{{$order->orderStatus->label}}</span>
                                @endif
                                </td>
                                <td>{{ $order->comment }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="clear:both"></div>

                <div class="col-lg-12 clearfix">
                    <ul class="pager">
                        {{-- <li class="previous pull-right"><a href="index.html"> <i class="fa fa-home"></i> Gå till Shop </a>
                        </li> --}}
                        <li class="next pull-left"><a href="{{ url('konto') }}"> &larr; Tillbaka till Mitt Konto</a></li>
                    </ul>
                </div>
            </div>
            <!--/row end-->

        </div>
        <div class="col-lg-3 col-md-3 col-sm-5"></div>
    </div>
    <!--/row-->

    <div style="clear:both"></div>
</div>
<!-- /main-container -->
@endsection

@section('footer')

<!-- include footable plugin -->
<script src="{{ asset('assets/js/footable.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/footable.sortable.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        $('.footable').footable();
    });
</script>

@endsection
