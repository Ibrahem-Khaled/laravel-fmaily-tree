<?php

namespace App\Livewire;

use App\Models\Breastfeeding;
use App\Models\Person;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BreastfeedingManagement extends Component
{
    use WithPagination, WithFileUploads, AuthorizesRequests;

    // Properties for form
    public $nursing_mother_id = '';
    public $breastfed_child_id = '';
    public $start_date = '';
    public $end_date = '';
    public $notes = '';
    public $is_active = true;

    // Properties for editing
    public $editingId = null;
    public $isEditing = false;

    // Properties for search and filters
    public $search = '';
    public $searchNursingMother = '';
    public $searchBreastfedChild = '';
    // Autocomplete inputs for modals
    public $motherSearch = '';
    public $childSearch = '';
    public $statusFilter = 'all'; // all, active, inactive
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 15;

    // Properties for modals
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $deleteId = null;

    protected $rules = [
        'nursing_mother_id' => 'required|exists:persons,id',
        'breastfed_child_id' => 'required|exists:persons,id|different:nursing_mother_id',
        'start_date' => 'nullable|date|before_or_equal:today',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'notes' => 'nullable|string|max:1000',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'nursing_mother_id.required' => 'يجب اختيار الأم المرضعة',
        'nursing_mother_id.exists' => 'الأم المرضعة المختارة غير موجودة',
        'breastfed_child_id.required' => 'يجب اختيار الطفل المرتضع',
        'breastfed_child_id.exists' => 'الطفل المرتضع المختار غير موجود',
        'breastfed_child_id.different' => 'لا يمكن أن تكون الأم المرضعة والطفل المرتضع نفس الشخص',
        'start_date.date' => 'تاريخ البداية غير صحيح',
        'start_date.before_or_equal' => 'تاريخ البداية لا يمكن أن يكون في المستقبل',
        'end_date.date' => 'تاريخ النهاية غير صحيح',
        'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية أو مساوياً له',
        'notes.max' => 'الملاحظات لا يمكن أن تتجاوز 1000 حرف',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function render()
    {
        $query = Breastfeeding::with(['nursingMother', 'breastfedChild']);

        // Apply general search filter
        if ($this->search) {
            $query->where(function($q) {
                $searchTerm = $this->search;
                $q->whereHas('nursingMother', function($subQ) use ($searchTerm) {
                    $subQ->searchByName($searchTerm);
                })->orWhereHas('breastfedChild', function($subQ) use ($searchTerm) {
                    $subQ->searchByName($searchTerm);
                });
            });
        }

        // Apply specific nursing mother search
        if ($this->searchNursingMother) {
            $query->whereHas('nursingMother', function($q) {
                $q->searchByName($this->searchNursingMother);
            });
        }

        // Apply specific breastfed child search
        if ($this->searchBreastfedChild) {
            $query->whereHas('breastfedChild', function($q) {
                $q->searchByName($this->searchBreastfedChild);
            });
        }

        // Apply status filter
        if ($this->statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        $breastfeedings = $query->paginate($this->perPage);

        // Suggestions for modal autocomplete (AJAX style)
        $motherSuggestions = Person::where('gender', 'female')
            ->when($this->motherSearch, function ($q) {
                $q->searchByName($this->motherSearch);
            })
            ->orderBy('first_name')

            ->get();

        $childSuggestions = Person::query()
            ->when($this->childSearch, function ($q) {
                $q->searchByName($this->childSearch);
            })
            ->orderBy('first_name')

            ->get();

        return view('livewire.breastfeeding-management', [
            'breastfeedings' => $breastfeedings,
            'motherSuggestions' => $motherSuggestions,
            'childSuggestions' => $childSuggestions,
        ]);
    }

    public function create()
    {
        $this->authorize('breastfeeding.create');
        $this->validate();

        // Check if relationship already exists
        $existingRelationship = Breastfeeding::where('nursing_mother_id', $this->nursing_mother_id)
            ->where('breastfed_child_id', $this->breastfed_child_id)
            ->first();

        if ($existingRelationship) {
            $this->addError('relationship', 'هذه العلاقة موجودة بالفعل');
            return;
        }

        // Ensure nursing mother is female
        $nursingMother = Person::find($this->nursing_mother_id);
        if ($nursingMother->gender !== 'female') {
            $this->addError('nursing_mother_id', 'الأم المرضعة يجب أن تكون أنثى');
            return;
        }

        Breastfeeding::create([
            'nursing_mother_id' => $this->nursing_mother_id,
            'breastfed_child_id' => $this->breastfed_child_id,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'notes' => $this->notes,
            'is_active' => $this->is_active,
        ]);

        $this->resetForm();
        $this->showCreateModal = false;
        session()->flash('success', 'تم إضافة علاقة الرضاعة بنجاح');
    }

    public function edit($id)
    {
        $breastfeeding = Breastfeeding::findOrFail($id);

        $this->editingId = $id;
        $this->nursing_mother_id = $breastfeeding->nursing_mother_id;
        $this->breastfed_child_id = $breastfeeding->breastfed_child_id;
        $this->start_date = $breastfeeding->start_date?->format('Y-m-d');
        $this->end_date = $breastfeeding->end_date?->format('Y-m-d');
        $this->notes = $breastfeeding->notes;
        $this->is_active = $breastfeeding->is_active;
        // Prefill autocomplete inputs with current selections
        $this->motherSearch = $breastfeeding->nursingMother?->full_name ?? '';
        $this->childSearch = $breastfeeding->breastfedChild?->full_name ?? '';

        $this->showEditModal = true;
    }

    public function update()
    {
        $this->authorize('breastfeeding.update');
        $this->validate();

        $breastfeeding = Breastfeeding::findOrFail($this->editingId);

        // Check if relationship already exists (excluding current record)
        $existingRelationship = Breastfeeding::where('nursing_mother_id', $this->nursing_mother_id)
            ->where('breastfed_child_id', $this->breastfed_child_id)
            ->where('id', '!=', $breastfeeding->id)
            ->first();

        if ($existingRelationship) {
            $this->addError('relationship', 'هذه العلاقة موجودة بالفعل');
            return;
        }

        // Ensure nursing mother is female
        $nursingMother = Person::find($this->nursing_mother_id);
        if ($nursingMother->gender !== 'female') {
            $this->addError('nursing_mother_id', 'الأم المرضعة يجب أن تكون أنثى');
            return;
        }

        $breastfeeding->update([
            'nursing_mother_id' => $this->nursing_mother_id,
            'breastfed_child_id' => $this->breastfed_child_id,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'notes' => $this->notes,
            'is_active' => $this->is_active,
        ]);

        $this->resetForm();
        $this->showEditModal = false;
        session()->flash('success', 'تم تحديث علاقة الرضاعة بنجاح');
    }

    public function delete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function confirmDelete()
    {
        $this->authorize('breastfeeding.delete');
        Breastfeeding::findOrFail($this->deleteId)->delete();
        $this->showDeleteModal = false;
        session()->flash('success', 'تم حذف علاقة الرضاعة بنجاح');
    }

    public function toggleStatus($id)
    {
        $this->authorize('breastfeeding.update');
        $breastfeeding = Breastfeeding::findOrFail($id);
        $breastfeeding->update(['is_active' => !$breastfeeding->is_active]);

        $status = $breastfeeding->is_active ? 'تفعيل' : 'إلغاء تفعيل';
        session()->flash('success', "تم {$status} علاقة الرضاعة بنجاح");
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function resetForm()
    {
        $this->nursing_mother_id = '';
        $this->breastfed_child_id = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->notes = '';
        $this->is_active = true;
        $this->editingId = null;
        $this->isEditing = false;
        $this->motherSearch = '';
        $this->childSearch = '';
        $this->resetErrorBag();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function closeModals()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedSearchNursingMother()
    {
        $this->resetPage();
    }

    public function updatedSearchBreastfedChild()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->searchNursingMother = '';
        $this->searchBreastfedChild = '';
        $this->resetPage();
    }

    public function selectNursingMother($personId)
    {
        $person = Person::find($personId);
        if (!$person) {
            return;
        }
        if ($person->gender !== 'female') {
            $this->addError('nursing_mother_id', 'الأم المرضعة يجب أن تكون أنثى');
            return;
        }
        $this->nursing_mother_id = $person->id;
        $this->motherSearch = $person->full_name;
        $this->resetValidation('nursing_mother_id');
    }

    public function selectBreastfedChild($personId)
    {
        $person = Person::find($personId);
        if (!$person) {
            return;
        }
        $this->breastfed_child_id = $person->id;
        $this->childSearch = $person->full_name;
        $this->resetValidation('breastfed_child_id');
    }
}
