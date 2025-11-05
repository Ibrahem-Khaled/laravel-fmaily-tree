<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Location;
use App\Models\Person;

return new class extends Migration
{
    /**
     * Run the migrations.
     * نقل البيانات من حقل location في جدول persons إلى جدول locations
     */
    public function up(): void
    {
        // التحقق من وجود جدول locations و persons
        if (!Schema::hasTable('locations') || !Schema::hasTable('persons')) {
            return;
        }

        // جلب جميع القيم الفريدة من حقل location
        $locations = DB::table('persons')
            ->select('location')
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->distinct()
            ->get();

        $migratedCount = 0;

        foreach ($locations as $locationData) {
            $locationName = trim($locationData->location);
            
            if (empty($locationName)) {
                continue;
            }

            try {
                // استخدام findOrCreateByName لإنشاء أو العثور على المكان
                // هذا سيضمن عدم تكرار الأماكن المتشابهة
                $location = Location::findOrCreateByName($locationName);
                
                // تحديث جميع الأشخاص الذين لديهم نفس المكان
                $updated = DB::table('persons')
                    ->where('location', $locationName)
                    ->update(['location_id' => $location->id]);
                
                $migratedCount += $updated;
                
                // تحديث عداد الأشخاص للمكان
                $location->persons_count = $location->persons()->count();
                $location->save();
                
            } catch (\Exception $e) {
                // في حالة حدوث خطأ، نتابع مع الأماكن الأخرى
                \Log::warning("Failed to migrate location: {$locationName}. Error: " . $e->getMessage());
                continue;
            }
        }

        \Log::info("Location migration completed. Migrated {$migratedCount} persons to locations table.");
    }

    /**
     * Reverse the migrations.
     * استعادة البيانات من location_id إلى location
     */
    public function down(): void
    {
        if (!Schema::hasColumn('persons', 'location_id')) {
            return;
        }

        // جلب جميع الأشخاص الذين لديهم location_id
        $persons = DB::table('persons')
            ->join('locations', 'persons.location_id', '=', 'locations.id')
            ->select('persons.id', 'locations.name')
            ->whereNotNull('persons.location_id')
            ->get();

        foreach ($persons as $person) {
            DB::table('persons')
                ->where('id', $person->id)
                ->update(['location' => $person->name]);
        }

        // إعادة تعيين location_id
        DB::table('persons')->update(['location_id' => null]);
    }
};
