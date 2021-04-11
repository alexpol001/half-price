<?php
/**
 * @var \App\Models\UwtModel $model
 */
$round = isset($round) ? $round : false;
$edge = isset($edge) ? $edge : false;
$required = $model->hasRule($slug, 'required');
$cropImage = \App\Models\Developer\CropImage::getCropImageUrl($model, $slug, true);
?>
<div id="form-image-{{$slug}}" class="form-group form-image{{$round ? ' round' : ''}} @error($slug) is-invalid @enderror" >
    <label for="{{$slug}}">{{ __($model->getLabel($slug)) }}@if($required)<span class="required-star">*</span>@endif</label>
    <div class="input-crop-group">
        <div class="image-crop clearfix">
            <div class="image-crop-upload">
                <img src="" class="image-preview image-preview-{{$slug}}">
            </div>
            @php
                if (($width = $size['width']) > 200) {
                    $width = 200;
                }
                $height = $size['height'] / ($size['width'] / $width);
            @endphp
            <div class="image-crop-preview-block">
                <div class="image-crop-preview-label pb-3">
                    Результат
                </div>
                <div class="image-crop-preview-wrap">
                    <div class="image-crop-preview image-preview-{{$slug}}-crop"
                         style="width: {{$width}}px; height: {{$height}}px;">
                    </div>
                </div>
            </div>
        </div>
        <input style="display: none" id="{{$slug}}" type="file" accept="image/jpeg,image/png,image/gif"
               class=""
               name="{{$slug}}">
        <input type="hidden" name="{{$slug}}_crop[x]">
        <input type="hidden" name="{{$slug}}_crop[y]">
        <input type="hidden" name="{{$slug}}_crop[width]">
        <input type="hidden" name="{{$slug}}_crop[height]">
        <input type="hidden" name="{{$slug}}_crop[rotate]">
        <div class="btn-group">
            <button class="image-select-file btn btn-primary">Выбрать файл</button>
            <button class="image-undo crop-control btn btn-light" style="display: none"><i class="fas fa-undo"></i>
            </button>
            <button class="image-redo crop-control btn btn-light" style="display: none"><i class="fas fa-redo"></i>
            </button>
            <button class="image-reset crop-control btn bg-orange" style="display: none"><i class="fas fa-times"></i>
            </button>
            <button class="image-destroy crop-control btn btn-danger" style="display: none"><i
                        class="fas fa-trash-alt"></i></button>
        </div>
        @if ($hint ?? false)
            <span class="hint">{{$hint}}</span>
        @endif
        @error($slug)
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
</div>
@section('pos-end')
    @parent
    <script>
        $(document).ready(function () {
            let image;
            let slug = '{{$slug}}';
            let form_image = $('#form-image-{{$slug}}');
            let file_input = $('#' + slug);

            let select = $('.image-select-file');
            let undo = $('.image-undo');
            let redo = $('.image-redo');
            let reset = $('.image-reset');
            let destroy = $('.image-destroy');

            let preview = '.image-preview-' + slug + '-crop';

            let x = $('input[name="{{$slug}}_crop[x]"]');
            let y = $('input[name="{{$slug}}_crop[y]"]');
            let width = $('input[name="{{$slug}}_crop[width]"]');
            let height = $('input[name="{{$slug}}_crop[height]"]');
            let rotate = $('input[name="{{$slug}}_crop[rotate]"]');

            @if ($edge)
            let cropFix = false;
            @endif

            let global_options = {
                viewMode: "{{$edge ? 2 : 0}}",
                checkOrientation: true,
                aspectRatio: "{{$size['width'] / $size['height']}}",
                autoCropArea: 1,
                highlight: false,
                preview: preview,
                crop: function (event) {
                    x.val(event.detail.x);
                    y.val(event.detail.y);
                    width.val(event.detail.width);
                    height.val(event.detail.height);
                    rotate.val(event.detail.rotate);
                    @if ($edge)
                    let cropperWidth = this.cropper.canvasData.naturalWidth;
                    if (!cropFix && (width.val()) > cropperWidth) {
                        cropFix = true;
                        this.cropper.setData({x: 0, width: cropperWidth});
                        this.cropper.moveTo();
                        cropFix = false;
                    }
                    @endif
                }
            };

            @if ($cropImage)
            imageInit("{{$cropImage}}?time={{time()}}", {
                x: Number("{{$model->$slug->x}}"),
                y: Number("{{$model->$slug->y}}"),
                width: Number("{{$model->$slug->width}}"),
                height: Number("{{$model->$slug->height}}"),
                rotate: Number("{{$model->$slug->rotate}}"),
            });
            @endif

            select.on('click', function (e) {
                e.preventDefault();
                file_input.trigger('click');
            });

            file_input.change(function () {
                if (this.files && this.files[0]) {
                    if (this.files[0].type.match('image.jpeg|image.png|image.gif')) {
                        let reader = new FileReader();
                        reader.onload = function (e) {
                            imageInit(this.result);
                        };

                        reader.readAsDataURL(this.files[0]);
                    }
                }
            });

            $('.crop-control').on('click', function (e) {
                e.preventDefault();
                let cropper = image.data('cropper');
                if (cropper) {
                    if ($(this).is(undo)) {
                        cropper.rotate(-90);
                    } else if ($(this).is(redo)) {
                        cropper.rotate(90);
                    } else if ($(this).is(reset)) {
                        cropper.reset();
                    } else if ($(this).is(destroy)) {
                        imageDestroy();
                    }
                }
            });

            function imageDestroy() {
                form_image.removeClass('active');
                if (image && image.data('cropper')) {
                    image.cropper('destroy', true);
                }
                image = $('.image-preview-' + slug);
                image.attr('src', '');
                undo.hide();
                redo.hide();
                reset.hide();
                destroy.hide();
                x.val('');
                y.val('');
                width.val('');
                height.val('');
                rotate.val('');
            }

            function imageInit($url, data = false) {
                imageDestroy();
                image.attr('src', $url);
                form_image.addClass('active');
                let options = Object.assign({}, global_options);
                if (data) {
                    options.data = data;
                }
                if (image.data('cropper')) {
                    image.cropper(options).cropper('reset', true).cropper('replace', $url);
                } else {
                    image.cropper(options);
                    @if (!$edge)
                    if (data) {
                        checkImageZoomSetData(image.data('cropper'), data);
                    }
                    @endif
                }
                undo.show();
                redo.show();
                reset.show();
                destroy.show();
            }

            @if (!$edge)
            function checkImageZoomSetData(cropper, data) {
                setTimeout(function () {
                    let cropperCanvas = cropper.canvasData;
                    if (cropperCanvas) {
                        let x1 = data.x;
                        let x2 = x1 + data.width;
                        x1 = x1 < 0 ? x1 : 0;
                        x2 = x2 > cropperCanvas.naturalWidth ? x2 : cropperCanvas.naturalWidth;
                        let y1 = data.y;
                        let y2 = y1 + data.height;
                        y1 = y1 < 0 ? y1 : 0;
                        y2 = y2 > cropperCanvas.naturalHeight ? y2 : cropperCanvas.naturalHeight;
                        if (x1 < 0 && x2 === cropperCanvas.naturalWidth) {
                            x2 -= x1;
                        }
                        if (y1 < 0 && y2 === cropperCanvas.naturalHeight) {
                            y2 -= y1;
                        }
                        if (x2 > cropperCanvas.naturalWidth && x1 === 0) {
                            x1 -= x2 - cropperCanvas.naturalWidth;
                        }
                        if (y2 > cropperCanvas.naturalHeight && y1 === 0) {
                            y1 -= y2 - cropperCanvas.naturalHeight;
                        }
                        let dX = x2 - x1;
                        let dY = y2 - y1;
                        let zoom = Math.max(dX / cropperCanvas.naturalWidth, dY / cropperCanvas.naturalHeight);
                        if (zoom > 1) {
                            cropper.zoom(1 - zoom).setData(data);
                        }
                    } else {
                        checkImageZoomSetData(cropper, data);
                    }
                }, 100);
            }
            @endif
        });
    </script>
@endsection
