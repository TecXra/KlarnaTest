function redirect(e){var t="";t="undefined"==typeof e||null==e?"/sok/reg/falgar":"/sok/reg/falgar?page="+e,history.pushState?window.history.pushState("","",t):document.location.href=t}$(document).on("click",".pagination a",function(e){e.preventDefault();var t=$(this).attr("href"),a=$(this).text(),r=$("#textSearch").val(),c=$(".DDBrands").val(),n=$('.check-btn [name="directNav"]').is(":checked")?1:0;redirect(a),$.ajax({type:"GET",url:t,data:{textSearch:r,brand:c,directNav:n},dataType:"json",xhrFields:{withCredentials:!0},success:function(e){$("#searchResult").remove(),$(".categoryFooter").remove(),$("#searchContainer div.col-sm-12").append(e.searchResult),$("html, body").animate({scrollTop:0},1e3)}})}),$(document).on("click",".selectSizes",function(e){e.preventDefault(),$(this).addClass("active").siblings().removeClass("active");var t=$("#textSearch").val(),a=$(this).data("size"),r=$(".DDBrands").val(),c=$('.check-btn [name="directNav"]').is(":checked")?1:0;redirect(),$.ajax({type:"get",url:"seachRimsByReg",data:{textSearch:t,size:a,brand:r,directNav:c,page:1},dataType:"json",xhrFields:{withCredentials:!0},success:function(e){$("#searchResult").remove(),$(".selectDimensions").remove(),$(".categoryFooter").remove(),$("#productCount").empty(),$("#productCount").html(e.productCount),$("#searchContainer div.col-sm-12").append(e.searchResult),$(".dimensions").append(e.dimensions)}})}),$(document).on("change",".DDBrands",function(){var e=$("#textSearch").val(),t=$(this).val(),a=$('.check-btn [name="directNav"]').is(":checked")?1:0;redirect(),$.ajax({type:"get",url:"seachRimsByReg",data:{textSearch:e,brand:t,directNav:a,page:1},dataType:"json",xhrFields:{withCredentials:!0},success:function(e){$("#searchResult").remove(),$(".selectDimensions").remove(),$(".categoryFooter").remove(),$("#productCount").empty(),$("#productCount").html(e.productCount),$("#searchContainer div.col-sm-12").append(e.searchResult),$(".dimensions").append(e.dimensions)}})}),$(document).on("ifChanged",".check-btn input",function(){var e=$("#textSearch").val(),t=$(".DDBrands").val(),a=$('.check-btn [name="directNav"]').is(":checked")?1:0;redirect(),$.ajax({type:"get",url:"seachRimsByReg",data:{textSearch:e,brand:t,directNav:a,page:1},dataType:"json",xhrFields:{withCredentials:!0},success:function(e){$("#searchResult").remove(),$(".selectDimensions").remove(),$(".categoryFooter").remove(),$("#productCount").empty(),$("#productCount").html(e.productCount),$("#searchContainer div.col-sm-12").append(e.searchResult),$(".dimensions").append(e.dimensions)}})}),$(".search-box").removeClass("hidden"),$(document).on("click",".search-close",function(e){$("#textSearch").val("");var t="",a=$(".DDBrands").val(),r=$('.check-btn [name="directNav"]').is(":checked")?1:0;redirect(),$.ajax({type:"get",url:"seachRimsByReg",data:{textSearch:t,brand:a,directNav:r,page:1},dataType:"json",xhrFields:{withCredentials:!0},success:function(e){$("#searchResult").remove(),$(".selectDimensions").remove(),$(".categoryFooter").remove(),$("#productCount").empty(),$("#productCount").html(e.productCount),$("#searchContainer div.col-sm-12").append(e.searchResult),$(".dimensions").append(e.dimensions)}})}),$(document).on("keyup","#textSearch",function(e){e.preventDefault();var t=$("#textSearch").val(),a=$(".DDBrands").val(),r=$('.check-btn [name="directNav"]').is(":checked")?1:0;redirect(),$.ajax({type:"get",url:"seachRimsByReg",data:{textSearch:t,brand:a,directNav:r,page:1},dataType:"json",xhrFields:{withCredentials:!0},success:function(e){$("#searchResult").remove(),$(".selectDimensions").remove(),$(".categoryFooter").remove(),$("#productCount").empty(),$("#productCount").html(e.productCount),$("#searchContainer div.col-sm-12").append(e.searchResult),$(".dimensions").append(e.dimensions)}})});