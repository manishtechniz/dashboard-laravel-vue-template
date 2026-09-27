<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::withoutMiddleware(['auth'])->group(function () {
    Route::get('/', fn() => view('frontend::home.index'))
        ->name('frontend.home');

    Route::get('/gem', fn() => view('frontend::home.gemni-index'))
        ->name('frontend.gem');

    Route::get('/cla', fn() => view('frontend::home.claude-index'))
        ->name('frontend.cla');

    Route::get('/about-us', function (Request $request) {
        return view('frontend::about.index');
    })->name('frontend.about');

    Route::get('/privacy-policy', function (Request $request) {
        return view('frontend::privacy.index');
    })->name('frontend.privacy');

    Route::get('/disclaimer', function (Request $request) {
        return view('frontend::disclaimer.index');
    })->name('frontend.disclaimer');

    // SEO Landing Pages
    $seoRoutes = [
        'best-club-in-gurugram',
        'night-club-gurugram',
        'best-nightclub-in-gurgaon',
        'best-clubs-in-gurugram',
        'best-clubs-in-gurgaon',
        'night-clubs-in-gurugram',
        'night-clubs-in-gurgaon',
        'club-booking-gurugram',
        'club-booking-gurgaon',
        'nightlife-in-gurugram',
        'nightlife-in-gurgaon',
        'clubs-in-sector-29',
        'clubs-near-cyber-hub',
        'clubs-on-mg-road-gurgaon',
        'gurgaon-weekend-party'
    ];
    foreach ($seoRoutes as $route) {
        Route::get('/' . $route, function () use ($route) {
            return view('frontend::seo.' . $route);
        })->name('frontend.seo.' . str_replace('-', '_', $route));
    }

    Route::prefix('frontend')->group(function () {
        Route::get('/club-assets', [App\Http\Controllers\Frontend\FrontendDataController::class, 'clubAssets'])->name('frontend.api.club_assets');
        Route::get('/testimonials', [App\Http\Controllers\Frontend\FrontendDataController::class, 'testimonials'])->name('frontend.api.testimonials');
    });

    /**
     * Sitemap XML
     */
    Route::get('/sitemap.xml', function () {
        $xml = cache()->remember('sitemap.xml', now()->addDay(), function () {
            // $xml = cache()->remember('sitemap.xml', 0, function () { 
            $urls = collect(config('sitemap.static'))
                ->map(function ($data, $route) {
                    return [
                        'url' => route($route),
                        'lastmod' => sitemapLastModified($data['file_slug']),
                    ];
                })
                ->merge(
                    collect(config('sitemap.seo'))
                        ->map(fn($slug) => [
                            'url' => url($slug),
                            'lastmod' => sitemapLastModified('seo/' . $slug),
                        ])
                )->values();
            // dd($urls);
            return view('frontend::sitemap', compact('urls'))->render();
        });

        return response($xml)
            ->header('Content-Type', 'application/xml');
    })->name('frontend.sitemap');
});
