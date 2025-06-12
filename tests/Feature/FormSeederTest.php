<?php

namespace Tests\Feature;

use App\Models\Form;
use Database\Seeders\FormSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FormSeederTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_form_fields_and_options()
    {
        $this->seed(FormSeeder::class);

        $form = Form::with('fields.options')->first();

        $this->assertNotNull($form);
        $this->assertGreaterThan(0, $form->fields->count(), 'Form should have fields');
        $this->assertGreaterThan(0, $form->fields->first()->options->count(), 'Field should have options');
    }
}
