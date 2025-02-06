<?php

namespace App\Repo\Admin\SubMogouRepo;

use App\Models\Mogou;
use App\Models\SubMogou;
use HydraStorage\HydraStorage\Traits\HydraMedia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubMogouDeleteRepo
{
    use HydraMedia;

    protected string $image_folder_path = "";

    public function __construct(
        protected Mogou $mogou,
        protected SubMogou $subMogou
    ) {
        $this->initProps();
    }

    public function delete(): bool
    {
        DB::beginTransaction();
        try {
            $this->removeDbImageRows();
            $this->removeFolder();
            $this->subMogou->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete SubMogou", ['exception' => $e->getMessage()]);
            return false;
        }
    }

    protected function initProps(): void
    {
        $this->image_folder_path = "public/mogou/{$this->mogou->id}/{$this->subMogou->id}";
    }

    protected function removeDbImageRows(): void
    {
        $this->subMogou->images($this->mogou->rotation_key)->delete();
    }

    protected function removeFolder(): void
    {
        $success = $this->dropDirectory($this->image_folder_path);
        if (!$success) {
            Log::channel("slack")->error("Folder not deleted", ['folder' => $this->image_folder_path]);
        }
        Log::channel("storage")->info("Deleting folder", ['folder' => $this->image_folder_path]);

    }
}
