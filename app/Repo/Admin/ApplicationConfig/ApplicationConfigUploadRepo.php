<?php

namespace App\Repo\Admin\ApplicationConfig;

use App\Models\ApplicationConfig;
use HydraStorage\HydraStorage\Traits\HydraMedia;
use Illuminate\Http\Request;

class ApplicationConfigUploadRepo
{
    use HydraMedia;


    public function upload(Request $request): ApplicationConfig
    {
        $validUploadProperties = [
            'logo',
            'water_mark',
            'intro_a',
            'outro_a',
            'intro_b',
            'outro_b',
        ];
        $app = ApplicationConfig::firstOrFail();

        foreach ($validUploadProperties as $property) {
            if ($request->hasFile($property)) {
                $this->removeMedia('public/config/'.$app->getRawOriginal($property));
                $mediaResult = $this->storeMedia($request->file($property), 'config');
                if (is_string($mediaResult)) {
                    $app->$property = $mediaResult;
                } else {
                    throw new \UnexpectedValueException('Expected a string for avatar path but got an array.');
                }
            }

        }
        $app->save();

        return $app;
    }
}
