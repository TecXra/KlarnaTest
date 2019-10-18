<div id="confirmOrder" class="row" style="padding: 20px; ">
	<div class="col-xs-6 col-sm-6">
		<ul >
			<li><u>{{ $customerIdentity->fullName }}</u></li>
			<li>{{ $customerIdentity->street }}</li>
			<li>{{ $customerIdentity->zipCode }} {{ $customerIdentity->locality }}</li>
		</ul>	
	</div>

	<div class="col-xs-6 col-sm-6">
		<button type="submit" class="btn btn-primary btn-lg pull-right" role="button">Bekräfta ordern</button>
	</div>
</div>