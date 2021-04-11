@foreach($products as $product)
    @if ($product->isActive())
        <div class="{{isset($class) ? $class : ''}}">
            <a href="/product/{{$product->id}}" class="product-item">
                <div class="product-header">
                    <div class="sale-info">
                        <div class="sale-value">-{{$product->shopSale->sale}}%</div>
                        <div class="sale-date">До {{$product->getDate()}}</div>
                        <div class="net">
                            <span class="border left"></span>
                            <div class="net-logo">
                                <img src="{{\App\Models\Developer\CropImage::getCropImageUrl($product->shop->net, 'logo')}}"
                                     alt="">
                            </div>
                            <span class="border right"></span>
                        </div>
                    </div>
                    <div class="product-logo">
                        <img src="{{\App\Models\Developer\CropImage::getCropImageUrl($product->product, 'logo')}}"
                             alt="">
                    </div>
                </div>
                <div class="product-content">
                    <div class="product-description">
                        <span class="product-title">{{$product->product->title}}:</span>
                        {{\App\Helpers\CommonHelper::cropString($product->product->description, 50)}}
                    </div>
                    @php
                        $price = $product->price * (1 - $product->shopSale->sale / 100);

                    @endphp
                    <div class="volume-price">
                        {{$product->product->measure_value}} {{$product->product->measure->short_title}}
                        /{{number_format((1 / $product->product->measure_value) * $price)}}
                        ₽ за {{$product->product->measure->short_title}}.
                    </div>
                </div>
                <div class="product-footer">
                    <div class="price">{{number_format($price, 2, ',', ' ')}} ₽</div>
                    <div class="old-price">{{number_format($product->price, 2, ',', ' ')}}
                        ₽
                    </div>
                </div>
            </a>
        </div>
    @endif
@endforeach
