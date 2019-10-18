@extends('layout')

{{-- @section('header')
    <META NAME="ROBOTS" CONTENT="NOINDEX, FOLLOW">
@endsection
 --}}
@section('content')


<div class="wrapper whitebg" style=" margin-bottom: 100px";>
    <div class="container main-container">
        <div class="row innerPage">
            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-offset-1">
        		<h1 class="title-big text-left section-title-style2">
                    <span>Vanliga frågor och svar</span>
                </h1>


                <div style="padding-left:15px">
					<p class="lead">
						<b>Kan jag använda vilka däck som helst?</b><br>
						Nej, du ska bara använda de däck som anges i ditt registreringsbevis eller i ditt fordonsbevis. Där står det även om tillåtna däckdimensioner, bärkraft och däckets högsta tillåtna hastighet.
					</p>
					
					<p class="lead">
						<b>Vad är skillnaden mellan däck och hjul?</b><br>
						Däck är det svarta gummit medan ett hjul är komplett med däck och fälg.
 					</p>

 					<p class="lead">
						<b>Hur vet jag hur gammalt ett däck är?</b><br>
						Alla däck är märkta med en så kallad DOT kod. Denna visar vilket år och vilken vecka som däcket tillverkats. Koden består av fyra siffror. Tillverkningsveckan är de första två siffrorna och de två sista är året.
					</p>

					<p class="lead">
						<b>Hur förvarar jag mina däck?</b><br>
						Både vinterdäck och sommardäck ska förvaras mörkt, svalt och luftigt. De får inte utsättas för direkt solljus. Tvätta dina däck med vatten och ett milt bilschampo innan du förvarar dem.
					</p>

					<p class="lead">
						<b>Vad gör jag om vissa däck är mer slitna än andra?</b><br>
						Det bästa är att inte ha skillnader i däcket alls när det gäller slitage. Men om du har däck som är mer slitna än de andra, så ska de bästa däcken sitta bak på bilen för att undvika sladd.
					</p>

					<p class="lead">
						<b>Vad gäller med lufttryck?</b><br>
						Rätt lufttryck i däcken är viktigt. Vad din bil ska ha för lufttryck kan du läsa i din bils instruktionsbok. Ett felaktigt lufttryck kan vara trafikfarligt och påverka däckets livslängd.
					</p>

					<p class="lead">
						<b>Varför har ett däck en bestämd rullriktning?</b><br>
						Ett däck med en bestämd rullriktning måste monteras åt rätt håll, eftersom det annars ger sämre egenskaper vid vattenplaning. Andra egenskaper försämras dock inte, men det kan bli ett ojämnt slitage på däcken.
					</p>

					<p class="lead">
						<b>Det vibrerar i ratten när jag kör, varför?</b><br>
						Om det vibrerar i ratten när du kör, främst vid högre hastigheter, så kan det bero på att det är obalans i framhjulen. Se då till att du får dina hjul balanserade och undersök hjulinställningen.
					</p>

					<p class="lead">
						<b>Om jag har 16 tums däck kan jag ha 17 tums fälgar då?</b><br>
						Nej, du måste alltid ha samma fälgstorlek som slutsiffran på ditt däck. Står det exempelvis 205 / 55 / 16, så är det däck med 16 tum som gäller.
					</p>

					<p class="lead">
						<b>Finns det däck jag kan använda året runt?</b><br>
						Friktionsdäck kallas ibland för året runt-däck eftersom lagen inte begränsar användandet. Men vi rekommenderar inte att de används året runt, eftersom de inte är anpassade för varma dagar och har ett sämre grepp när väglaget är blött. Dessutom slits de ner snabbare under sommartid. Det är bättre att investera i ett par sommardäck för sommaren och ett par vinterdäck för vintern.
					</p>

					<p class="lead">
						<b>När får jag använda mina dubbdäck?</b><br>
						Det är tillåtet att använda dubbdäck under perioden 1 oktober - 15 april. Om det råder vinterväglag så kan du få använda dem utöver denna period. Det är polisen som avgör vad som är vinterväglag.
					</p>

					<p class="lead">
						<b>Vilka regler är det för dubbdäck vid körning med släp?</b><br>
						Om du har dubbdäck på bilen, så måste du ha dubbdäck även på släpet. Om du har dubbfria vinterdäck på bilen, så kan du använda både dubbfria och dubbade däck på ditt släp.
					</p>

					<p class="lead">
						<b>Får jag köra med dubbdäck överallt?</b><br>
						Nej, dubbdäck är förbjudet på vissa ställen. Det är då endast tillåtet att köra med friktionsdäck. Mer information finns i de lokala trafikföreskrifterna.
					</p>

					<p class="lead">
						<b>När ska jag ha på mina vinterdäck?</b><br>
						Lagen säger att du ska ha på vinterdäck på bilen under perioden 1 december - 31 mars. Detta gäller personbil, lätt lastbil och buss upp till 3.5 ton. Det gäller även släpvagnar som dras av dessa fordon.
					</p>

					<p class="lead">
						<b>Vad betyder M+S?</b><br>
						I Europa betyder M+S Mud + Snow och däck märkta med den beteckningen är vinterdäck. M+S-däck har väldigt bra grepp på vått eller halt väglag.
					</p>

					<p class="lead">
						<b>Kan jag montera nya dubbar i mina dubbdäck när dubbarna har lossnat?</b><br>
						Nej, de dubbar som har lossnat kan inte ersättas med nya dubbar.
					</p>

					<p class="lead">
						<b>Vad är egentligen vinterväglag?</b><br>
						Det är polisen som avgör det, men oftast gäller det när hela eller delar av vägen är täckta med is, snö eller modd och när det är risk för halka.
					</p>

					<p class="lead">
						<b>Vilket mönsterdjup bör jag ha?</b><br>
						Vintertid och vinterväglag kräver att du har minst 3 mm i mönsterdjup. När det gäller sommardäck så är det 1,6 mm som är minsta mönsterdjup du får ha. Ju större mönsterdjup, desto bättre grepp.
					</p>

					<p class="lead">
						<b>Vilken är den viktigaste egenskapen för sommardäck?</b><br>
						Ett sommardäck bör ha ett bra grepp även när vägen är våt. På sommaren och hösten kan det regna rejält och då är det bra att ha däck med förmåga att förhindra vattenplaning. Däck med lågt ljud är också att föredra, eftersom det gör körningen mer behaglig. Ett lågt rullmotstånd är också bra, eftersom det minskar bränsleförbrukningen och är vänligare mot miljön.
					</p>
					
                    {{-- <h4><b>1. När kommer min beställning? </b></h4>
		            <p><b>Svar:</b> Du får ett mail med en preliminär leveranstid så snart vi har beställt
					produkten från vår leverantör normalt 5-10 arbetsdagar, om varan är
					restnoterad är leveranstiden ca 3-6 veckor. Lagervaror skickas inom 2-3
					dagar.</p>

					<br>

					<h4><b> 2. Vad kostar frakten? </b></h4>
		            <p><b>Svar:</b>  Lägg produkten i varukorgen och klicka dig vidare till kassan, där får du upp alla övriga kostnader som tillkommer.</p>

					<br>

					<h4><b>3. Kan vi vara sponsorer för ditt projekt? </b></h4>
		            <p><b>Svar:</b> Tyvärr, vi samarbetar redan med ett flertal personer.</p>

					<br>

					<h4><b>4. När betalar ni tillbaka pengar för en retur?</b></h4>
		            <p><b>Svar:</b>Vi betalar inom 30 dagar.</p>

					<br>

					<h4><b>5. Är min order skickad? </b></h4>
		            <p><b>Svar:</b> Logga in i Mitt Konto där kan du se om din orderstatus är ändrad till skickad.</p>

					<br>

					<p>Du kan ringa oss mellan 08.00 till 12.00 vardagar eller maila oss på
					info@streetwheels.se dygnet runt.</p>
					
					<br>
					
		            <p><b>Reklamationer</b><br>
					Skicka ett mail till info@streetwheels.se med ämne Reklamation + order
					nummer "Reklamation 1234". Bifoga även en bild på skadan och utförlig
					information om skadan.</p>
 --}}
		            



            	</div>


            </div>
        </div>
        <!--/row || innerPage end-->
        <div style="clear:both"></div>
    </div>
    <!-- ./main-container -->
    {{-- <div class="gap"></div> --}}
</div>
<!-- /main-container -->

@endsection