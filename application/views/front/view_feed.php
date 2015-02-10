<?php echo '<?xml version="1.0" encoding="' . $encoding . '"?>' . "\n"; ?>
<rss version="2.0"
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
   xmlns:admin="http://webns.net/mvcb/"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:content="http://purl.org/rss/1.0/modules/content/">

	<channel>

		<description><?php echo $site_description; ?></description>
		<link><?php echo $site_link; ?></link>
		<title><?php echo $site_name; ?></title>
		<dc:language><?php echo $page_language; ?></dc:language>
		<dc:rights>Copyright <?php echo gmdate("Y", time()); ?></dc:rights>

		<?php foreach ($posts->result() as $row): ?>
		<item>
			<title><?php echo xml_convert($row->c_title); ?></title>
			<link><?php echo base_url($row->r_url_rw . '/' . $row->c_url_rw); ?></link>
			<guid><?php echo base_url($row->r_url_rw . '/' . $row->c_url_rw); ?></guid>
			<?php $c_content = strip_tags($row->c_content); ?>
			<description>
				<?php if (!empty($row->c_image)): ?>
					<img src="<?php echo $row->c_image; ?>" alt="" class="img-responsive" style="margin: 0 auto;" width="280px" heigth="120px" />
				<?php endif; ?>
				<?php echo $c_content; ?>
			</description>
			<?php $date = strtotime($row->c_cdate); ?>
			<pubDate><?php echo date('r', $date);?></pubDate>
		</item>
		<?php endforeach; ?>

	</channel>

</rss>