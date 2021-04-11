<?php

namespace App\Http\Controllers\Web;

use App\Mail\FeedbackEmail;
use App\Models\Feedback;
use App\Models\Materials\About\Advantage;
use App\Models\Materials\About\Advantage2;
use App\Models\Materials\Faq;
use App\Models\Materials\Home\HowToUse;
use App\Models\Materials\Placement\Advantage3;
use App\Models\Materials\Placement\Condition;
use App\Models\Materials\Placement\HowToPlace;
use App\Models\Materials\Review;
use App\Models\Product;
use App\Models\Products\ProductCategory;
use App\Models\Site\Setting;
use App\Models\Users\Shop;
use App\Models\Users\ShopProduct;
use App\Models\UwtModel;
use App\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class Controller extends \App\Http\Controllers\Controller
{
    public function index()
    {
        return view('web.pages.home', [
            'howToUse' => HowToUse::all(),
            'review' => Review::query()->limit(4)->get(),
        ]);
    }

    public function about()
    {
        return view('web.pages.about', [
            'advantage' => Advantage::all(),
            'advantage2' => Advantage2::all(),
            'review' => Review::all()
        ]);
    }

    public function faq()
    {
        return view('web.pages.faq', [
            'faq' => Faq::all(),
        ]);
    }

    public function offer()
    {
        return view('web.pages.offer', [
            'advantage' => Advantage3::all(),
            'condition' => Condition::all(),
            'howToPlace' => HowToPlace::all()
        ]);
    }

    public function politics()
    {
        $content = Setting::query()->first();
        $content = $content ? $content->politics : '';
        return view('web.pages.politics', [
            'content' => $content
        ]);
    }

    public function map(Request $request)
    {
        $city = 'Санкт-Петербург';
        $shops = Shop::query();
//            ->where('city', '=', $city);
        if ($search = $request->get('search')) {
            $shops = $shops->whereHas('net', function ($query) use ($search) {
                $query->whereRaw('LOWER(title) like ?', '%'.strtolower($search).'%');
            })->get();
        } else {
            $shops = $shops->get();
        }
//        return response()->json($shops);
        if (!($n = count($shops)) && $search) {
            $shops = Shop::query()
//                ->where('city', '=', $city)
                ->get();
        }
        return view('web.pages.map', [
            'city' => $city,
            'shops' => $shops,
            'search' => $search,
            'count' => $n,
        ]);
    }


    public function shop(Request $request, $id)
    {
        /** @var Shop $shop */
        $shop = Shop::find($id);
        $products = $shop->products()
            ->where('active', 1)
            ->where(function($query) {
                $query->where('over_date', '>', time())
                    ->orWhereNull('over_date');
            })
            ->whereHas('product', function ($query) {
                $query->where('active', 1);
            })
            ->get();
        $category = ProductCategory::query()
            ->whereHas('products.products', function ($query) use ($products) {
                $query->whereIn('id', $products->pluck('id'));
            })->get();

        if ($activeCategory = $request->get('category')) {
            $products = $shop->products()
                ->where('active', 1)
                ->where(function($query) {
                    $query->where('over_date', '>', time())
                        ->orWhereNull('over_date');
                })
                ->whereHas('product', function ($query) use ($activeCategory) {
                    $query->where('product_category_id', $activeCategory)
                        ->where('active', 1);
                })
                ->paginate(12,['*'],'page');
        } else {
            $products = $shop->products()
                ->where('active', 1)
                ->where(function($query) {
                    $query->where('over_date', '>', time())
                        ->orWhereNull('over_date');
                })
                ->whereHas('product', function ($query) {
                    $query->where('active', 1);
                })
                ->paginate(12,['*'],'page');
//            return response()->json($products);
        }
        return view('web.pages.shop', [
            'shop' => $shop,
            'category' => $category,
            'products' => $products,
            'activeCategory' => $activeCategory,
            'lastPage' => $products->currentPage()
        ]);
    }

    public function product($id)
    {
        /** @var ShopProduct $product */
        $product = ShopProduct::findOrFail($id);
        $products = $product->shop->products()
            ->where('active', 1)
            ->where('id', '!=', $id)
            ->where(function($query) {
                $query->where('over_date', '>', time())
                    ->orWhereNull('over_date');
            })
            ->whereHas('product', function ($query) {
                $query->where('active', 1);
            })->limit(4)->get();

        return view('web.pages.product', [
            'product' => $product,
            'products' => $products,
        ]);
    }

    public function barCode($id)
    {
        $product = ShopProduct::findOrFail($id);
        return view('web.pages.barcode', [
            'product' => $product,
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return \redirect('');
    }

    public function feedback()
    {
        return view('web.pages.feedback', [
            'model' => Feedback::getInstance(),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function feedbackPost(Request $request)
    {
        $model = new Feedback();
        $requestData = $this->doValidate($request, $model);
        $model->setAttr($requestData);
        Mail::to('support@half-price.app')->send(new FeedbackEmail($model));
        if (!Mail::failures()) {
            return \redirect('feedback')->with('toast', ['type' => 'success', 'message' => 'Благодарим вас за заявку. Мы свяжемся с вами в ближайшее время']);
        }
        return \redirect('feedback')->with('toast', ['type' => 'error', 'message' => 'Сожалеем, но мы не смогли отправить сообщение. Попробуйте еще раз']);
    }

    /**
     * @param Request $request
     * @param UwtModel $model
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function doValidate(Request $request, UwtModel $model)
    {
        foreach ($model->generateAttributes() as $key => $field) {
            if (!$request->get($key)) {
                $data = [];
                if (isset($field['params'])) {
                    foreach ($field['params'] as $param) {
                        $data[$param] = $request->get($param);
                    }
                }
                if (isset($field['isOnlyCreate']) && $field['isOnlyCreate']) {
                    if (!$model->id) {
                        $request->merge([$key => $field['function']($data)]);
                    }
                } else {
                    $request->merge([$key => $field['function']($data)]);
                }
            }
        }
        parent::validate($request, $model->rules(), $model->errorMessages(), $model->getLabels());
        return $request->all();
    }
}
