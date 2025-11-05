<?php

namespace App\Livewire;

use App\Models\Person;
use Livewire\Component;
use Livewire\WithPagination;

class PersonsIndex extends Component
{
    use WithPagination;
    
    public $search = '';
    public $sortField = 'first_name';
    public $sortDirection = 'asc';
    
    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'first_name'],
        'sortDirection' => ['except' => 'asc'],
    ];
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function deletePerson($personId)
    {
        $person = Person::find($personId);
        
        if ($person) {
            // Check if person has children
            if ($person->children()->count() > 0) {
                session()->flash('error', 'لا يمكن حذف هذا الشخص لأنه لديه أطفال. قم بحذف الأطفال أولاً.');
                return;
            }
            
            $person->delete();
            session()->flash('message', 'تم حذف الشخص بنجاح.');
        }
    }
    
    public function render()
    {
        $persons = Person::query()
            ->with('location') // تحميل علاقة location للبحث
            ->where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('location', function($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('occupation', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
            
        return view('livewire.persons-index', [
            'persons' => $persons,
        ])->layout('layouts.app');
    }
}
