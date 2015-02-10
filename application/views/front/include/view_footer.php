			<footer data-role="footer">
				<p class="footer" style="text-align: center">Propulsé par Codeigniter - Temps d'exécution : <strong>{elapsed_time}</strong> secondes - <a href="<?php echo base_url('contact'); ?>">Contact</a></p>
			</footer>

		</div><!-- end of .container -->

		<?php echo js_url('jquery.min'); ?>
		<?php echo js_url('bootstrap.min'); ?>
		<?php echo js_url('material.min'); ?>
		<?php echo js_url('ripples.min'); ?>
		<script>
			$.material.init();
		</script>

		<?php if ($page == 'content'): ?>
		<?php echo js_url('prism'); ?>
		<?php endif; ?>

	</body>
</html>
