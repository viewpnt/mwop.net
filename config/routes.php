<?php

namespace Mwop;

use Zend\Expressive\Csrf\CsrfMiddleware;
use Zend\Expressive\Session\SessionMiddleware;

// General pages
$app->get('/', HomePage::class, 'home');
$app->get('/comics', ComicsPage::class, 'comics');
$app->get('/offline', OfflinePage::class, 'offline');
$app->get('/resume', ResumePage::class, 'resume');

// Blog
$app->get('/blog[/]', Blog\ListPostsMiddleware::class, 'blog');
$app->get('/blog/{id:[^/]+}.html', Blog\DisplayPostMiddleware::class, 'blog.post');
$app->get('/blog/tag/{tag:php}.xml', Blog\FeedMiddleware::class, 'blog.feed.php');
$app->get('/blog/{tag:php}.xml', Blog\FeedMiddleware::class, 'blog.feed.php.also');
$app->get('/blog/tag/{tag:[^/]+}/{type:atom|rss}.xml', Blog\FeedMiddleware::class, 'blog.tag.feed');
$app->get('/blog/tag/{tag:[^/]+}', Blog\ListPostsMiddleware::class, 'blog.tag');
$app->get('/blog/{type:atom|rss}.xml', Blog\FeedMiddleware::class, 'blog.feed');

// Logout
$app->get('/auth/logout', [
    SessionMiddleware::class,
    LogoutHandler::class,
], 'logout');


// Contact form
$app->get('/contact[/]', [
    SessionMiddleware::class,
    CsrfMiddleware::class,
    Contact\LandingPage::class,
], 'contact');
$app->post('/contact/process', [
    SessionMiddleware::class,
    CsrfMiddleware::class,
    Contact\Process::class,
], 'contact.process');
$app->get('/contact/thank-you', Contact\ThankYouPage::class, 'contact.thank-you');
