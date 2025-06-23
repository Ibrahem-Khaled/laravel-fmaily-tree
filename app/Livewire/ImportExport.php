<?php

namespace App\Livewire;

use App\Exports\PersonsExport;
use App\Exports\PersonsJsonExport;
use App\Exports\PersonsPdfExport;
use App\Imports\PersonsImport;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ImportExport extends Component
{
    use WithFileUploads;
    
    public $importFile;
    public $importFormat = 'csv';
    public $exportFormat = 'csv';
    
    protected $rules = [
        'importFile' => 'required|file|mimes:csv,txt,json|max:2048',
        'importFormat' => 'required|in:csv,json',
    ];
    
    protected $messages = [
        'importFile.required' => 'يرجى اختيار ملف للاستيراد',
        'importFile.file' => 'يجب أن يكون الملف صالحًا',
        'importFile.mimes' => 'يجب أن يكون الملف بتنسيق CSV أو JSON',
        'importFile.max' => 'حجم الملف يجب أن لا يتجاوز 2 ميجابايت',
        'importFormat.required' => 'يرجى اختيار تنسيق الاستيراد',
        'importFormat.in' => 'تنسيق الاستيراد غير صالح',
    ];
    
    public function import()
    {
        $this->validate();
        
        try {
            if ($this->importFormat === 'csv') {
                Excel::import(new PersonsImport, $this->importFile);
            } elseif ($this->importFormat === 'json') {
                $content = file_get_contents($this->importFile->getRealPath());
                $data = json_decode($content, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    session()->flash('error', 'ملف JSON غير صالح: ' . json_last_error_msg());
                    return;
                }
                
                // Process JSON data
                $import = new PersonsImport();
                $import->collection(collect($data));
            }
            
            session()->flash('message', 'تم استيراد البيانات بنجاح');
            $this->reset('importFile');
        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء استيراد البيانات: ' . $e->getMessage());
        }
    }
    
    public function export()
    {
        try {
            switch ($this->exportFormat) {
                case 'csv':
                    return Excel::download(new PersonsExport, 'persons.csv');
                case 'xlsx':
                    return Excel::download(new PersonsExport, 'persons.xlsx');
                case 'pdf':
                    $exporter = new PersonsPdfExport();
                    return $exporter->export();
                case 'json':
                    $exporter = new PersonsJsonExport();
                    return $exporter->export();
                default:
                    session()->flash('error', 'تنسيق التصدير غير صالح');
                    break;
            }
        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء تصدير البيانات: ' . $e->getMessage());
        }
    }
    
    public function render()
    {
        return view('livewire.import-export')->layout('layouts.app');
    }
}
