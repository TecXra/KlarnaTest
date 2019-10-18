{{ csrf_field() }}
<div class="col-sm-12 search-hit" style="border: 1px solid #eee; background: #ddd; padding: 10px;"> 
    <div class="row"> 
        <div class="col-sm-4"> 
            <img height="100" src="{{ $searchData['ImageLink'] }}">
        </div>
        <div class="col-sm-8"> 
            <a class="pull-right" href="deleteSearchCookie">X</a> 
            <h3>{{ @$searchData['CarSearchTitle'] }}</h3> 
            
            <p> 
                @if(!empty($searchData['FoundDackBack']))
                    <b>Däck:</b> {{ $searchData['FoundDackBack'] }}<br> 
                @endif
                <b>Fälg PCD:</b> {{ $searchData['PCD'] }} <b>ET:</b> {{$searchData['Offset']}}<br> 
                <b>Nav:</b> {{ $searchData['ShowCenterBore'] }} {{ $searchData['OE_Type'] }}<br> 
                <b>Max fälgbredd F/B:</b> {{ $searchData['MaxRimWidthFront'] }}/{{ $searchData['MaxRimWidthRear'] }}<br>
            </p> 
        </div> 
    </div> 
</div>

<div class="productFilter productFilterLook2">
    <div id="productType" data-toggle="buttons">
        <div class="row"> 
            <div class="col-sm-12"> 
                <button id="selectComplete" class="btn btn-default" type="button"  data-type="kompletta-hjul/falgar"> Kompletta Hjul <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                </button>
            </div>  
        </div> 
        <div class="row"> 
            <div class="col-xs-6 padding-right-ajust"> 
                <button id="selectRims" class="btn btn-default" type="button" data-type="falgar"> Fälgar <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                </button>
            </div>  
            <div class="col-xs-6 padding-left-ajust"> 
                <button id="selectTires" class="btn btn-default" type="button"  data-type="sommardack"> Däck <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                </button>
            </div>  
        </div> 
    </div>
    <div class="row"> 
        <div class="col-xs-6 padding-right-ajust"> 
            <div class="select-style"> 
                <select name="size" id="size" class="form-control">
                    @foreach($wheelSizes as $size) {
                        <option value="{{$size->RimSize}}"> {{$size->RimSize}} </option>;
                    @endforeach
                </select> 
            </div> 
        </div>  
        <div id="ddTireType" class="col-xs-6 padding-left-ajust"> 
            <div class="select-style"> 
                <select class="form-control" id="tireType" name="tireType"> 
                    <option value="sommardack">Sommardäck</option> 
                    <option value="friktionsdack">Vinterdäck Friktion</option> 
                    <option value="dubbdack">Vinterdäck Dubbad</option> 
                </select> 
            </div> 
        </div>  
    </div>
</div>

<div class="cart-actions">
     <button class="button btn-block btn-cart cart first" type="submit">Sök
    <i class="fa fa-long-arrow-right" > </i>
    </button>
</div>
