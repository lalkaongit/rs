<?php
use App\Specialties;
// Home
Breadcrumbs::for('admin', function ($trail) {
    $trail->push('Административная панель', route('admin'));
});

// Home > About
Breadcrumbs::for('about', function ($trail) {
    $trail->parent('admin');
    $trail->push('About', route('about'));
});

// Home > Blog
Breadcrumbs::for('specialties', function ($trail) {
    $trail->parent('admin');
    $trail->push('Специальности', route('specialties'));
});

// Home > Blog > [Category]
Breadcrumbs::for('specialty', function ($trail, $specialty) {
    $trail->parent('specialties');
    $trail->push($specialty->name, route('specialties', $specialty->id));
});

// Home > Blog > [Category] > [Post]
Breadcrumbs::for('post', function ($trail, $post) {
    $trail->parent('category', $post->category);
    $trail->push($post->title, route('post', $post->id));
});
