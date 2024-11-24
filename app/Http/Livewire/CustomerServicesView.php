<?php

namespace App\Http\Livewire;

use App\Models\Service;
use App\Models\Deal;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerServicesView extends Component
{
    use WithPagination;

    public $search;
    public $categories;
    public $categoryFilter = [];
    public $sortByPrice = 'PriceLowToHigh';
    public $selectedDealId;

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => []],
        'sortByPrice' => ['except' => 'PriceLowToHigh'],
    ];

    public function mount()
    {
        // Retrieve all categories to show in filter options
        $this->categories = \App\Models\Category::all();

        // Initialize categoryFilter with all category IDs for "Select All"
        $this->categoryFilter = $this->categories->pluck('id')->toArray();
    }

    public function render()
{
    $query = Service::query();

    // Apply search filters
    if ($this->search) {
        $query->where(function ($subquery) {
            $subquery->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%');
        });
    }

    // Apply category filters
    if (!empty($this->categoryFilter) && !in_array(0, $this->categoryFilter)) {
        $query->whereIn('category_id', $this->categoryFilter);
    }

    // Apply deal if selected
    if ($this->selectedDealId) {
        $deal = Deal::find($this->selectedDealId);
        if ($deal) {
            // Calculate the discounted price for each service with this deal
            $discountPercentage = $deal->discount_percentage;
            $query->whereIn('id', $deal->services->pluck('id'))
                ->selectRaw("*, price * (1 - ? / 100) as final_price", [$discountPercentage]);
        }
    }

    // Paginate results
    $services = $query->paginate(10);

    // Pass services with final_price to the view
    return view('livewire.customer-services-view', [
        'services' => $services,
        'categories' => $this->categories,
        'showCategoryNames' => count($this->categoryFilter) <= 3,
    ]);
}

    public function applyDeal($dealId)
    {
        $this->selectedDealId = $dealId;
        $this->resetPage();  // Reset pagination after applying a deal
    }

    public function updatedCategoryFilter()
    {
        // Reset pagination to page 1 after category change
        $this->resetPage();
    }

    public function sortByPrice($sort)
    {
        // Set the sorting order based on selection
        if (in_array($sort, ['PriceLowToHigh', 'PriceHighToLow'])) {
            $this->sortByPrice = $sort;
        } else {
            $this->sortByPrice = 'PriceLowToHigh'; // Default to low-to-high
        }

        // Reset pagination to page 1 after sorting change
        $this->resetPage();
    }
}
