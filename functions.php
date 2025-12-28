<?php //子テーマ用関数
if ( !defined( 'ABSPATH' ) ) exit;

//子テーマ用のビジュアルエディタースタイルを適用
add_editor_style();

//以下に子テーマ用の関数を書く

// Add 'paged' query var for custom page templates
add_filter('query_vars', function($vars) {
    $vars[] = 'paged';
    return $vars;
});