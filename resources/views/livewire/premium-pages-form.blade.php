<div>
    <x-form-section submit="submit" enctype="multipart/form-data" >
        <x-slot name="form">
            <div class="col-span-2">
                <x-jet-label for="connection_id" value="{{ __('Connection') }}" />
                <x-select name="connection_id" class="mt-1" wire:model.defer="premiumpage.connection_id">
                    <option value=""></option>
                    @foreach($this->connections as $connection)
                        <option value="{{ $connection->id }}" {{ $this->premiumpage->connection_id == $connection->id ? 'selected' : '' }}>
                            {{ $connection->name . ' ' .  $connection->base_url }}
                        </option>
                    @endforeach
                </x-select>
                <x-jet-input-error for="premiumpage.connection_id" class="mt-2" />
            </div>

            <div class="col-span-4">
                <x-jet-label for="slug" value="{{ __('Slug') }}" />
                <x-jet-input id="slug" name="slug" type="text" class="mt-1 block w-full" wire:model.defer="premiumpage.slug" />
                <x-jet-input-error for="premiumpage.slug" class="mt-2" />
            </div>
            <div class="col-span-6">
                <x-jet-label for="premium_templates_id" value="{{ __('Premium Template') }}" />
                <x-select name="premium_templates_id" class="mt-1" wire:model.defer="premiumpage.premium_templates_id">
                    <option value=""></option>
                    @foreach($this->PremiumTemplates as $template)
                        <option value="{{ $template->id }}" {{ $this->premiumpage->premium_templates_id == $template->id ? 'selected' : '' }}>
                            {{ $template->name }}
                        </option>
                    @endforeach
                </x-select>
                <x-jet-input-error for="premiumpage.premium_templates_id" class="mt-2" />
            </div>

            <div class="col-span-3">
                <x-jet-label for="product_name" value="{{ __('Product Name') }}" />
                <x-jet-input id="product_name" name="product_name" type="text" class="mt-1 block w-full" wire:model.defer="premiumpage.product_name" />
                <x-jet-input-error for="premiumpage.product_name" class="mt-2" />
            </div>

            <div class="col-span-3">
                <x-jet-label for="affiliate_link" value="{{ __('Affiliate Link') }}" />
                <x-jet-input id="affiliate_link" name="affiliate_link" type="text" class="mt-1 block w-full" wire:model.defer="premiumpage.affiliate_link" />
                <x-jet-input-error for="premiumpage.affiliate_link" class="mt-2" />
            </div>
            <div class="col-span-3">
                <x-jet-label for="header_text" value="{{ __('Header Text') }}" />
                <x-jet-input id="header_text" name="header_text" type="text" class="mt-1 block w-full" wire:model.defer="premiumpage.header_text" />
                <x-jet-input-error for="premiumpage.header_text" class="mt-2" />
            </div>
            
            <div class="col-span-2">
                <x-jet-label for="hero_image" value="{{ __('Hero Image') }}" />
                <x-jet-input id="hero_image" name="hero_image" type="file" class="mt-1 block w-full" wire:model.defer="file" />
                @if(isset($this->premiumpage->hero_image))
                        <img style="max-width: 100px" src="{{ asset('/storage').'/'.$this->premiumpage->hero_image}}" alt="">
                 @endif
                <x-jet-input-error for="premiumpage.hero_image" class="mt-2" />
            </div>
            <div class="col-span-3">
                <x-jet-label for="hero_text1" value="{{ __('Hero Text 1') }}" />
                <x-jet-input id="hero_text1" type="text" class="mt-1 block w-full" wire:model.defer="premiumpage.hero_text1" />
                <x-jet-input-error for="premiumpage.hero_text1" class="mt-2" />
            </div>
            <div class="col-span-3">
                <x-jet-label for="hero_text2" value="{{ __('Hero Text 2') }}" />
                <x-jet-input id="hero_text2" type="text" class="mt-1 block w-full" wire:model.defer="premiumpage.hero_text2" />
                <x-jet-input-error for="premiumpage.hero_text2" class="mt-2" />
            </div>
            <!-- <div class="col-span-2">
                <x-jet-label for="button_color" value="{{ __('Button Color') }}" />
                <x-jet-input id="button_color" name="button_color" type="color" class="mt-1 block w-full h-10" wire:model.defer="premiumpage.button_color" />
                <x-jet-input-error for="premiumpage.button_color" class="mt-2" />
            </div> -->
            <div class="col-span-6">
                <x-jet-label for="product_header" value="{{ __('Product Header') }}" />
                <x-jet-input id="product_header" type="text" class="mt-1 block w-full" wire:model.defer="premiumpage.product_header" />
                <x-jet-input-error for="premiumpage.product_header" class="mt-2" />
            </div>
            <div class="col-span-3">
                <x-jet-label for="product_image1" value="{{ __('Product Image 1') }}" />
                <x-jet-input id="product_image1" type="file" class="mt-1 block w-full" wire:model="file1" />
                @if(isset($this->premiumpage->product_image1))
                        <img style="max-width: 100px" src="{{ asset('/storage').'/'.$this->premiumpage->product_image1}}" alt="">
                 @endif
                <x-jet-input-error for="premiumpage.product_image1" class="mt-2" />
            </div>
            <div class="col-span-2">
                <x-jet-label for="product_image1_link" value="{{ __('Image Affiliate Link') }}" />
                <x-jet-input id="product_image1_link" type="text" class="mt-1 block w-full" wire:model.defer="premiumpage.product_image1_link" />
                <x-jet-input-error for="premiumpage.product_image1_link" class="mt-2" />
            </div>  
            <div class="col-span-3">
                <x-jet-label for="product_image2" value="{{ __('Product Image 2') }}" />
                <x-jet-input id="product_image2" type="file" class="mt-1 block w-full" wire:model="file2" />
                @if(isset($this->premiumpage->product_image2))
                        <img style="max-width: 100px" src="{{ asset('/storage').'/'.$this->premiumpage->product_image2}}" alt="">
                 @endif
                <x-jet-input-error for="premiumpage.product_image2" class="mt-2" />
            </div>
            <div class="col-span-2">
                <x-jet-label for="product_image2_link" value="{{ __('Image Affiliate Link') }}" />
                <x-jet-input id="product_image2_link" type="text" class="mt-1 block w-full" wire:model.defer="premiumpage.product_image2_link" />
                <x-jet-input-error for="premiumpage.product_image2_link" class="mt-2" />
            </div>
            <div class="col-span-3">
                <x-jet-label for="product_image3" value="{{ __('Product Image 3') }}" />
                <x-jet-input id="product_image3" type="file" class="mt-1 block w-full" wire:model="file3" />
                @if(isset($this->premiumpage->product_image3))
                        <img style="max-width: 100px" src="{{ asset('/storage').'/'.$this->premiumpage->product_image3}}" alt="">
                 @endif
                <x-jet-input-error for="premiumpage.product_image3" class="mt-2" />
            </div>
            <div class="col-span-2">
                <x-jet-label for="product_image3_link" value="{{ __('Image Affiliate Link') }}" />
                <x-jet-input id="product_image3_link" type="text" class="mt-1 block w-full" wire:model.defer="premiumpage.product_image3_link" />
                <x-jet-input-error for="premiumpage.product_image3_link" class="mt-2" />
            </div>

        </x-slot>

        <x-slot name="actions">
            <x-jet-button>
                {{ __(!$this->premiumpage->exists ? 'Create' : 'Update') }}
            </x-jet-button>
        </x-slot>
    </x-form-section>
</div>
