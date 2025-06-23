<?php

namespace App\Livewire;

use App\Models\Person;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class PersonForm extends Component
{
    use WithFileUploads;
    
    public Person $person;
    public $photo;
    public $parentId;
    public $isEdit = false;
    public $title;
    
    protected function rules()
    {
        return [
            'person.first_name' => 'required|string|max:255',
            'person.last_name' => 'required|string|max:255',
            'person.birth_date' => 'nullable|date',
            'person.death_date' => 'nullable|date|after_or_equal:person.birth_date',
            'person.gender' => 'required|in:male,female',
            'person.biography' => 'nullable|string',
            'person.occupation' => 'nullable|string|max:255',
            'person.location' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:1024', // 1MB Max
            'parentId' => 'nullable|exists:persons,id',
        ];
    }
    
    protected $messages = [
        'person.first_name.required' => 'الاسم الأول مطلوب',
        'person.last_name.required' => 'اسم العائلة مطلوب',
        'person.gender.required' => 'الجنس مطلوب',
        'person.death_date.after_or_equal' => 'تاريخ الوفاة يجب أن يكون بعد تاريخ الميلاد',
        'photo.image' => 'يجب أن يكون الملف صورة',
        'photo.max' => 'حجم الصورة يجب أن لا يتجاوز 1 ميجابايت',
        'parentId.exists' => 'الأب/الأم المحدد غير موجود',
    ];
    
    public function mount($person = null)
    {
        if ($person) {
            $this->person = $person;
            $this->parentId = $person->parent_id;
            $this->isEdit = true;
            $this->title = 'تعديل شخص';
        } else {
            $this->person = new Person();
            $this->title = 'إضافة شخص جديد';
        }
    }
    
    public function save()
    {
        $this->validate();
        
        // Handle photo upload
        if ($this->photo) {
            // Delete old photo if exists
            if ($this->person->photo_url) {
                Storage::delete('public/' . $this->person->photo_url);
            }
            
            $photoPath = $this->photo->store('photos', 'public');
            $this->person->photo_url = $photoPath;
        }
        
        // Save person
        $this->person->save();
        
        // Handle parent relationship
        if ($this->parentId) {
            $parent = Person::find($this->parentId);
            if ($parent) {
                $this->person->appendToNode($parent)->save();
            }
        } else {
            // If no parent, make it a root node
            if (!$this->isEdit) {
                $this->person->saveAsRoot();
            }
        }
        
        session()->flash('message', $this->isEdit ? 'تم تحديث الشخص بنجاح' : 'تم إضافة الشخص بنجاح');
        
        return redirect()->route('admin.persons.index');
    }
    
    public function render()
    {
        $potentialParents = Person::where('id', '!=', $this->person->id ?? 0)->get();
        
        return view('livewire.person-form', [
            'potentialParents' => $potentialParents,
        ])->layout('layouts.app');
    }
}
