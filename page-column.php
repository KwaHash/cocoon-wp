<?php
get_header();

// Store the current page ID and permalink BEFORE any queries
$current_page_id = get_the_ID();
$current_page_permalink = get_permalink($current_page_id);

// Get the "news" category ID
$news_category = get_category_by_slug('news');
$news_cat_id = $news_category ? $news_category->term_id : 0;

// Query posts excluding "news" category
// For pages, WordPress uses 'page' in URL but 'paged' in WP_Query
$paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
// Also check 'page' query var (used by WordPress for page pagination)
if (!$paged) {
  $paged = get_query_var('page') ? absint(get_query_var('page')) : 1;
}
// Fallback to $_GET if query vars not set
if (!$paged && isset($_GET['paged'])) {
  $paged = absint($_GET['paged']);
}
if (!$paged && isset($_GET['page'])) {
  $paged = absint($_GET['page']);
}
if (!$paged) {
  $paged = 1;
}

$custom_query = new WP_Query(array(
  'post_type' => 'post',
  'posts_per_page' => get_option('posts_per_page'),
  'paged' => $paged,
  'category__not_in' => array($news_cat_id),
  'orderby' => 'date',
  'order' => 'DESC'
));
?>

<main class="c-archive">
  <div class="c-archive-section-header">
    <h1 class="c-archive-section-title">Column</h1>
    <p class="c-archive-section-subtitle">苑子の法律コラム</p>
  </div>
  <div class="c-archive-wrapper">
    <div class="a-archive-main">
      <div class="c-archive-titlewrap">
        <h2 class="c-archive-title">Column</h2>
      </div>

      <div class="c-archive-list">
        <?php if ($custom_query->have_posts()):
          while ($custom_query->have_posts()):
            $custom_query->the_post(); ?>
            <article class="c-archive-item">
              <div class="c-archive-thumb">
                <?php if (has_post_thumbnail()): ?>
                  <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('medium', array('class' => 'c-archive-thumb-img')); ?>
                  </a>
                <?php else: ?>
                  <a href="<?php the_permalink(); ?>">
                    <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/imgs/no-image.png'); ?>" alt=""
                      class="c-archive-thumb-img">
                  </a>
                <?php endif; ?>
              </div>
              <div class="c-archive-body">
                <h2 class="c-archive-item-title">
                  <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h2>
                <div class="c-archive-item-excerpt">
                  <?php echo wp_trim_words(get_the_excerpt(), 100, '...'); ?>
                </div>
                <div class="c-archive-item-date">
                  投稿日: <?php echo get_the_date('Y/n/j'); ?>
                </div>
              </div>
            </article>
          <?php endwhile; else: ?>
          <p class="c-archive-empty">記事が見つかりませんでした。</p>
        <?php endif; ?>
      </div>

      <?php
      // Custom pagination
      $max_pages = $custom_query->max_num_pages;

      if ($max_pages > 1):
        // Use the stored page permalink (not from current post context)
        $page_permalink = $current_page_permalink;

        // Handle both pretty permalinks and query string permalinks
        global $wp_rewrite;
        $using_permalinks = $wp_rewrite->using_permalinks();
        ?>
        <div class="c-archive-pagination">
          <?php
          // Next page button (only show if not on last page)
          $next_page = $paged + 1;
          $prev_page = $paged - 1;

          // Build pagination links - ensure we use the correct base URL
          if ($next_page <= $max_pages):
            if ($using_permalinks) {
              // For pretty permalinks: /page-name/page/2/
              $next_link = esc_url(user_trailingslashit(trailingslashit($page_permalink) . 'page/' . $next_page));
            } else {
              // For query strings: /page-name/?paged=2
              $next_link = esc_url(add_query_arg('paged', $next_page, $page_permalink));
            }
          endif;

          if ($prev_page > 0):
            if ($using_permalinks) {
              // Page 1 should be just the base URL
              $prev_link = $prev_page == 1 ? esc_url($page_permalink) : esc_url(user_trailingslashit(trailingslashit($page_permalink) . 'page/' . $prev_page));
            } else {
              // Page 1 should remove the paged parameter
              $prev_link = $prev_page == 1 ? esc_url(remove_query_arg('paged', $page_permalink)) : esc_url(add_query_arg('paged', $prev_page, $page_permalink));
            }
          endif;
          ?>

          <div class="c-pagination-numbers">
            <?php
            // Calculate page range to show
            $pages_to_show = 4;
            $start_page = max(1, min($paged - floor($pages_to_show / 2), $max_pages - $pages_to_show + 1));
            $end_page = min($max_pages, $start_page + $pages_to_show - 1);

            if ($prev_page > 0 && isset($prev_link)):
              ?>
              <a href="<?php echo esc_url($prev_link); ?>" class="c-pagination-arrow">&lt;</a>
            <?php endif; ?>

            <?php
            // Page numbers
            for ($i = $start_page; $i <= $end_page; $i++):
              if ($i == $paged):
                ?>
                <span class="c-pagination-number c-pagination-current"><?php echo esc_html($i); ?></span>
              <?php else:
                if ($using_permalinks) {
                  // Page 1 should be just the base URL
                  $page_link = $i == 1 ? esc_url($page_permalink) : esc_url(user_trailingslashit(trailingslashit($page_permalink) . 'page/' . $i));
                } else {
                  // Page 1 should remove the paged parameter
                  $page_link = $i == 1 ? esc_url(remove_query_arg('paged', $page_permalink)) : esc_url(add_query_arg('paged', $i, $page_permalink));
                }
                ?>
                <a href="<?php echo esc_url($page_link); ?>" class="c-pagination-number"><?php echo esc_html($i); ?></a>
                <?php
              endif;
            endfor;

            // Right arrow (next page)
            if ($next_page <= $max_pages && isset($next_link)):
              ?>
              <a href="<?php echo esc_url($next_link); ?>" class="c-pagination-arrow">&gt;</a>
            <?php endif; ?>
          </div>
        </div>
      <?php endif;

      // Reset post data
      wp_reset_postdata(); ?>
    </div>
    <?php get_sidebar(); ?>
</main>
</div>

<?php get_footer(); ?>