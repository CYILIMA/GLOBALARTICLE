<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    public function index()
    {
        $ads       = Advertisement::latest()->paginate(20);
        $positions = Advertisement::positionLabels();
        return view('admin.advertisements.index', compact('ads', 'positions'));
    }

    public function create()
    {
        $positions    = Advertisement::positionLabels();
        $displayTypes = Advertisement::displayTypeLabels();
        $statuses     = Advertisement::statusLabels();
        return view('admin.advertisements.create', compact('positions', 'displayTypes', 'statuses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(creating: true));

        if ($error = $this->checkImageDimensions($request)) {
            return back()->withInput()->withErrors(['banner_image' => $error]);
        }

        $data['banner_image'] = $request->file('banner_image')
            ->store('advertisements', 'public');

        Advertisement::create($data);

        return redirect()->route('admin.advertisements.index')
            ->with('success', 'Advertisement created successfully.');
    }

    public function edit(Advertisement $advertisement)
    {
        $positions    = Advertisement::positionLabels();
        $displayTypes = Advertisement::displayTypeLabels();
        $statuses     = Advertisement::statusLabels();
        return view('admin.advertisements.edit', compact('advertisement', 'positions', 'displayTypes', 'statuses'));
    }

    public function update(Request $request, Advertisement $advertisement)
    {
        $data = $request->validate($this->rules(creating: false));

        if ($request->hasFile('banner_image')) {
            if ($error = $this->checkImageDimensions($request)) {
                return back()->withInput()->withErrors(['banner_image' => $error]);
            }
            Storage::disk('public')->delete($advertisement->banner_image);
            $data['banner_image'] = $request->file('banner_image')
                ->store('advertisements', 'public');
        } else {
            unset($data['banner_image']);
        }

        $advertisement->update($data);

        return redirect()->route('admin.advertisements.index')
            ->with('success', 'Advertisement updated successfully.');
    }

    public function destroy(Advertisement $advertisement)
    {
        Storage::disk('public')->delete($advertisement->banner_image);
        $advertisement->delete();

        return redirect()->route('admin.advertisements.index')
            ->with('success', 'Advertisement deleted.');
    }

    public function click(Advertisement $advertisement)
    {
        if ($advertisement->target_url) {
            $advertisement->increment('clicks');
            return redirect()->away($advertisement->target_url);
        }

        return redirect()->back();
    }

    public function impression(Advertisement $advertisement)
    {
        $advertisement->increment('impressions');
        return response()->json(['ok' => true]);
    }

    // ── Private helpers ────────────────────────────────────────────────────────

    private function rules(bool $creating): array
    {
        $positions    = implode(',', array_keys(Advertisement::positionLabels()));
        $displayTypes = implode(',', array_keys(Advertisement::displayTypeLabels()));
        $statuses     = implode(',', array_keys(Advertisement::statusLabels()));

        return [
            'advertiser_name' => 'required|string|max:255',
            'banner_title'    => 'required|string|max:255',
            'banner_image'    => ($creating ? 'required' : 'nullable') . '|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'target_url'      => 'nullable|url|max:500',
            'position'        => "required|in:{$positions}",
            'display_type'    => "required|in:{$displayTypes}",
            'price'           => 'nullable|numeric|min:0',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after_or_equal:start_date',
            'status'          => "required|in:{$statuses}",
        ];
    }

    /**
     * Landscape positions (leaderboard, in-article, footer) must not receive
     * portrait images — they fill the zone with object-fit: cover and a portrait
     * image would show only a sliver of the content.
     */
    private function checkImageDimensions(Request $request): ?string
    {
        if (!$request->hasFile('banner_image')) return null;

        $file = $request->file('banner_image');
        if (!$file->isValid()) return null;

        [$width, $height] = getimagesize($file->path());
        if (!$width || !$height) return null;

        $landscapeRequired = [
            'header_leaderboard' => '1200×250, 970×250, or 728×90',
            'in_article'         => '728×90 or 970×250',
            'footer_banner'      => '970×90 or 970×250',
        ];

        $position = $request->input('position');

        if (isset($landscapeRequired[$position]) && $height > $width) {
            return "This position requires a landscape image (wider than tall). "
                 . "Recommended: {$landscapeRequired[$position]}.";
        }

        if ($width < 100 || $height < 20) {
            return 'Image is too small. Minimum size is 100×20 px.';
        }

        return null;
    }
}
