<?php

it('serves core seo pages with http 200', function () {
    $this->get('/')->assertOk();
    $this->get('/features')->assertOk();
    $this->get('/pricing')->assertOk();
    $this->get('/blog')->assertOk();
    $this->get('/blog/best-email-marketing-tools')->assertOk();
});

it('serves sitemap.xml as xml', function () {
    $response = $this->get('/sitemap.xml');

    $response->assertOk();
    expect($response->headers->get('content-type'))->toStartWith('application/xml');
    expect($response->getContent())->toContain('<?xml version="1.0" encoding="UTF-8"?>');
    expect($response->getContent())->toContain('<urlset');
});

it('serves robots.txt as plain text with sitemap entry', function () {
    $response = $this->get('/robots.txt');

    $response->assertOk();
    expect($response->headers->get('content-type'))->toStartWith('text/plain');
    expect($response->getContent())->toContain('User-agent: *');
    expect($response->getContent())->toContain('Sitemap:');
});
