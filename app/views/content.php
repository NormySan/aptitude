<section class="container">
	<div class="jumbotron">
		<h1><?php echo $title; ?></h1>
		<p><?php echo $text; ?></p>
		<p>
			<a class="btn btn-primary btn-lg" role="button">Learn more</a>
		</p>
	</div>
</section>

<!-- Articles -->
<section class="container">
	<?php if (isset($articles) && count($articles)): ?>
	
		<?php foreach($articles as $article): ?>

			<article class="article-<?php echo $article['id']; ?>">

				<h3><?php echo $article['title']; ?></h3>

				<div class="article-meta">
					<span>Posted on <?php echo $article['created_at']; ?></span>
					<span> by <a href="/users/<?php echo $article['author']; ?>"><?php echo $article['username']; ?></a></span>
				</div>

				<p>
					<?php echo $article['body']; ?>
				</p>
			</article>

		<?php endforeach; ?>
	
	<?php endif; ?>
</section>