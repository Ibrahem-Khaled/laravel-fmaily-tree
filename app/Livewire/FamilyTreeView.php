<?php

namespace App\Livewire;

use App\Models\Person;
use Livewire\Component;

class FamilyTreeView extends Component
{
    public $selectedPersonId = null;
    public $treeData = [];

    public function mount()
    {
        // Get all root nodes (persons without parents)
        $rootPersons = Person::whereIsRoot()->get();

        // If there are no root persons, return empty tree
        if ($rootPersons->isEmpty()) {
            $this->treeData = [];
            return;
        }

        // If no person is selected, select the first root person
        if (!$this->selectedPersonId && $rootPersons->isNotEmpty()) {
            $this->selectedPersonId = $rootPersons->first()->id;
        }

        // Build the tree data
        $this->buildTreeData();
    }

    public function selectPerson($personId)
    {
        $this->selectedPersonId = $personId;
        $this->buildTreeData();
    }

    protected function buildTreeData()
    {
        if (!$this->selectedPersonId) {
            return;
        }

        $selectedPerson = Person::find($this->selectedPersonId);
        if (!$selectedPerson) {
            return;
        }

        // Get the ancestors of the selected person
        $ancestors = $selectedPerson->ancestors()->get();

        // Get the descendants of the selected person
        $descendants = $selectedPerson->descendants()->get();

        // Build the tree data
        $this->treeData = $this->formatPersonData($selectedPerson);
        $this->treeData['ancestors'] = $ancestors->map(function ($person) {
            return $this->formatPersonData($person);
        })->toArray();

        // Group descendants by their parent_id
        $descendantsByParent = [];
        foreach ($descendants as $descendant) {
            $parentId = $descendant->parent_id;
            if (!isset($descendantsByParent[$parentId])) {
                $descendantsByParent[$parentId] = [];
            }
            $descendantsByParent[$parentId][] = $this->formatPersonData($descendant);
        }

        // Add the selected person's children
        $this->treeData['children'] = $descendantsByParent[$selectedPerson->id] ?? [];

        // Add children for each child recursively
        $this->addChildrenRecursively($this->treeData['children'], $descendantsByParent);
    }

    protected function addChildrenRecursively(&$nodes, $descendantsByParent)
    {
        foreach ($nodes as &$node) {
            if (isset($descendantsByParent[$node['id']])) {
                $node['children'] = $descendantsByParent[$node['id']];
                $this->addChildrenRecursively($node['children'], $descendantsByParent);
            } else {
                $node['children'] = [];
            }
        }
    }

    protected function formatPersonData($person)
    {
        // تحميل علاقة location إذا لم تكن محملة
        if (!$person->relationLoaded('location') && $person->location_id) {
            $person->load('location');
        }
        
        return [
            'id' => $person->id,
            'name' => $person->full_name,
            'birth_date' => $person->birth_date ? $person->birth_date->format('Y-m-d') : null,
            'death_date' => $person->death_date ? $person->death_date->format('Y-m-d') : null,
            'gender' => $person->gender,
            'photo_url' => $person->photo_url ? asset('storage/' . $person->photo_url) : null,
            'occupation' => $person->occupation,
            'location' => $person->location_display ?? null,
            'biography' => $person->biography,
            'parent_id' => $person->parent_id,
        ];
    }

    public function render()
    {
        $rootPersons = Person::whereIsRoot()->get();

        return view('livewire.family-tree-view', [
            'rootPersons' => $rootPersons,
        ])->layout('layouts.app');
    }
}
