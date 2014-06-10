<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<?php if (isset($users) && count($users)): ?>
			<table class="table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Usernamr</th>
						<th>Email</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($users as $user): ?>
					<tr>
						<td><?php echo $user['id']; ?></td>
						<td><?php echo $user['username']; ?></td>
						<td><?php echo $user['email']; ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif;?>
	</div>
</div>