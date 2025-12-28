<aside class="c-sidebar">
  <!-- Categories Section -->
  <div class="c-sidebar-section">
    <h3 class="c-sidebar-title">カテゴリー</h3>
    <ul class="c-sidebar-list c-sidebar-categories">
      <?php
      $categories = get_categories(array(
        'orderby' => 'count',
        'order' => 'DESC',
        'hide_empty' => true,
      ));
      foreach ($categories as $category) {
        echo '<li class="c-sidebar-item">';
        echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="c-sidebar-link">' . esc_html($category->name) . '</a>';
        echo '<span class="c-sidebar-count">' . esc_html($category->count) . '</span>';
        echo '</li>';
      }
      ?>
    </ul>
  </div>

  <!-- Popular Articles Section -->
  <div class="c-sidebar-section">
    <h3 class="c-sidebar-title">人気記事</h3>
    <ul class="c-sidebar-list c-sidebar-popular">
      <?php
      // Get popular posts by comment count or views
      $popular_posts = new WP_Query(array(
        'posts_per_page' => 3,
        'orderby' => 'comment_count',
        'order' => 'DESC',
        'ignore_sticky_posts' => 1,
      ));
      
      if ($popular_posts->have_posts()) {
        while ($popular_posts->have_posts()) {
          $popular_posts->the_post();
          echo '<li class="c-sidebar-item c-sidebar-popular-item">';
          echo '<a href="' . esc_url(get_permalink()) . '" class="c-sidebar-link">' . esc_html(get_the_title()) . '</a>';
          echo '</li>';
        }
        wp_reset_postdata();
      }
      ?>
    </ul>
  </div>

  <!-- Search Section -->
  <div class="c-sidebar-section">
    <form role="search" method="get" class="c-sidebar-search" action="<?php echo esc_url(home_url('/')); ?>">
      <input type="search" class="c-sidebar-search-input" placeholder="サイト内を検索" value="<?php echo get_search_query(); ?>" name="s" />
      <button type="submit" class="c-sidebar-search-button">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"></circle>
          <path d="m21 21-4.35-4.35"></path>
        </svg>
      </button>
    </form>
  </div>

  <!-- Archives Section -->
  <div class="c-sidebar-section">
    <h3 class="c-sidebar-title">アーカイブ</h3>
    <ul class="c-sidebar-list c-sidebar-archives">
      <?php
      global $wpdb;
      $archives = $wpdb->get_results("
        SELECT YEAR(post_date) AS year, MONTH(post_date) AS month, COUNT(ID) AS posts
        FROM $wpdb->posts
        WHERE post_type = 'post' AND post_status = 'publish'
        GROUP BY YEAR(post_date), MONTH(post_date)
        ORDER BY post_date DESC
        LIMIT 12
      ");
      
      foreach ($archives as $archive) {
        $url = get_month_link($archive->year, $archive->month);
        $text = sprintf('%d年%d月', $archive->year, $archive->month);
        echo '<li class="c-sidebar-item">';
        echo '<a href="' . esc_url($url) . '" class="c-sidebar-link">' . esc_html($text) . '</a>';
        echo '<span class="c-sidebar-count">' . esc_html($archive->posts) . '</span>';
        echo '</li>';
      }
      ?>
    </ul>
  </div>
</aside>

