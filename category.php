<?php get_header(); ?>

<main class="c-archive">
  <div class="c-archive-section-header">
    <h1 class="c-archive-section-title">Column</h1>
    <p class="c-archive-section-subtitle">苑子の法律コラム</p>
  </div>
  <div class="c-archive-wrapper">
    <div class="a-archive-main">
      <div class="c-archive-titlewrap">
        <h2 class="c-archive-title"><?php single_cat_title(); ?></h2>
      </div>

      <div class="c-archive-list">
        <?php if (have_posts()):
          while (have_posts()):
            the_post(); ?>
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
      global $wp_query;
      $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
      $max_pages = $wp_query->max_num_pages;

      if ($max_pages > 1): ?>
        <div class="c-archive-pagination">
          <?php
          // Next page button (only show if not on last page)
          $next_page = $paged + 1;
          $prev_page = $paged - 1;
          if ($next_page <= $max_pages):
            $next_link = get_pagenum_link($next_page);
          endif;

          if ($prev_page > 0):
            $prev_link = get_pagenum_link($prev_page);
          endif;
          ?>

          <div class="c-pagination-numbers">
            <?php
            // Calculate page range to show
            $pages_to_show = 4;
            $start_page = max(1, min($paged - floor($pages_to_show / 2), $max_pages - $pages_to_show + 1));
            $end_page = min($max_pages, $start_page + $pages_to_show - 1);

            if ($prev_page > 0):
              ?>
              <a href="<?php echo esc_url($prev_link); ?>" class="c-pagination-arrow">&lt;</a>
            <?php endif; ?>

            <?php
            // Page numbers
            for ($i = $start_page; $i <= $end_page; $i++):
              if ($i == $paged):
                ?>
                <span class="c-pagination-number c-pagination-current"><?php echo esc_html($i); ?></span>
              <?php else: ?>
                <a href="<?php echo esc_url(get_pagenum_link($i)); ?>"
                  class="c-pagination-number"><?php echo esc_html($i); ?></a>
                <?php
              endif;
            endfor;

            // Right arrow (next page)
            if ($next_page <= $max_pages):
              ?>
              <a href="<?php echo esc_url($next_link); ?>" class="c-pagination-arrow">&gt;</a>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
    <?php get_sidebar(); ?>
</main>
</div>

<?php get_footer(); ?>