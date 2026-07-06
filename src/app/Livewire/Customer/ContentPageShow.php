<?php

namespace App\Livewire\Customer;

use App\Models\ContentPage;
use Livewire\Component;

class ContentPageShow extends Component
{
    public string $slug = '';

    public function mount(string $slug): void
    {
        $this->slug = $slug;
    }

    public function render()
    {
        $page = ContentPage::getPublished($this->slug);

        $siteSetting = class_exists(\App\Models\SiteSetting::class)
            ? \App\Models\SiteSetting::current()
            : null;

        return view('livewire.customer.content-page-show', [
            'page' => $page,
            'siteSetting' => $siteSetting,
        ]);
    }
}
