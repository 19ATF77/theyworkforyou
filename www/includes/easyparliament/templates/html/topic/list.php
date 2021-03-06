<div class="full-page topic-home">
    <div class="full-page__row">

        <h1>Topics</h1>

        <p class="topic-home__intro">
        Need to know more about a particular area? Here's where to find debates
       and votes on the most popular topics covered in Parliament.
        </p>

        <ul class="topic-list">
          <?php foreach ($topics as $topic): ?>
            <li>
                <a href="<?= $topic->url(); ?>">
                    <img src="<?= $topic->image_url(); ?>">
                    <?= _htmlspecialchars($topic->title()); ?>
                </a>
                <p><?= _htmlspecialchars($topic->description()); ?></p>
            </li>
          <?php endforeach; ?>
        </ul>

    </div>
</div>
