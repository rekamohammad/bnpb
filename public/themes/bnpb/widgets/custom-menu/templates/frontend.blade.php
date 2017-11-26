<div class="col-md-2 col-sm-6">
    <h4><a href="#">{{ __($config['name']) }}</a></h4>
    {!!
        Menu::generateMenu([
            'slug' => $config['menu_id'],
            'options' => ['class' => 'footer-menu', 'role' => 'menu'],
        ])
    !!}
</div>