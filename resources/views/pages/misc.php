{{--             @if ( !empty($_COOKIE['regSearchData']))
                <?php $searchData = json_decode($_COOKIE['regSearchData']) ?>
                <div class="col-sm-12 search-hit" style="border: 1px solid #eee; background: #ddd; padding: 10px;">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-4">
                        <a class="pull-right" href="/deleteCookie">X</a>
                        <h3>{{ $searchData->Manufacturer . ' ' .  $searchData->ModelName . ' ' . $searchData->FoundYear}}</h3>
                        <p >
                            Däck: {{ $searchData->FoundDackBack }} <br>
                            Fälg PCD: {{ $searchData->PCD }} <br>
                            Nav PCD: {{ $searchData->ShowCenterBore . ' ' . $searchData->OE_Type }}

                        </p>
                    </div>
                </div> 
                </div>   
            @endif --}}


success: function(data) {
                        console.log(data);
                        var options = "";
                        $.each(data.size, function(i, obj){
                            options += '<option value="Action">' + obj.WheelSize_2 + '</option>';
                        })
                        $('.car-search-box .productFilter .row').remove();
                        $('.car-search-box .productFilter').append(
                            '<div class="row">' +
                                '<div class="col-sm-12">' +
                                    '<div class="radio product-label">' +
                                        '<label><input type="radio" name="optradio">Kompletta Hjul</label>' +
                                    '</div>' +
                                '</div>' + 
                            '</div>' +
                            '<div class="row">' +
                                '<div class="col-xs-6 padding-right-ajust">' +
                                    '<div class="radio product-label">' +
                                        '<label><input type="radio" name="optradio">Fälgar</label>' +
                                    '</div>' + 
                                '</div>' + 
                                '<div class="col-xs-6 padding-left-ajust">' +
                                    '<div class="radio product-label">' +
                                        '<label><input type="radio" name="optradio">Däck</label>' +
                                    '</div>' + 
                                '</div>' + 
                            '</div>' +
                            '<div class="row">' +
                                '<div class="col-xs-6 padding-right-ajust">' +
                                    '<div class="select-style">' +
                                        '<select class="form-control">' +
                                            options +
                                        '</select>' +
                                    '</div>' +
                                '</div>' + 
                                '<div class="col-xs-6 padding-left-ajust">' +
                                    '<div class="select-style">' +
                                        '<select class="form-control">' +
                                            '<option>Sommardäck</option>' +
                                            '<option>Vinterdäck Friktion</option>' +
                                            '<option>Vinterdäck Dubbad</option>' +
                                        '</ul>' +
                                    '</div>' +
                                '</div>' + 
                            '</div>'
                        );

                        $data = JSON.parse(data.cookie);
                
                        $('.car-search-box .reg-search').remove();
                        $('.car-search-box .search-hit').remove();
                        $('.car-search-box form').prepend(
                        '<div class="col-sm-12 search-hit" style="border: 1px solid #eee; background: #ddd; padding: 10px;">' +
                            '<div class="row">' +
                                '<div class="col-sm-8 col-sm-offset-4">' +
                                    '<a class="pull-right" href="/deleteCookie">X</a>' +
                                    '<h3>' + $data.Manufacturer + ' ' + $data.ModelName + ' ' + $data.FoundYear + '</h3>' +
                                    '<p>' +
                                        'Däck: ' + $data.FoundDackBack +  '<br>' +
                                        'Fälg PCD: ' + $data.PCD +  '<br>' +
                                        'Nav: ' + $data.ShowCenterBore + ' ' + $data.OE_Type + '<br>' +
                                    '</p>' +
                                '</div>' +
                            '</div>' +
                        '</div>'
                        );  

                        


                        $('input').iCheck({
                            // checkboxClass: 'icheckbox_minimal-green',
                            // radioClass: 'iradio_minimal-green'

                            checkboxClass: 'icheckbox_square-green iCheck-margin',
                            radioClass: 'iradio_square-grey iChk iCheck-margin'
                        });
                    }
<?php


// (($classId=='3' or $classId=='4') and $quantityInStock>4 and $tyreVehicleType!='1')
// 
(($classId=='3' or $classId=='4') and $supplierId=='3' and !in_array((int)$rimDiameter,$sizesArray) and (int)$rimDiameter<23 and (int)$rimDiameter>15 and in_array((int)$width,$this->dackWideFixedArrayList) and strtolower($brandName)!="hankook" and strtolower($brandName)!="nokian" and strtolower($brandName)!="sailun" and $quantityInStock>4 and $typeId=='1' and $tyreVehicleType!='1')

if($classId!='3' and $classId!='4')
    continue;
if($quantityInStock>4 or $tyreVehicleType=='1')
    continue;



// tyreVehicleType 1 = motorcykletyre/ 0=normal tyrels / 4=CDACK -> put in isDack
// ClassID 3=summerDack / 4=vinterDAck (friktionsdack or dubbdack, checkit in isSpike [1==dubbdack, 1!=fricktionsdack]) -> Put it in product_tyretype
// SupplierId -> Put it in subSupplier, NOT a FK anymore 

?>