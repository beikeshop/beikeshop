<?= '<'.'?'.'xml version="1.0" encoding="UTF-8"?>'."\n"; ?>
<rss version="2.0" xmlns:ror="http://rorweb.com/0.1/" >
<channel>
	<title><?= $channel['title'] ?></title>
	<link><?= $channel['link'] ?></link>
<?php foreach ($items as $item) : ?>
	<item>
		<link><?= $item['loc'] ?></link>
		<title><?= $item['title'] ?></title>
		<ror:updated><?= date('Y-m-d\TH:i:sP', strtotime($item['lastmod'])) ?></ror:updated>
		<ror:updatePeriod><?= $item['freq'] ?></ror:updatePeriod>
		<ror:sortOrder><?= $item['priority'] ?></ror:sortOrder>
		<ror:resourceOf>sitemap</ror:resourceOf>
	</item>
<?php endforeach; ?>
</channel>
</rss>