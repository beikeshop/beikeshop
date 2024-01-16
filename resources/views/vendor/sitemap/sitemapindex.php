<?= '<'.'?'.'xml version="1.0" encoding="UTF-8"?>'."\n"; ?>
<?php if (null != $style) {
    echo '<'.'?'.'xml-stylesheet href="'.$style.'" type="text/xsl"?>'."\n";
} ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($sitemaps as $sitemap) : ?>
	<sitemap>
		<loc><?= $sitemap['loc'] ?></loc>
	<?php if ($sitemap['lastmod'] !== null) : ?>
		<lastmod><?= date('Y-m-d\TH:i:sP', strtotime($sitemap['lastmod'])) ?></lastmod>
	<?php endif; ?>
	</sitemap>
<?php endforeach; ?>
</sitemapindex>