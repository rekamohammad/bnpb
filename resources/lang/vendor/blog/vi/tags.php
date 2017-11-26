<?php

return array(
    'model' => 'Tag',
    'models' => 'Tags',
    'list' => 'Danh sách tags',
    'create' => 'Thêm tag mới',
    'edit' => 'Sửa tag',
    'form' =>
        array(
            'name' => 'Tên',
            'name_placeholder' => 'Tên tag (Tối đa 120 kí tự)',
            'description' => 'Mô tả',
            'description_placeholder' => 'Mô tả ngắn cho tag (Tối đa 400 kí tự)',
            'categories' => 'Chuyên mục',
        ),
    'notices' =>
        array(
            'no_select' => 'Chọn ít nhất 1 bài viết để thực hiện hành động này!',
        ),
    'cannot_delete' => 'Không thể xóa tag',
    'deleted' => 'Xóa tag thành công',
    'menu_name' => 'Thẻ',
);
