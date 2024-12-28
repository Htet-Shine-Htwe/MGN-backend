<?php
namespace App\Services\Subscription;


use App\Models\SubMogou;

class SubMogouSubscription
{
    public function __construct(protected SubMogou $subMogou)
    {

    }


    /**
     * @param array<int> $id
     */
    public function appendSubscriptionId(int|array $id) :void
    {
        $ids = $this->subMogou->subscription_collection;

        if(is_array($id)) {
            $ids = array_merge($ids, $id);
        }else{
            $ids[] = $id;
        }

        $this->subMogou->update(
            [
            'subscription_collection' => json_encode($ids)
            ]
        );

        $this->subMogou->refresh();

    }

    /**
     * @param array<int> $id
     */
    public function removeSubscriptionId(int|array $id) :void
    {
        $ids = $this->subMogou->subscription_collection;


        is_array($id) ? $ids = array_diff($ids, $id) : $ids = array_filter($ids, fn($i) => $i != $id);
        $ids = array_values($ids);

        $this->subMogou->update(
            [
            'subscription_collection' => json_encode($ids)
            ]
        );

        $this->subMogou->refresh();

    }

}

