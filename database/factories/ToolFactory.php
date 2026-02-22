<?php

namespace Database\Factories;

use App\Models\Tool;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tool>
 */
class ToolFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Tool::class;



    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $toolNames = [
            'Câble PROFIBUS Type A',
            'Câble PROFIBUS Type B',
            'Connecteur PROFIBUS 9 broches',
            'PLC Siemens S7-1200',
            'PLC Siemens S7-1500',
            'Module d’extension PLC',
            'Switch industriel Ethernet',
            'Passerelle PROFIBUS vers Ethernet',
            'Coupleur PROFIBUS',
            'Résistance de terminaison PROFIBUS',
            'Boîtier de jonction industriel',
            'Alimentation industrielle 24V',
            'Module IO déporté',
            'Câble Ethernet industriel blindé',
            'Interface PROFIBUS USB',
        ];

        return [
            'name' => $this->faker->randomElement($toolNames),

            'description' => $this->faker->boolean(70)
                ? $this->faker->sentence(12)
                : null,

            'reference' => strtoupper(
                'REF-' . $this->faker->unique()->bothify('PLC-####-??')
            ),

            'qty' => $this->faker->numberBetween(0, 16),
        ];
    }
}
