<?php

namespace Database\Factories;

use App\Models\Tool;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'name' => $toolNames[array_rand($toolNames)],

            'description' => random_int(1, 100) <= 70
                ? $this->generateSentence(12)
                : null,

            'reference' => strtoupper(
                'REF-PLC-' .
                random_int(1000, 9999) . '-' .
                Str::upper(Str::random(2))
            ),

            'qty' => random_int(1, 16),
        ];
    }


    private function generateSentence(int $words = 10): string
    {
        $wordList = [
            'industriel',
            'automate',
            'communication',
            'réseau',
            'installation',
            'maintenance',
            'équipement',
            'contrôle',
            'système',
            'module',
            'connectivité',
            'performance',
            'fiabilité',
            'sécurité'
        ];

        shuffle($wordList);

        return ucfirst(implode(' ', array_slice($wordList, 0, $words))) . '.';
    }
}
