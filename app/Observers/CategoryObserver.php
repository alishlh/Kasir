<?php

namespace App\Observers;

use App\Models\Category;

class CategoryObserver
{

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        $category->products()->delete();
        
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        $category->products()->restore();
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        $category->products()->forceDelete();
    }
}
