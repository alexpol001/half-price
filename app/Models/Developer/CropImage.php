<?php

namespace App\Models\Developer;

use App\Helpers\CommonHelper;
use App\Models\UwtModel;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;

/**
 * App\Models\Admin\AdminMenu
 *
 * @property int $id
 * @property int $x
 * @property int $y
 * @property int $width
 * @property int $height
 * @property int $rotate
 * @property string $extension
 */
class CropImage extends UwtModel
{
    protected $fillable = [
        'x', 'y', 'width', 'height', 'rotate', 'extension'
    ];


    /**
     * @param UwtModel $model
     * @param string $slug
     * @param array $attributes
     */
    public static function saveCropImage($model, $slug, $attributes)
    {
        $fields = $model->getFields()[$slug];
        $size = $fields['size'];
        $bg = isset($fields['bg']) ? $fields['bg'] : null;
        $cropSlug = $slug . '_crop';
        $crop = isset($attributes[$cropSlug]) ? $attributes[$cropSlug] : [];
        $crop['x'] = isset($crop['x']) ? $crop['x'] : null;
        $crop['y'] = isset($crop['y']) ? $crop['y'] : null;
        $crop['width'] = isset($crop['width']) ? $crop['width'] : null;
        $crop['height'] = isset($crop['height']) ? $crop['height'] : null;
        $crop['rotate'] = isset($crop['rotate']) ? $crop['rotate'] : null;
        $delete = ($crop['x'] === null || $crop['y'] === null || $crop['width'] === null || $crop['height'] === null || $crop['rotate'] === null);

        $dir = $model->resource_url . '/' . $slug;
        if (!$delete) {
            $crop = array_map(function ($a) {
                return (int)$a;
            }, $crop);
            /** @var UploadedFile $file */
            if (isset($attributes[$slug]) && $file = $attributes[$slug]) {
                CommonHelper::rmRec(public_path($dir));
                $originalName = 'original.' . $file->getClientOriginalExtension();
                if ($file->storeAs($dir, $originalName, 'public')) {
                    $originalPath = public_path($dir . '/' . $originalName);
                    $img = Image::make($originalPath);
                    $cropPath = public_path($dir . '/' . str_replace('original', 'crop', $originalName));
                    static::cropImage($img, $crop, $size, $cropPath, $bg);

//                    $img = Image::make($originalPath);
//                    if (($optWidth = $img->getWidth()) > 400) {
//                        $optK = $img->getWidth() / $size['width'];
//                        if ($optK > 1) {
//                            $optWidth = (int)($img->getWidth() / $optK);
//                            $optWidth = $optWidth < 400 ? 400 : $optWidth;
//                            $optK = $img->getWidth() / $optWidth;
//                            $optHeight = (int)($img->getHeight() / $optK);
//                            $img->resize($optWidth, $optHeight);
//                            $img->save();
//                            $crop['x'] = (int) ($crop['x'] / $optK);
//                            $crop['y'] = (int) ($crop['y'] / $optK);
//                            $crop['width'] = (int) ($crop['width'] / $optK);
//                            $crop['height'] = (int) ($crop['height'] / $optK);
//                        }
//                    }
                    $crop['extension'] = $file->getClientOriginalExtension();
                    if ($model->$slug) {
                        $model->$slug->update($crop);
                    } else {
                        $crop = CropImage::getInstance()->store($crop);
                        $model->$slug()->associate($crop);
                        $model->save();
                    }
                }
            } else {
                if ($model->$slug) {
                    $extension = $model->$slug->extension;
                    $originalName = 'original.' . $extension;
                    $originalPath = public_path($dir . '/' . $originalName);
                    $img = Image::make($originalPath);
                    $cropPath = public_path($dir . '/' . str_replace('original', 'crop', $originalName));
                    $size = $model->getFields()[$slug]['size'];
                    static::cropImage($img, $crop, $size, $cropPath, $bg);
                    $model->$slug->update($crop);
                }
            }
        } else {
            CommonHelper::rmRec(public_path($dir));
            if ($model->$slug) {
                $model->$slug->delete();
            }
        }
    }

    /**
     * @param UwtModel $model
     * @param string $slug
     * @param bool $original
     * @return bool|string
     */
    public static function getCropImageUrl($model, $slug, $original = false)
    {
        if ($cropImage = $model->$slug) {
            return "$model->resource_url/$slug/" . ($original ? 'original.' : 'crop.') . $model->$slug->extension;
        }
        return null;
    }

    public static function deleteCropImage($model, $slug) {
        if ($model->$slug) {
            $model->$slug->delete();
        }
    }

    /**
     * @param \Intervention\Image\Image $img
     * @param array $crop
     * @param array $size
     * @param string $cropPath
     */
    protected static function cropImage($img, $crop, $size, $cropPath, $bg) {
        if ($crop['rotate']) {
            $img->rotate((0 - $crop['rotate']));
        }
        $k = $crop['width'] / $size['width'];
        $x = 0 - $crop['x'];
        $y = 0 - $crop['y'];
        if ($k > 0) {
            $width = $img->width() / $k;
            $height = $img->height() / $k;
            $x = (int) ($x / $k);
            $y = (int) ($y / $k);
            $width = $width < 1 ? 1: $width;
            $height = $height < 1 ? 1 : $height;
            if ($width > 4096 || $height > 4096) {
                return;
            }
            $img->resize($width, $height);
        }
        $finalImg = Image::canvas($size['width'], $size['height']);
        if ($bg) {
            $finalImg->fill($bg);
        }
        $finalImg->insert($img, 'top-left', $x, $y);
        $finalImg->save($cropPath);
//        $crop['x'] = 0 - $x;
//        $crop['y'] = 0 - $y;
//        $crop['width'] =
    }

    public function getAccess()
    {
        return [
            'index' => [1],
            'create' => [1],
            'update' => [1],
        ];
    }
}
