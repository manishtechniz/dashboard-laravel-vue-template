<?php

namespace App\Http\Controllers\Frontend;

use App\Model\ClubAsset;
use App\Model\Testimonial;
use Illuminate\Http\Request;

class FrontendDataController extends Controller
{

    public function index()
    {
        // dd(url('/'));
        // return dd(app()->environment('APP_ENV'), app()->environment('production'), env('APP_ENV'));
        // if (request()->ajax() || request()->wantsJson()) {
        //     return response()->json([
        //         'data' => $settings,
        //     ]);
        // }

        $data = getSystemConfigArray('website');

        return view('frontend::home.index', compact('data'));
    }

    /**
     * Fetch club assets (gallery items) with pagination.
     */
    public function clubAssets(Request $request)
    {
        // Assuming ClubAsset has 'type' => 'image', 'is_active' or similar, we just fetch all for now
        // Adjust ordering and conditions as needed.
        // $perPage = $request->input('per_page', 6);
        $perPage = 6;
        $fileType = $request->input('file_type', null);

        if ($fileType == 'image') {
            $fileType = ['image', 'image_url'];
        }

        if ($fileType == 'video') {
            $fileType = ['video', 'video_url'];
        }

        $assets = ClubAsset::where('is_active', 1)
            ->when(! empty($fileType), function ($q) use ($fileType) {
                $q->whereIn('file_type', $fileType);
            })
            ->orderBy('created_at', 'desc')->paginate($perPage);
        // dd($assets);
        // Transform the data slightly to match the frontend expected structure if needed
        $transformed = $assets->getCollection()->map(function ($asset) {
            return [
                'file_url' => $asset->file_url
            ];
        });

        $assets->setCollection($transformed);
        // return response()->json([]);
        return response()->json($assets);
    }

    /**
     * Fetch published testimonials with pagination.
     */
    public function testimonials(Request $request)
    {
        $perPage = $request->input('per_page', 50);

        $testimonials = Testimonial::with('client:id,name,avatar')
            ->select('client_id', 'comment', 'rating')
            ->where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // $transformed = $testimonials->getCollection()->map(function ($t) {
        //     return [
        //         'id' => $t->id,
        //         'quote' => $t->comment,
        //         'author' => $t->client ? $t->client->name : 'Anonymous',
        //         'role' => $t->title ?? 'VIP Guest',
        //         'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($t->client ? $t->client->name : 'V I P') . '&background=random',
        //         'rating' => $t->rating
        //     ];
        // });

        // $testimonials->setCollection($transformed);

        return response()->json($testimonials);
        // return response()->json([]);
    }
}
