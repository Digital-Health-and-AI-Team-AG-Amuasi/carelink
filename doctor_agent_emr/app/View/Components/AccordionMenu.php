<?php

declare(strict_types=1);

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AccordionMenu extends Component
{
    public function __construct(
        public string $title,
        public bool $active = false,
    ) {
        //
    }

    public function render(): Closure|string|View
    {
        return view('components.accordion-menu');
    }
}
