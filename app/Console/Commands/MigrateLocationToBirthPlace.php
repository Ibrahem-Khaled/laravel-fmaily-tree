<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Person;
use Illuminate\Support\Facades\DB;

class MigrateLocationToBirthPlace extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'persons:migrate-location-to-birth-place';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer location data to birth_place field';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration: location -> birth_place');

        // Get all persons that have location but birth_place is null or empty
        $persons = Person::whereNotNull('location')
            ->where('location', '!=', '')
            ->where(function($query) {
                $query->whereNull('birth_place')
                    ->orWhere('birth_place', '');
            })
            ->get();

        $this->info("Found {$persons->count()} persons to migrate");

        if ($persons->count() === 0) {
            $this->info('No persons to migrate.');
            return 0;
        }

        $bar = $this->output->createProgressBar($persons->count());
        $bar->start();

        $updated = 0;
        foreach ($persons as $person) {
            try {
                DB::table('persons')
                    ->where('id', $person->id)
                    ->update([
                        'birth_place' => $person->location
                    ]);
                $updated++;
            } catch (\Exception $e) {
                $this->error("Failed to update person ID {$person->id}: " . $e->getMessage());
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Migration completed! Updated {$updated} out of {$persons->count()} persons.");

        return 0;
    }
}
