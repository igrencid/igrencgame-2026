<?php

namespace App\Livewire\Customer;

use App\Models\FaqItem;
use Livewire\Component;
use Livewire\WithPagination;

class FaqPage extends Component
{
    use WithPagination;

    #[\Livewire\Attributes\Url]
    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $faqs = FaqItem::active()
            ->search($this->search)
            ->orderBy('sort_order', 'asc')
            ->get();

        $siteSetting = class_exists(\App\Models\SiteSetting::class)
            ? \App\Models\SiteSetting::current()
            : null;

        return view('livewire.customer.faq-page', [
            'faqs' => $faqs,
            'siteSetting' => $siteSetting,
        ]);
    }
}
