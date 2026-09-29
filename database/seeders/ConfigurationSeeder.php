<?php

namespace Database\Seeders;

use App\Model\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configurations = config('configuration');

        if (!$configurations) {
            return;
        }

        foreach ($configurations as $configGroup) {
            if (isset($configGroup['sections']) && is_array($configGroup['sections'])) {
                foreach ($configGroup['sections'] as $section) {
                    if (isset($section['fields']) && is_array($section['fields'])) {
                        foreach ($section['fields'] as $field) {
                            if (isset($field['name'])) {
                                $existing = Setting::where('key', $field['name'])->first();

                                if (! $existing) {
                                    $default = $field['default'] ?? null;

                                    Setting::create([
                                        'key' => $field['name'],
                                        'value' => $default,
                                        'type' => $field['type'] ?? 'default'
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
