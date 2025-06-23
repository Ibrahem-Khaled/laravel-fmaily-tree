<?php

namespace App\Livewire;

use App\Models\Person;
use Livewire\Component;

class PersonsOrder extends Component
{
    public $selectedPersonId = null;
    public $persons = [];
    public $children = [];
    
    public function mount()
    {
        // Get all root persons
        $this->persons = Person::whereIsRoot()->get();
        
        // If no person is selected, select the first root person
        if (!$this->selectedPersonId && $this->persons->isNotEmpty()) {
            $this->selectedPersonId = $this->persons->first()->id;
            $this->loadChildren();
        }
    }
    
    public function selectPerson($personId)
    {
        $this->selectedPersonId = $personId;
        $this->loadChildren();
    }
    
    public function loadChildren()
    {
        if (!$this->selectedPersonId) {
            return;
        }
        
        $person = Person::find($this->selectedPersonId);
        if (!$person) {
            return;
        }
        
        // Get immediate children
        $this->children = $person->children()->orderBy('_lft')->get();
    }
    
    public function updateOrder($orderedIds)
    {
        if (empty($orderedIds) || !$this->selectedPersonId) {
            return;
        }
        
        $parent = Person::find($this->selectedPersonId);
        if (!$parent) {
            return;
        }
        
        // Update the order of children
        foreach ($orderedIds as $index => $id) {
            $child = Person::find($id);
            if ($child) {
                // If the child is already a child of this parent, just update its position
                if ($child->parent_id == $parent->id) {
                    $child->_lft = ($index * 2) + 1;
                    $child->_rgt = ($index * 2) + 2;
                    $child->save();
                }
            }
        }
        
        // Rebuild the tree to ensure consistency
        Person::fixTree();
        
        // Reload children with new order
        $this->loadChildren();
        
        session()->flash('message', 'تم تحديث ترتيب الأشخاص بنجاح.');
    }
    
    public function render()
    {
        return view('livewire.persons-order', [
            'rootPersons' => $this->persons,
        ])->layout('layouts.app');
    }
}
