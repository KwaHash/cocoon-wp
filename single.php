<?php get_header(); ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('a[href^="#toc"]').forEach(function (link) {
    link.classList.add('js-link_scroller');

    link.addEventListener('click', function (e) {
      const targetId = this.getAttribute('href');
      const targetEl = document.querySelector(targetId);

      if (!targetEl) return;

      e.preventDefault();

      const headerOffset = 0; // 固定ヘッダーがある場合は数値を調整
      const elementPosition = targetEl.getBoundingClientRect().top;
      const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

      window.scrollTo({
        top: offsetPosition,
        behavior: 'smooth'
      });
    });
  });
});
</script>

<?php
// Determine header based on post category
$categories = get_the_category();
$is_news = false;
foreach ($categories as $category) {
  if ($category->slug === 'news') {
    $is_news = true;
    break;
  }
}

if ($is_news) {
  $section_title = 'News';
  $section_subtitle = 'お知らせ';
} else {
  $section_title = 'Column';
  $section_subtitle = '苑子の法律コラム';
}
?>

<main class="c-archive">
  <div class="c-archive-section-header">
    <h1 class="c-archive-section-title"><?php echo esc_html($section_title); ?></h1>
    <p class="c-archive-section-subtitle"><?php echo esc_html($section_subtitle); ?></p>
  </div>
  <div class="c-archive-wrapper">
    <div class="a-single-main">
      <?php if (have_posts()):
        while (have_posts()):
          the_post(); ?>
          
          <div class="c-single-header">
            <h1 class="c-single-title"><?php the_title(); ?></h1>
          </div>

          <?php if (has_post_thumbnail()): ?>
            <div class="c-single-eyecatch">
              <?php the_post_thumbnail('', array('class' => 'c-single-eyecatch-img')); ?>
            </div>
          <?php endif; ?>

          <div class="c-single-meta">
            投稿日: <?php echo get_the_date('Y.n.j'); ?>
          </div>

          <div class="c-single-body">
            <?php the_content(); ?>
          </div>

          <?php
          // Previous and Next Post Navigation
          $prev_post = get_previous_post();
          $next_post = get_next_post();
          ?>
          
          <?php if ($prev_post || $next_post): ?>
            <div class="c-single-navigation">
              <?php if ($prev_post): ?>
                <div class="c-single-nav-item c-single-nav-prev">
                  <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" class="c-single-nav-link">
                    <span class="c-single-nav-arrow">&lt;</span>
                    <span class="c-single-nav-title"><?php echo esc_html(get_the_title($prev_post->ID)); ?></span>
                  </a>
                </div>
              <?php else: ?>
                <div class="c-single-nav-item c-single-nav-prev"></div>
              <?php endif; ?>

              <?php if ($next_post): ?>
                <div class="c-single-nav-item c-single-nav-next">
                  <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" class="c-single-nav-link">
                    <span class="c-single-nav-title"><?php echo esc_html(get_the_title($next_post->ID)); ?></span>
                    <span class="c-single-nav-arrow">&gt;</span>
                  </a>
                </div>
              <?php else: ?>
                <div class="c-single-nav-item c-single-nav-next"></div>
              <?php endif; ?>
            </div>
          <?php endif; ?>

        <?php endwhile;
      endif; ?>
    </div>
    <?php get_sidebar(); ?>
  </div>
</main>

<?php get_footer(); ?>
