<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <META NAME="ROBOTS" CONTENT="NOINDEX, FOLLOW">
    <title>{{ env('APP_NAME') }}</title>
    <!-- Bootstrap core CSS -->
    <style>


        h2 {
            text-align: center;
            margin: 0;
            margin-bottom: 25px;
            padding: 0;
        }

        h3 {
            margin-top: 0;
            padding-top: 0;
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
        }

        .info {
            padding-right: 5px; 
            width: 33%; 
            float: left;
            margin-bottom: 50px;
            padding-bottom: 50px;
        }

        ul {
            list-style-type: none;
            margin: 0;
            padding:0;
        }

        table {
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
            float:left;
            width: 100%;
            margin-top: 70px;

        }

        thead {
            /*border-top: 2px solid #333;*/
            border-bottom: 2px solid #333;
        }  

        thead th {
            text-align: left;
        }

        .container {
            height: 800px;
        }
        #summery {
            border-left: 2px solid #333;
            padding-left: 10px;
            padding-top: 0px;
            margin-top: 0px;
        }

        .small {
            font-size: 0.9em;
        }
        
        /*.summery {
            width:20%;
            margin-top: 70px;
            float:right;
            border: 1px solid #333;
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
        }*/

        /*.summery span {
            border: 1px solid #333;
        }*/
        

        .footer {
            border-top: 2px solid #333;
        }
        
        .footer div {
            /*border-top: 2px solid #333;*/
            padding-right: 5px; 
            padding-top: 20px; 
            width: 25%; 
            float: left;
        }

        .clearfix:after {
            content:" ";
            display:table;
            clear:both;
        }

    </style>
</head>
<body>

<div class="container">
	<h2>Produktinformation</h2>
    @if (sizeof(Cart::content()) > 0)
        <table>
            <thead>
                <tr>
                    <th style="width:15%"> Produkt</th>
                    <th style="width:45%"> Info</th>
                    <th style="width:7%">Antal</th>
                    <th style="width:7%">Rabatt</th>
                    <th style="width:19%">Totalt</th>
                    <th style="width:21%; padding-left:10px; border-left: 2px solid #333">Summering</th>
                </tr>
            </thead>
            <tbody>
            @foreach (Cart::content() as $key => $item)
                <tr>
                    <td>
                        <div>
                            <img height="80" src="{{ $item->model->productImages->first()->thumbnail_path}}" alt="img">
                        </div>
                    </td>
                    <td>
                        <div>
                            <h4> {{$item->model->product_name}} </h4>
                        </div>
                    </td>

                    <td> x{{ $item->qty }}</td>
                    <td>0</td>
                    <td><span>{{ $item->total }} {{ $cartCalculator->getCurrency() }}</span> <br> <span class="small">varav moms: {{ $item->total * 0.2 }} {{ $cartCalculator->getCurrency() }}</span></td>
                    @if($loop->iteration == 1)
                        <td rowspan="4" id="summery">
                            <b>Totalt produkter: </b><br> {{ $cartCalculator->totalPriceProducts() }} {{ $cartCalculator->getCurrency() }}
                            <br><br>
                            <b>Leverans: </b><br> {{ $cartCalculator->totalPriceShipping() }} {{ $cartCalculator->getCurrency() }}
                            <br><br>
                            <b>Totalt (ex moms): </b><br> {{ $cartCalculator->totalPriceExTax() }} {{ $cartCalculator->getCurrency() }}
                            <br><br>
                            {{-- @if($order->discount > 0 )
                                <b> Rabatt: </b> {{ $order->discount }} {{ $cartCalculator->getCurrency() }}
                                <br><br>
                            @endif --}}
                            <b> Totalt: </b> {{ $cartCalculator->totalPriceIncTax() }} {{ $cartCalculator->getCurrency() }}
                            
                        </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

</div>


{{-- <footer class="footer">
    <div>
        <b>Betalningsmottagare</b>
        {{ env('APP_NAME') }}<br>
        <span style="font-size: 0.95em">{{ App\Setting::getStreetAddress() }}</span> <br>
        {{ App\Setting::getPostalCode() }}, {{ App\Setting::getCity() }}<br>
        {{ env('APP_URL') }}<br>
    </div>
    <div>
        <b>Telefon</b><br>
        {{ App\Setting::getPhone() }}<br><br>
        <b>E-post</b><br>
        {{ App\Setting::getSupportMail() }}
    </div>
    <div>
        <b>Betalningsuppgifter</b><br>
        Bankgiro: {{ App\Setting::getBankGiro() }} <br>
        Säte: {{ App\Setting::getCity() }}
    </div>
    <div>
        <b>Organisations.nr</b><br>
        {{ App\Setting::getOrgNumber() }} <br>
        <b>Momsreg.nr VAT</b><br>
        {{ App\Setting::getVATNumber() }}<br><br>
        <b>Godkänd för F-skatt</b>

    </div>

</footer> --}}