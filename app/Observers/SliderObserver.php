<?php

namespace App\Observers;

use App\Helpers\ImageHelper;
use App\Models\Slider;

final class SliderObserver
{
    public function created(Slider $slider): void
    {
        $slider::flushQueryCacheWithTag($slider::MODEL_NAME);
    }

    public function updated(Slider $slider): void
    {
        $slider::flushQueryCacheWithTag($slider::MODEL_NAME);
    }

    public function deleted(Slider $slider): void
    {
        $slider::flushQueryCacheWithTag($slider::MODEL_NAME);

        $images = $slider->getTranslations('image');
        foreach ($images as $lang => $image) {
            ImageHelper::deleteImageFromCDN($slider::getImagePath($lang), $image);
        }
    }
}
