<?php

namespace App\Http\Controllers\Account;

use App\DTO\Offer\OfferData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\OfferRequest;
use App\Models\Glossary\Category;
use App\Models\Glossary\OfferType;
use App\Models\Offer;
use App\Services\OfferService;
use Auth;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use View;

class OfferController extends Controller
{
    public function list()
    {
        return view('account.offers.list')->with('user', Auth::user());
    }

    public function create()
    {
        return view('account.offers.create', $this->form());
    }

    public function edit(Offer $offer)
    {
        $user = Auth::user();
        if ($offer->user_id != $user->id) {
            return abort(404);
        }

        return View::make('account.offers.edit', $this->form($offer));
    }

    /**
     * @throws UnknownProperties
     */
    public function store(OfferRequest $request)
    {
        $offerType = OfferType::whereId($request->get('offer_type_id'))->first();

        OfferService::store($offerType, Auth::user(), OfferData::fromRequest($request));

        return back()->with('success', __("app.saved"));
    }

    private function form(Offer $offer = null): array
    {
        $offerType = OfferType::first();
        $categories = Category::orderBy('order')->get();

        $links = [];
        foreach ($categories as $category) {
            if ($category->parent_id) {
                $links[$category->parent_id][] = $category->id;
            }
        }

        return [
            'offer'      => $offer,
            'offerType'  => $offerType,
            'user'       => Auth::user(),
            'categories' => categoriesTree($categories, $links, null),
            'links'      => $links
        ];
    }
}
