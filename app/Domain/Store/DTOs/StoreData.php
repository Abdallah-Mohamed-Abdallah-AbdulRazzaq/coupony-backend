<?php

namespace App\Domain\Store\DTOs;

use App\Application\Http\Requests\createStoreRequest;

class StoreData
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly string $ownerUserId,
        public readonly ?string $subscriptionTier = null,
        public readonly ?string $status = null,
        public readonly ?string $phone = null,
        public readonly ?string $email = null,
        public readonly ?string $tax_id = null,
        public readonly ?string $logo_url = null,
        public readonly ?string $banner_url = null,
        public readonly ?string $commercial_register = null,
        public readonly ?string $tax_card = null,
        public readonly ?string $id_card = null,

    ) {
    }

    public static function fromRequest(createStoreRequest $request): self
    {
        return new self(
            name: $request->input('name'),
            description: $request->input('description', ''),
            ownerUserId: $request->user()->id,
            subscriptionTier: $request->input('subscription_tier'),
            status: $request->input('status', 'pending'),
            phone: $request->input('phone'),
            email: $request->user()->email,
            tax_id: $request->input('tax_id'),
            logo_url: $request->file('logo_url') ? $request->file('logo_url')->store('stores/logos', 'public') : null,
            banner_url: $request->file('banner_url') ? $request->file('banner_url')->store('stores/banners', 'public') : null,
            commercial_register: $request->file('verification_docs.commercial_register') ? $request->file('verification_docs.commercial_register')->store('stores/verifications', 'public') : null,
            tax_card: $request->file('verification_docs.tax_card') ? $request->file('verification_docs.tax_card')->store('stores/verifications', 'public') : null,
            id_card: $request->file('verification_docs.id_card') ? $request->file('verification_docs.id_card')->store('stores/verifications', 'public') : null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'owner_user_id' => $this->ownerUserId,
            'subscription_tier' => $this->subscriptionTier,
            'status' => $this->status,
            'phone' => $this->phone,
            'email' => $this->email,
            'tax_id' => $this->tax_id,
            'logo_url' => $this->logo_url,
            'banner_url' => $this->banner_url,
            'commercial_register' => $this->commercial_register,
            'tax_card' => $this->tax_card,
            'id_card' => $this->id_card,
        ];
    }
}
