<?php

namespace Database\Seeders;

use App\Models\Form;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private function isValidUuid($uuid): bool
    {
        return is_string($uuid) && preg_match(
            '/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[1-5][0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}$/',
            $uuid
        );
    }

    public function run(): void
    {
        $jsonPath = database_path('data/submission.json');

        if (!File::exists($jsonPath)) {
            $this->command->error('submission.json not found');
            return;
        }

        $raw = File::get($jsonPath);
        $structure = json_decode($raw, true);

        if (!is_array($structure)) {
            $this->command->error('Invalid JSON structure');
            return;
        }

        foreach ($structure as $section) {
            $form = Form::create([
                'name' => $section['name'],
            ]);

            foreach ($section['payloads'] ?? [] as $fieldData) {
                $field = $form->fields()->create([
                    'label' => $fieldData['label'],
                    'type' => $fieldData['type'],
                    'description' => $fieldData['description'] ?? null,
                    'sub_type' => $fieldData['sub_type'] ?? null,
                    'parent_id' => null,
                ]);

                foreach ($fieldData['options'] ?? [] as $opt) {
                    $parentId = $opt['parent_id'] ?? null;

                    $field->options()->create([
                        'label' => $opt['label'],
                        'value' => $opt['value'] ?? null,
                        'parent_id' => $this->isValidUuid($parentId) ? $parentId : null,
                    ]);
                }

                foreach ($fieldData['sub_payloads'] ?? [] as $subFieldData) {
                    $subField = $form->fields()->create([
                        'label' => $subFieldData['label'],
                        'type' => $subFieldData['type'],
                        'description' => $subFieldData['description'] ?? null,
                        'sub_type' => $subFieldData['sub_type'] ?? null,
                        'parent_id' => $field->id,
                    ]);

                    foreach ($subFieldData['options'] ?? [] as $subOpt) {
                        $subField->options()->create([
                            'label' => $subOpt['label'],
                            'value' => $subOpt['value'] ?? null,
                            'parent_id' => $subOpt['parent_id'] ?? null,
                        ]);
                    }
                }
            }
        }

        $this->command->info("Seeding done: Forms + Fields + Options created.");
    }
}
