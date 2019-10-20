<ul class="nav nav-pills nav-stacked tree">
    <li class="{{ Request::is('admin/cms/sidor') ? 'active' : '' }}"><a href="{{ url('admin/cms/sidor') }}"> <span class="badge pull-right">{{ App\Page::where('is_post', 0)->count()}}</span> Sidor</a></li>
    <li class="{{ Request::is('admin/cms/inlagg') ? 'active' : '' }}"><a href="{{ url('admin/cms/inlagg') }}"> <span class="badge pull-right">{{ App\Page::where('is_post', 1)->count()}}</span> Inlägg </a></li>
    <li class="{{ Request::is('admin/cms/slider') ? 'active' : '' }}"><a href="{{ url('admin/cms/slider') }}"> <span class="badge pull-right"></span> Sliders </a></li>
    <li class="{{ Request::is('admin/cms/menyer') ? 'active' : '' }}"><a href="{{ url('admin/cms/menyer') }}"></span> Menyer</a></li>
    {{-- <li class="{{ Request::is('admin/cms/installningar') ? 'active' : '' }}"><a href="{{ url('admin/cms/installningar') }}"> <span class="badge pull-right"></span> Inställningar </a></li> --}}
    <li data-toggle="collapse" data-parent="#p1" href="#pv1">
      	<a class="nav-sub-container">Inställningar<span class="caret arrow"></span></a></li>
      	<ul style="margin-left: 20px" class="nav nav-pills nav-stacked tree collapse in" id="pv1">
       		<li class="{{ Request::is('admin/cms/installningar/allmant/1') ? 'active' : '' }}"><a href="{{ url('admin/cms/installningar/allmant/1') }}"> <span class="badge pull-right"></span> Allmänt </a></li>
        	<li class="{{ Request::is('admin/cms/installningar/sociala_medier/3') ? 'active' : '' }}"><a href="{{ url('admin/cms/installningar/sociala_medier/3') }}"> <span class="badge pull-right"></span> Sociala medier </a></li>
        	<li class="{{ Request::is('admin/cms/installningar/foretags_info/2') ? 'active' : '' }}"><a href="{{ url('admin/cms/installningar/foretags_info/2') }}"> <span class="badge pull-right"></span> Företags info </a></li>
      </ul>
</ul>

{{-- <div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      
          <ul class="nav nav-pills nav-stacked collapse in" id="p1">
            <li data-toggle="collapse" data-parent="#p1" href="#pv1">
              <a class="nav-sub-container">Public Views<div class="caret-container"><span class="caret arrow"></span></div></a></li>
              <ul class="nav nav-pills nav-stacked collapse in" id="pv1">
                <li><a href="#">View One</a></li>
                <li><a href="#">View Two</a></li>
                <li class="nav-divider"></li>
                <li><a href="#">Trash</a></li>
              </ul>
            
            
            <li data-toggle="collapse" data-parent="#p1" href="#pv2">
              <a class="nav-sub-container">Your Views<div class="caret-container"><span class="caret arrow"></span></div></a></li>
              <ul class="nav nav-pills nav-stacked collapse in" id="pv2">
                <li><a href="#">View One</a></li>
                <li><a href="#">View Two</a></li>
              </ul>
            

            <li data-toggle="collapse" data-parent="#p1" href="#pv3">
              <a class="nav-sub-container">Reports<div class="caret-container"><span class="caret arrow"></span></div></a></li>
              <ul class="nav nav-pills nav-stacked collapse in" id="pv3">
                <li><a href="#">Report One</a></li>
                <li><a href="#">Report Two</a></li>
              </ul>  
                      
        <li>
          <a class="nav-container" data-toggle="collapse" data-parent="#stacked-menu" href="#p2">Process Two<div class="caret-container"><span class="caret arrow"></span></div></a>          
          <ul class="nav nav-pills nav-stacked collapse" id="p2">
            <li><a href="#">View One</a></li>
            <li><a href="#">View Two</a></li>
            <li><a href="#">View Three</a></li>
            <li class="nav-divider"></li>
            <li><a href="#">Trash</a></li>
          </ul>
        </li>
        <li><a class="nav-container" href="#">Process Three<div class="caret-container"><span class="caret arrow"></span></div></a></li>
        <li><a class="nav-container" href="#">Process Four<div class="caret-container"><span class="caret arrow"></span></div></a></li>
      </ul>
      
    </div>
    <div class="col-md-10">
      CONTENT
    </div>
  </div>
</div> --}}