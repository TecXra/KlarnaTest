<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<!-- created with Free Online Sitemap Generator www.xml-sitemaps.com -->

<url>
  <loc>https://www.hjulonline.se/</loc>
</url>
<url>
  <loc>https://www.hjulonline.se/falgar</loc>
</url>
<url>
  <loc>https://www.hjulonline.se/sommardack</loc>
</url>
<url>
  <loc>https://www.hjulonline.se/friktionsdack</loc>
</url>
<url>
  <loc>https://www.hjulonline.se/dubbdack</loc>
</url>
<url>
  <loc>https://www.hjulonline.se/blogg</loc>
</url>
<url>
  <loc>https://www.hjulonline.se/kontakt</loc>
</url>
<url>
  <loc>https://www.hjulonline.se/kopvillkor</loc>
</url>
<url>
  <loc>https://www.hjulonline.se/omoss</loc>
</url>

@foreach($tires as $tire)
  <url>
    <loc>https://www.hjulonline.se/dack/{{ str_slug($tire->product_brand) }}</loc>
  </url>
@endforeach

@foreach($rims as $rim)
  <url>
    <loc>https://www.hjulonline.se/falg/{{ str_slug($rim->product_brand) }}</loc>
  </url>
@endforeach
</urlset>