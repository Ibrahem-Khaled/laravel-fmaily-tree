<?php

namespace App\Livewire;

use App\Models\Breastfeeding;
use App\Models\Person;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class BreastfeedingManagement extends Component
{
    use WithPagination, WithFileUploads;

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
    public $statusFilter = 'all'; // all, active, inactive
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

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

        // Apply search filter
        if ($this->search) {
            $query->whereHas('nursingMother', function($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%');
            })->orWhereHas('breastfedChild', function($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%');
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

        $breastfeedings = $query->paginate(15);

        // Get persons for dropdowns
        $nursingMothers = Person::where('gender', 'female')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name']);

        $breastfedChildren = Person::orderBy('first_name')
            ->get(['id', 'first_name', 'last_name']);

        return view('livewire.breastfeeding-management', [
            'breastfeedings' => $breastfeedings,
            'nursingMothers' => $nursingMothers,
            'breastfedChildren' => $breastfedChildren,
        ]);
    }

    public function create()
    {
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

        $this->showEditModal = true;
    }

    public function update()
    {
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
        Breastfeeding::findOrFail($this->deleteId)->delete();
        $this->showDeleteModal = false;
        session()->flash('success', 'تم حذف علاقة الرضاعة بنجاح');
    }

    public function toggleStatus($id)
    {
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
}
