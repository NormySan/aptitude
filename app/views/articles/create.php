<div class="row">
	<div class="col-sm-12 col-md-8 col-md-offset-2">
		<form action="/articles" method="POST">
			
			<div class="form-group">
				<label for="title">Title</label>
				<input type="text" name="title" id="title" class="form-control" required>
			</div>

			<div class="form-group">
				<label for="author">Author</label>
				<select name="author" id="author" class="form-control" required>
					<option value="">-- Select article author --</option>
					<?php if (isset($users) && count($users)): ?>
						<?php foreach ($users as $user): ?>
							<option value="<?php echo $user['id']; ?>"><?php echo $user['username']; ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>

			<div class="form-group">
				<label for="body">Text</label>
				<textarea name="body" id="body" cols="30" rows="10" class="form-control" required></textarea>
			</div>

			<button type="submit" class="btn btn-default">Save articles</button>

		</form>
	</div>
</div>