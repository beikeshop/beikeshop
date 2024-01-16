<?= '<'.'?'.'xml version="1.0" encoding="UTF-8"?>'."\n"; ?>
<rdf:RDF xmlns="http://rorweb.com/0.1/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
<Resource rdf:about="sitemap">
	<title><?= $channel['title'] ?></title>
	<url><?= $channel['link'] ?></url>
	<type>sitemap</type>
</Resource>
<?php foreach ($items as $item) : ?>
<Resource>
	<url><?= $item['loc'] ?></url>
	<title><?= $item['title'] ?></title>
	<updated><?= date('Y-m-d\TH:i:sP', strtotime($item['lastmod'])) ?></updated>
	<updatePeriod><?= $item['freq'] ?></updatePeriod>
	<sortOrder><?= $item['priority'] ?></sortOrder>
	<resourceOf rdf:resource="sitemap"/>
</Resource>
<?php endforeach; ?>
</rdf:RDF>