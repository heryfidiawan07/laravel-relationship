<?php

use Illuminate\Support\Facades\DB;

use App\User;
use App\Profile;
use App\Post;
use App\Category;
use App\Comment;

Route::get('/create_user', function () {
    DB::table('users')->insert([
    	['name' => 'Hery Fidiawan', 'email' => 'heryfidiawan@gmail.com'],
	    ['name' => 'Wawan', 'email' => 'wawan@gmail.com'],
	    ['name' => 'Fitri', 'email' => 'fitri@gmail.com']
    ]);
    return 'User created';
});

Route::get('/show_user', function () {
	$user = User::findOrFail(2);
	$phone = 'User belum memiliki profile/phone';
	if ($user->profile) {
		$phone = $user->profile->phone;
	}
	return 'Name = ' . $user->name . ' - Phone = ' . $phone;
});

Route::get('/create_profile', function () {
	$user = User::findOrFail(1);
	$user->profile()->create([
		'phone' => '0822133173147'
	]);
	return 'Profile ' . $user->name . ' created';
});

Route::get('/all_user', function (){
	$users = User::all();
	foreach ($users as $user) {
		$phone = 'User belum memiliki profile/phone';
		if ($user->profile) {
			$phone = $user->profile->phone;
		}
		echo "Nama " . $user->name . ' - Phone = ' . $phone . '<br>';
	}
});

Route::get('/show_profile', function (){
	$profile = Profile::findOrFail(2);
	return 'Phone ' . $profile->phone . ' dimiliki oleh ' . $profile->user->name;
});

Route::get('/create_post', function () {
	$user = User::findOrFail(2);
	$user->posts()->create([
		'title' => 'Title ketiga dari user_id 2',
		'body'	=> 'Body ketiga dari user_id 2'
	]);
	return 'Post created';
});

Route::get('/update_post', function (){
	$user = User::findOrFail(1);
	$user->posts()->whereId(1)->update([
		'title' => 'Title pertama dari user_id 1 telah di update --',
		'body'	=> 'Body pertama dari user_id 1 telah di update --'
	]);
	return redirect('/all_post');
});

Route::get('/all_post', function () {
	$posts = Post::all();
	foreach ($posts as $post) {
		echo 'Title ' . $post->title . ', Penulis = ' . $post->user->name . '<br>';
	}
});

Route::get('/penulis_post', function (){
	$user = User::findOrFail(1);
	if ($user->posts) {
		foreach ($user->posts as $post) {
			echo $post->title . ' ditulis oleh '. $user->name . '<br>';
		}
	}else{
		return $user->name . ' belum memiliki post.';
	}
});

Route::get('/create_category', function () {
	DB::table('categories')->insert([
		['name' => 'Category 1'],
		['name' => 'Category 2'],
		['name' => 'Category 3'],
		['name' => 'Category 4'],
		['name' => 'Category 5'],
	]);
	return 'Category created';
});

Route::get('/all_category', function () {
	$categories = Category::all();
	foreach ($categories as $category) {
		echo $category->name . '<br>';
	}
});

Route::get('/attach_post_category', function () {
	$post = Post::findOrFail(2);
	$post->categories()->attach([1,3,4]);
	return 'Success';
});

Route::get('/sync_post_category', function () {
	$post = Post::findOrFail(2);
	$post->categories()->sync([1,4,5]);
	return 'Success';
});

Route::get('/detach_post_category', function () {
	$post = Post::findOrFail(2);
	$post->categories()->sync([1,5]);
	return 'Success';
});

Route::get('/show_post_category', function () {
	$post = Post::findOrFail(2);
	foreach ($post->categories as $category) {
		echo $post->title . ' - ' . $category->name . '<br>';
	}
});

Route::get('/show_category_post', function () {
	$category = Category::findOrFail(1);
	foreach ($category->posts as $post) {
		echo $category->name . ' - ' . $post->title . '<br>';
	}
});

Route::get('/create_comment', function () {
	$user = User::findOrFail(2);
	$post = Post::findOrFail(2);
	$post->comments()->create([
		'user_id' => $user->id,
		'body' => 'Komentar kedua pada post pertama oleh user_id 2',
	]);
	return 'Success';
});

Route::get('/show_post_comment', function () {
	$post = Post::findOrFail(2);
	// return $post->comments->count();
	if ($post->comments->count() == 0) {
		return $post->title . ', (post ini belum mempunyai komentar.)';
	}
	foreach ($post->comments as $comment) {
		echo $post->title . ', isi komentar = ' . $comment->body . ', oleh - '. $comment->user->name . '<br>';
	}
});