@extends('layout')

@section('content')
<!-- /.Fixed navbar  -->
<div class="container main-container headerOffset">
    <div class="row">
        <div class="breadcrumbDiv col-lg-12">
            <ul class="breadcrumb">
                <li><a href="{{ url('') }}">Hem</a></li>
                <li><a href="{{ url('varukorg') }}">Varukorg</a></li>
                <li class="active"> Kassa</li>
            </ul>
        </div>
    </div>
    <!--/.row-->

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h1 class="section-title-inner"><span>
            <i class="fa fa-th-list" aria-hidden="true"></i> Du har valt att betala med faktura </span></h1>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-5 rightSidebar">
            {{-- <h4 class="caps"><a href="category.html"><i class="fa fa-chevron-left"></i> Tillbaka till produktsida </a></h4> --}}
        </div>
    </div>
    <!--/.row-->
    
    <form action="{{ url('storeInvoicePayment')}}" method="POST">
        {{ csrf_field() }}
    
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="row userInfo">
                    <div class="col-xs-12 col-sm-12">
                        
                        <br>

                        <p style="font-size: 1.2em;">
                             <b>Den totala summan för din beställning är {{  round((str_replace(' ', '', Cart::total()) + $shippingCost) * $currencyMultiplier, 2) }} {{ $currency }}</b>
                        </p>

                    </div>
                   
                </div>
                <!--/row end-->

            </div>
         
        </div>
        <!--/row-->

        <div style="clear:both"></div>
        <br>
        <br>

        <div id="ssnForm" class="row">

            <div class="col-xs-12" style="padding-left: 15px;">
                <h3 style="border-bottom: 1px solid #ccc">Ange betalningsinformation</h3>
                <br>
                <label>Personnr: </label>
                <input required type="text" name="ssn">
                <button class="btn btn-success">Hämta adress</button>
                <span class="help-block" ></span>
            </div>
        </div>

        <br>

        <div class="row">
            <div class="col-xs-12">
                <p>*Alla beställningar levereras till din folkbokföringsadress, kontrollera adressen som visas innan du bekräftar beställningen. vid eventuella fel, kontakta oss.</p>
            </div>
        </div>

    </form>

</div>
<!-- /.main-container-->
<div class="gap"></div>

@endsection

@section('footer')

<script>
    $(document).on('click', '#ssnForm button', function(e) {
        e.preventDefault();


        // console.log($('#saveOrderInfo').serialize());
        // throw new Error('die');

        $.ajax({
            type: 'get',
            url: 'checkSSN', 
            data: {
                ssn: $('#ssnForm input[name="ssn"]').val()
            },
            dataType: 'json',
            xhrFields: {
                withCredentials: true
            },
            success: function(data) {
                $('#ssnForm .help-block').empty();
                $('#confirmOrder').empty();

                if(data.status) {
                    $('#ssnForm').after(data.html);
                } else {
                    $('#ssnForm .help-block').append('<strong style="color: #D14339;">Ingen adress hittades, betalning inte möjlig</strong>');
                }             

            }
        });
    });    
</script>

@endsection
