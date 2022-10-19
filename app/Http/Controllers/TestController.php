<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Weidner\Goutte\GoutteFacade as Goutte;

class TestController extends Controller
{
    public function test(Request $request)
    {
        try {
            // https://symfony.com/doc/current/components/browser_kit.html
            
            $crawler = Goutte::request('GET', 'https://duckduckgo.com/html/?q=Laravel');
            dump($crawler);
            $crawler->filter('.result__title .result__a')->each(function ($node) {
                dump($node->text());
            });
        } catch (Exception $e) {
            dump($e->getMessage());
        }

        return view('welcome');
    }
}
