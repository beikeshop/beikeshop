<?= '<'.'?'.'xml version="1.0" encoding="UTF-8"?>'."\n" ?>
<?php if (null != $style) {
    echo '<'.'?'.'xml-stylesheet href="'.$style.'" type="text/xsl"?>'."\n";
} ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
<?php foreach ($items as $item) : ?>
	<url>
	<loc><?= $item['loc'] ?></loc>
	<?php
        if ($item['lastmod'] !== null) {
            echo '<lastmod>'.date('Y-m-d\TH:i:sP', strtotime($item['lastmod'])).'</lastmod>'."\n";
        }
    ?>
	<?php
        if (! empty($item['alternates'])) {
            foreach ($item['alternates'] as $alternate) {
                echo '<xhtml:link rel="alternate" media="'.$alternate['media'].'" href="'.$alternate['url'].'" />'."\n";
            }
        }
    ?>
	<news:news>
		<news:publication>
		<news:name><?= $item['googlenews']['sitename'] ?></news:name>
		<news:language><?= $item['googlenews']['language']  ?></news:language>
		</news:publication>
		<news:publication_date><?= date('Y-m-d\TH:i:sP', strtotime($item['googlenews']['publication_date'])) ?></news:publication_date>
		<news:title><?= $item['title'] ?></news:title>
	<?php
    if (isset($item['googlenews']['access'])) {
        echo "\t\t".'<news:access>'.$item['googlenews']['access'].'</news:access>'."\n";
    }

    if (isset($item['googlenews']['keywords'])) {
        echo "\t\t".'<news:keywords>'.implode(',', $item['googlenews']['keywords']).'</news:keywords>'."\n";
    }

    if (isset($item['googlenews']['genres'])) {
        echo "\t\t".'<news:genres>'.implode(',', $item['googlenews']['genres']).'</news:genres>'."\n";
    }

    if (isset($item['googlenews']['stock_tickers'])) {
        echo "\t\t".'<news:stock_tickers>'.implode(',', $item['googlenews']['stock_tickers']).'</news:stock_tickers>'."\n";
    }
    ?>
	</news:news>
	</url>
<?php endforeach; ?>
</urlset>
