<?php

namespace App\Domain\Store\Actions;

use App\Domain\Store\DTOs\StoreData;
use App\Domain\Store\Events\StoreCreated;
use App\Domain\Store\Models\Store;
use App\Domain\Store\Enums\StoreStatus;
use App\Domain\Store\Repositories\StoreRepository;
use App\Domain\User\Models\User;
use DB;

class CreateStore
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private StoreRepository $stores,
    ) {

    }

    public function execute(User $owner, StoreData $data): Store
    {
        return DB::transaction(function () use ($data) {
            $store = $this->stores->create([
                'owner_user_id' => $data->ownerUserId,
                'name' => $data->name,
                'email' => $data->email,
                'phone' => $data->phone,
                'banner_url' => $data->banner_url,
                'logo_url' => $data->logo_url,
                'description' => $data->description,
                'status' => StoreStatus::PENDING
            ]);

            $docs = [
                'commercial_register' => $data->commercial_register,
                'tax_card' => $data->tax_card,
                'id_card' => $data->id_card,
            ];

            foreach ($docs as $type => $path) {
                if ($path) {
                    $store->verifications()->create([
                        'store_id' => $store->id,
                        'document_type' => $type,
                        'document_path' => $path,
                        'status' => 'pending',
                    ]);
                }
            }

            $store->userRoles()->create([
                'user_id' => $data->ownerUserId,
                'role' => 'owner',
                'store_id' => $store->id,
            ]);

            $store->createDefaultStoreHours($store);

            event(new StoreCreated($store));
            return $store;
        });
    }
}
