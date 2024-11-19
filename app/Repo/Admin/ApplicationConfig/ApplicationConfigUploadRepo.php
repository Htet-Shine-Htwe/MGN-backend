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
                $app->$property = $this->storeMedia($request->file($property), 'config');
            }

        }
        $app->save();

        return $app;
    }
}
