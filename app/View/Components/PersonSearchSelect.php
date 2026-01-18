<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class PersonSearchSelect extends Component
{
    public string $name;
    public string $id;
    public ?int $selectedId;
    public ?string $selectedText;
    public string $placeholder;
    public string $url;
    public int $minInput;
    public ?string $gender;
    public bool $includeOutside;
    public bool $allowClear;

    public function __construct(
        string $name = 'person_id',
        ?string $id = null,
        ?int $selectedId = null,
        ?string $selectedText = null,
        string $placeholder = 'ابحث عن شخص...',
        ?string $url = null,
        int $minInput = 2,
        ?string $gender = null,
        bool $includeOutside = true,
        bool $allowClear = true,
    ) {
        $this->name = $name;
        $this->id = $id ?: preg_replace('/[^a-zA-Z0-9_\\-]/', '_', $name);
        $this->selectedId = $selectedId;
        $this->selectedText = $selectedText;
        $this->placeholder = $placeholder;
        $this->url = $url ?: route('persons.search');
        $this->minInput = $minInput;
        $this->gender = $gender;
        $this->includeOutside = $includeOutside;
        $this->allowClear = $allowClear;
    }

    public function render(): View
    {
        return view('components.person-search-select');
    }
}

