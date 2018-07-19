<?php declare(strict_types=1);

namespace App\Contracts\Interactions\Components;

use App\Models\Component;

interface CreateComponent
{
    /**
     * @param array $data
     * @return Component
     */
    public function handle(array $data): Component;
}
