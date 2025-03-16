<?php

namespace App\Repo\Admin\ApplicationConfig;

use App\Models\ApplicationConfig;
use HydraStorage\HydraStorage\Traits\HydraMedia;
use Illuminate\Http\Request;
use UnexpectedValueException;

class ApplicationConfigUploadRepo
{
    use HydraMedia;

    private array $validUploadProperties = [
        'logo',
        'water_mark',
        'intro_a',
        'outro_a',
        'intro_b',
        'outro_b',
    ];

    public function upload(Request $request): ApplicationConfig
    {
        $app = ApplicationConfig::firstOrFail();

        foreach ($this->validUploadProperties as $property) {
            if ($request->hasFile($property)) {
                $this->handleFileUpload($app, $request, $property);
            }
        }

        $app->fill($request->only('title','daily_subscriptions_target','daily_traffic_target','monthly_subscriptions_target'));
        $app->save();

        return $app;
    }

    private function handleFileUpload(ApplicationConfig $app, Request $request, string $property): void
    {
        $oldPath = $app->getRawOriginal($property);
        $this->removeMedia("public/config/{$oldPath}");
        $mediaResult = $this->storeMedia($request->file($property), 'config');
        if (!is_string($mediaResult)) {
            throw new UnexpectedValueException('Expected a string path but got an array.');
        }
        $app->{$property} = $mediaResult;
    }
}
