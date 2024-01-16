<?= '<'.'?'.'xml version="1.0" encoding="UTF-8"?>'."\n" ?>
<?php if (null != $style) {
    echo '<'.'?'.'xml-stylesheet href="'.$style.'" type="text/xsl"?>'."\n";
} ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">
<?php foreach ($items as $item) : ?>
	<url>
		<loc><?= $item['loc'] ?></loc>
		<mobile:mobile/>
	</url>
<?php endforeach; ?>
</urlset>
