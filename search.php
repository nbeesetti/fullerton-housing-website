<?php get_header(); ?>

<main>
    <h1>Search Results for: <?php echo get_search_query(); ?></h1>

    <?php
    $search_query = get_search_query();
    $youtube_videos = fetch_youtube_videos($search_query);

    if (!empty($youtube_videos)): ?>
        <h2>YouTube Videos</h2>
        <ul>
            <?php foreach ($youtube_videos as $video): ?>
                <li>
                    <a href="https://www.youtube.com/watch?v=<?php echo $video['id']['videoId']; ?>" target="_blank">
                        <img src="<?php echo $video['snippet']['thumbnails']['default']['url']; ?>" alt="<?php echo esc_attr($video['snippet']['title']); ?>">
                        <p><?php echo esc_html($video['snippet']['title']); ?></p>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No videos found.</p>
    <?php endif; ?>
</main>

<?php get_footer(); ?>

/
make sure searches like this 
<form role="search" method="get" id="searchform" class="searchform" action="<?php echo home_url('/'); ?>">
    <div>
        <label class="screen-reader-text" for="s">Search for:</label>
        <input type="text" value="" name="s" id="s" />
        <input type="submit" id="searchsubmit" value="Search" />
    </div>
</form>
/ 