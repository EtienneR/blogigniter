<?php 
	echo form_open_multipart(base_url('admin/medias/upload'));
?>
	<div class="form-group">
		<input class="btn" type="file" name="userfile" size="20" />
		<input class="btn btn-primary" type="submit" value="Envoyer" />
	</div>
</form>

<div class="row">
	<?php if (!empty($query)): ?>
	<?php foreach ($query as $row): ?>
	<div class="col-sm-6 col-md-2">
		<div class="thumbnail">
			<?php // faire un var_dump($query) pour comprendre
				$var = $row['relative_path'];
				$var = strstr($var, 'assets');
				$var = str_replace("\\","/", $var);
			?>
			<a data-toggle="lightbox" data-gallery="multiimages" data-title="<?php echo $row['name']; ?>" href="<?php echo base_url('assets/img/' . str_replace('_thumb', '', $row['name'])); ?>">
				<img src="<?php echo base_url($var . '/' . $row['name']); ?>" alt="<?php echo $row['name']; ?>" width="150px" />
			</a>
			<div class="caption">
				<h6 class="text-center">
					<?php echo substr($row['name'], 0, 25); ?>
				</h6>
				<p class="text-center">
					<a href="?delete=<?php echo $row['name']; ?>" onclick="return deleteConfirm()" class="btn btn-default" role="button" title="Supprimer"><i class="glyphicon glyphicon-trash"></i></a>
				</p>
			</div>
		</div>
	</div>
	<?php endforeach; ?>

	<script>
		function deleteConfirm() {
			var a = confirm("Etes-vous sur de vouloir supprimer cette image ?!");
			if (a){
				return true;
			}
			else{
				return false;
			}
		}
	</script>

	<?php else: ?>
		<p>Aucune image n'est disponible</p>
	<?php endif;?>
	
</div>