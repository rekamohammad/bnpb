<?php

return [
    'model' => 'News',
    'models' => 'News',
    'list' => 'List News',
    'create' => 'Create new news',
    'edit' => 'Edit news',
    'form' => [
        'name' => 'Name',
        'name_placeholder' => 'News name (Maximum 120 characters)',
        'description' => 'Description',
        'description_placeholder' => 'Short description for news (Maximum 300 characters)',
        'categories' => 'Categories',
        'tags' => 'Tags',
        'tags_placeholder' => 'Tags',
        'content' => 'Content',
        'featured' => 'Is featured?',
        'note' => 'Note content',
        'format_type' => 'Format',
        'slide' => 'Is Set To Slide ?',
    ],
    'notices' => [
        'no_select' => 'Please select at least one record to take this action!',
        'update_success_message' => 'Update successfully',
    ],
    'cannot_delete' => 'News could not be deleted',
    'post_deleted' => 'News deleted',
    'posts' => 'News',
    'edit_this_post' => 'Edit this news',
    'no_new_post_now' => 'There is no new news now!',
    'menu_name' => 'News',
    'all_posts' => 'All News',
];
