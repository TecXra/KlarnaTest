<div class="row">
    @if(!$isCompany)
    <div class="col-bg-6 col-xs-12">
        <a  role="button" class="btn btn-default" id="paymentOptionAnchor" data-action="avarda_payment" href="javascript:void(0)" style="background-color:#fff; color: #444; font-size: 1.4em; font-weight: 500; border:1px solid #aaa; margin-top: 15px; margin-bottom: 15px; width: 100%;">
            <div class="paymentIcons pull-left">
                <img height="40" src="{{ asset('images/site/payment/avarda-logo.png') }}" alt='img'>
            </div>
            <span class="paymentSubText pull-left">Faktura upp till 50 dagar <span class="paymentLineTwo">eller delbetala i egen takt</span></span>
            @if(App::environment('production'))
                <script src="https://online.avarda.org/CheckOut2/Scripts/CheckOutClient.js"></script>
            @else
                <script src="https://stage.avarda.org/CheckOut2/Scripts/CheckOutClient.js"></script>
            @endif

        </a>
    </div>
    @endif
    <div class="col-bg-6 col-xs-12">
        <a role="button" class="btn btn-default" id="paymentOptionAnchor" data-action="careditcard_payment" href="javascript:void(0)" style="background-color:#fff; color: #444; font-size: 1.4em; font-weight: 500;  border:1px solid #aaa; width: 100%;">
            <div class="paymentIcons pull-left">
                <img height="40" style="margin-right: 5px" class="pull-left" src="{{ asset('images/site/payment/master_card.png') }}" alt='img'>
                <img height="40" class="pull-left" src="{{ asset('images/site/payment/visa_card.png') }}" alt='img'>
            </div>
            <span class="paymentSubText pull-left">Säker kortbetalning</span>
        </a>
    </div>

    <div class="col-bg-6 col-xs-12">
        <a role="button" class="btn btn-default" id="paymentOptionAnchor" data-action="klarna_payment" href="javascript:void(0)" style="background-color:#fff; color: #444; font-size: 1.4em; font-weight: 500; border:1px solid #aaa; margin-top: 15px; margin-bottom: 15px; width: 100%;">
            <div class="paymentIcons pull-left">
                <img height="40" class="pull-left" src="https://www.klarna.com/assets/sites/2/2019/07/05131457/Klarna_Smoooth_Payments_OG.png" alt='img'>
            </div>
            <span class="paymentSubText pull-left"> Klarna Payment</span>
        </a>
    </div>
</div>