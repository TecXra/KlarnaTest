function redirect(t){var e="";e="undefined"==typeof t||null==t?"/sok/storlek/falgar":"/sok/storlek/falgar?page="+t,history.pushState?window.history.pushState("","",e):document.location.href=e}$(document).on("click",".pagination a",function(t){t.preventDefault();var e=$(this).attr("href"),r=e.urlParam("page");redirect(r),$.ajax({type:"GET",url:"../../filterRimsByBrand",data:{page:r,textSearch:$("#textSearch").val(),product_width:$("#width").val(),product_inch:$("#inch").val(),pcd:$("#pcd").val(),et:$("#et").val(),product_brand:$("#brand").val()},dataType:"json",xhrFields:{withCredentials:!0},success:function(t){$("#searchResult").remove(),$(".categoryFooter").remove(),$("#searchContainer div.col-sm-12").append(t.searchResult),$("html, body").animate({scrollTop:0},1e3)}})}),$(document).on("change","#inch",function(){var t="../../filterRimsByInch";redirect();var e=$("#textSearch").val(),r=$("#pcd").val(),a=$(this).val();$.ajax({type:"get",url:t,data:{textSearch:e,pcd:r,product_inch:a},dataType:"json",xhrFields:{withCredentials:!0},success:function(t){$("#width").html(t.html.dropdownWidth),$("#et").html(t.html.dropdownET),$("#brand").html(t.html.dropdownBrand),$("#searchResult").remove(),$(".categoryFooter").remove(),$("#productCount").empty(),$("#productCount").html(t.productCount),$("#searchContainer div.col-sm-12").append(t.searchResult)}})}),$(document).on("change","#width",function(){var t="../../filterRimsByWidth";redirect();var e=$("#textSearch").val(),r=$("#pcd").val(),a=$("#inch").val(),c=$(this).val();$.ajax({type:"get",url:t,data:{textSearch:e,pcd:r,product_inch:a,product_width:c},dataType:"json",xhrFields:{withCredentials:!0},success:function(t){$("#et").html(t.html.dropdownET),$("#brand").html(t.html.dropdownBrand),$("#searchResult").remove(),$(".categoryFooter").remove(),$("#productCount").empty(),$("#productCount").html(t.productCount),$("#searchContainer div.col-sm-12").append(t.searchResult)}})}),$(document).on("change","#et",function(){var t="../../filterRimsByET";redirect();var e=$("#textSearch").val(),r=$("#pcd").val(),a=$("#inch").val(),c=$("#width").val(),o=$(this).val();$.ajax({type:"get",url:t,data:{textSearch:e,pcd:r,product_inch:a,product_width:c,et:o},dataType:"json",xhrFields:{withCredentials:!0},success:function(t){$("#brand").html(t.html.dropdownBrand),$("#searchResult").remove(),$(".categoryFooter").remove(),$("#productCount").empty(),$("#productCount").html(t.productCount),$("#searchContainer div.col-sm-12").append(t.searchResult)}})}),$(document).on("change","#pcd",function(){var t="../../filterRimsByET";redirect();var e=$("#textSearch").val(),r=$(this).val(),a=$("#inch").val(),c=$("#width").val(),o=$("#et").val();$.ajax({type:"get",url:t,data:{textSearch:e,pcd:r,product_inch:a,product_width:c,et:o},dataType:"json",xhrFields:{withCredentials:!0},success:function(t){$("#brand").html(t.html.dropdownBrand),$("#searchResult").remove(),$(".categoryFooter").remove(),$("#productCount").empty(),$("#productCount").html(t.productCount),$("#searchContainer div.col-sm-12").append(t.searchResult)}})}),$(document).on("change","#brand",function(){var t="../../filterRimsByBrand";redirect();var e=$("#textSearch").val(),r=$("#pcd").val(),a=$("#inch").val(),c=$("#width").val(),o=$("#et").val(),d=$(this).val();$.ajax({type:"get",url:t,data:{textSearch:e,pcd:r,product_inch:a,product_width:c,et:o,product_brand:d},dataType:"json",xhrFields:{withCredentials:!0},success:function(t){$("#searchResult").remove(),$(".categoryFooter").remove(),$("#productCount").empty(),$("#productCount").html(t.productCount),$("#searchContainer div.col-sm-12").append(t.searchResult)}})}),$(".search-box").removeClass("hidden"),$(document).on("click",".search-close",function(t){var e="../../filterRimsByBrand";redirect(),$("#textSearch").val("");var r="",a=$("#pcd").val(),c=$("#inch").val(),o=$("#width").val(),d=$("#et").val(),n=$("#brand").val();$.ajax({type:"get",url:e,data:{textSearch:r,pcd:a,product_inch:c,product_width:o,et:d,product_brand:n},dataType:"json",xhrFields:{withCredentials:!0},success:function(t){$("#searchResult").remove(),$(".categoryFooter").remove(),$("#productCount").empty(),$("#productCount").html(t.productCount),$("#searchContainer div.col-sm-12").append(t.searchResult)}})}),$(document).on("keyup","#textSearch",function(t){t.preventDefault();var e="../../filterRimsByBrand";redirect();var r=$("#textSearch").val(),a=$("#pcd").val(),c=$("#inch").val(),o=$("#width").val(),d=$("#et").val(),n=$("#brand").val();$.ajax({type:"get",url:e,data:{textSearch:r,pcd:a,product_inch:c,product_width:o,et:d,product_brand:n},dataType:"json",xhrFields:{withCredentials:!0},success:function(t){$("#searchResult").remove(),$(".categoryFooter").remove(),$("#productCount").empty(),$("#productCount").html(t.productCount),$("#searchContainer div.col-sm-12").append(t.searchResult)}})});