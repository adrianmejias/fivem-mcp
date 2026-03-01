<?php

namespace App\Mcp\Tools\QBCore;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Look up QBCore shared data structure by name. Returns information about items, jobs, gangs, vehicles, weapons, and utility functions.')]
class GetQBCoreSharedData extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $dataType = $request->get('data_type');
        $language = $request->get('language', 'lua');

        $data = $this->findSharedData($dataType);

        if (! $data) {
            return Response::text(sprintf("QBCore shared data '%s' not found. Available types: Items, Jobs, Gangs, Vehicles, Weapons, StarterItems, Utilities", $dataType));
        }

        $output = $this->formatSharedInfo($data, $language);

        return Response::text($output);
    }

    /**
     * Find a QBCore shared data type.
     */
    protected function findSharedData(string $name): ?array
    {
        $sharedData = [
            'Items' => [
                'type' => 'Items',
                'description' => 'Table of all items available on the server. Each item has properties like label, weight, type, image, useable, etc.',
                'location' => 'qb-core/shared/items.lua',
                'properties' => [
                    ['name' => 'name', 'type' => 'string', 'description' => 'Internal item identifier'],
                    ['name' => 'label', 'type' => 'string', 'description' => 'Display name shown to players'],
                    ['name' => 'weight', 'type' => 'number', 'description' => 'Item weight (affects inventory limit)'],
                    ['name' => 'type', 'type' => 'string', 'description' => 'Item type (item, weapon, component, etc.)'],
                    ['name' => 'image', 'type' => 'string', 'description' => 'Image filename for inventory display'],
                    ['name' => 'unique', 'type' => 'boolean', 'description' => 'If true, item cannot be stacked'],
                    ['name' => 'useable', 'type' => 'boolean', 'description' => 'If true, item can be used/consumed'],
                    ['name' => 'shouldClose', 'type' => 'boolean', 'description' => 'If true, inventory closes when using item'],
                    ['name' => 'description', 'type' => 'string', 'description' => 'Item description shown in inventory'],
                ],
                'lua_example' => "local QBCore = exports['qb-core']:GetCoreObject({'Shared'})\nlocal item = QBCore.Shared.Items['water_bottle']\nif item then\n    print('Item: ' .. item.label .. ' (Weight: ' .. item.weight .. ')')\nend",
                'js_example' => "const QBCore = exports['qb-core']:GetCoreObject(['Shared']);\nconst item = QBCore.Shared.Items['water_bottle'];\nif (item) {\n    console.log(`Item: \${item.label} (Weight: \${item.weight})`);\n}",
            ],
            'Jobs' => [
                'type' => 'Jobs',
                'description' => 'Table of all jobs available on the server. Each job has grades (ranks) and payment amounts.',
                'location' => 'qb-core/shared/jobs.lua',
                'properties' => [
                    ['name' => 'label', 'type' => 'string', 'description' => 'Job display name'],
                    ['name' => 'type', 'type' => 'string', 'description' => 'Job type (leo, illegal, casino, etc.)'],
                    ['name' => 'defaultDuty', 'type' => 'boolean', 'description' => 'If true, player starts on duty'],
                    ['name' => 'offDutyPay', 'type' => 'boolean', 'description' => 'If true, player gets paid while off duty'],
                    ['name' => 'grades', 'type' => 'table', 'description' => 'Array of job grades with name and payment'],
                ],
                'lua_example' => "local QBCore = exports['qb-core']:GetCoreObject({'Shared'})\nlocal job = QBCore.Shared.Jobs['police']\nif job then\n    print('Job: ' .. job.label)\n    for i = 1, #job.grades do\n        local grade = job.grades[i]\n        print('  Grade: ' .. grade.name .. ' (Payment: $' .. grade.payment .. ')')\n    end\nend",
                'js_example' => "const QBCore = exports['qb-core']:GetCoreObject(['Shared']);\nconst job = QBCore.Shared.Jobs['police'];\nif (job) {\n    console.log(`Job: \${job.label}`);\n    job.grades.forEach((grade, i) => {\n        console.log(`  Grade: \${grade.name} (Payment: \\\$\${grade.payment})`);\n    });\n}",
            ],
            'Gangs' => [
                'type' => 'Gangs',
                'description' => 'Table of all gangs available on the server. Similar structure to jobs with labels and grades.',
                'location' => 'qb-core/shared/gangs.lua',
                'properties' => [
                    ['name' => 'label', 'type' => 'string', 'description' => 'Gang display name'],
                    ['name' => 'grades', 'type' => 'table', 'description' => 'Array of gang grades/ranks'],
                ],
                'lua_example' => "local QBCore = exports['qb-core']:GetCoreObject({'Shared'})\nlocal gang = QBCore.Shared.Gangs['lostmc']\nif gang then\n    print('Gang: ' .. gang.label)\n    for i = 1, #gang.grades do\n        print('  Rank: ' .. gang.grades[i].name)\n    end\nend",
                'js_example' => "const QBCore = exports['qb-core']:GetCoreObject(['Shared']);\nconst gang = QBCore.Shared.Gangs['lostmc'];\nif (gang) {\n    console.log(`Gang: \${gang.label}`);\n    gang.grades.forEach((grade) => {\n        console.log(`  Rank: \${grade.name}`);\n    });\n}",
            ],
            'Vehicles' => [
                'type' => 'Vehicles',
                'description' => 'Table of all vehicle models available on the server. Indexed by model name for quick lookup.',
                'location' => 'qb-core/shared/vehicles.lua',
                'properties' => [
                    ['name' => 'model', 'type' => 'string', 'description' => 'Vehicle model spawn code'],
                    ['name' => 'name', 'type' => 'string', 'description' => 'Vehicle display name'],
                    ['name' => 'brand', 'type' => 'string', 'description' => 'Vehicle manufacturer/brand'],
                    ['name' => 'price', 'type' => 'number', 'description' => 'Vehicle resale price'],
                    ['name' => 'category', 'type' => 'string', 'description' => 'Vehicle category (compacts, sedans, etc.)'],
                    ['name' => 'type', 'type' => 'string', 'description' => 'Vehicle type (automobile, bike, helicopter, etc.)'],
                    ['name' => 'shop', 'type' => 'string|table', 'description' => 'Shop(s) where vehicle can be purchased'],
                ],
                'lua_example' => "local QBCore = exports['qb-core']:GetCoreObject({'Shared'})\nlocal vehicle = QBCore.Shared.Vehicles['adder']\nif vehicle then\n    print('Vehicle: ' .. vehicle.name .. ' by ' .. vehicle.brand)\n    print('Price: $' .. vehicle.price)\nend",
                'js_example' => "const QBCore = exports['qb-core']:GetCoreObject(['Shared']);\nconst vehicle = QBCore.Shared.Vehicles['adder'];\nif (vehicle) {\n    console.log(`Vehicle: \${vehicle.name} by \${vehicle.brand}`);\n    console.log(`Price: \\\$\${vehicle.price}`);\n}",
            ],
            'Weapons' => [
                'type' => 'Weapons',
                'description' => 'Table of all weapons indexed by weapon hash. Used for retrieving weapon information.',
                'location' => 'qb-core/shared/weapons.lua',
                'properties' => [
                    ['name' => 'name', 'type' => 'string', 'description' => 'Weapon model/spawn name'],
                    ['name' => 'label', 'type' => 'string', 'description' => 'Weapon display name'],
                    ['name' => 'weapontype', 'type' => 'string', 'description' => 'Weapon type (Pistol, Rifle, Shotgun, etc.)'],
                    ['name' => 'ammotype', 'type' => 'string', 'description' => 'Ammo type used by weapon'],
                    ['name' => 'damageReason', 'type' => 'string', 'description' => 'Death notification message for kills'],
                ],
                'lua_example' => "local QBCore = exports['qb-core']:GetCoreObject({'Shared'})\nlocal weapon = QBCore.Shared.Weapons[`weapon_pistol`]\nif weapon then\n    print('Weapon: ' .. weapon.label .. ' (' .. weapon.weapontype .. ')')\nend",
                'js_example' => "const QBCore = exports['qb-core']:GetCoreObject(['Shared']);\nconst weapon = QBCore.Shared.Weapons['weapon_pistol'];\nif (weapon) {\n    console.log(`Weapon: \${weapon.label} (\${weapon.weapontype})`);\n}",
            ],
            'StarterItems' => [
                'type' => 'StarterItems',
                'description' => 'Table of items that new players receive when first joining. Defines item name and amount.',
                'location' => 'qb-core/shared/main.lua',
                'properties' => [
                    ['name' => 'item_name', 'type' => 'string', 'description' => 'Item to give to new players'],
                    ['name' => 'amount', 'type' => 'number', 'description' => 'Amount of item to give'],
                ],
                'lua_example' => "local QBCore = exports['qb-core']:GetCoreObject({'Shared'})\nfor item, amount in pairs(QBCore.Shared.StarterItems) do\n    local itemInfo = QBCore.Shared.Items[item]\n    if itemInfo then\n        print('New players get ' .. amount .. 'x ' .. itemInfo.label)\n    end\nend",
                'js_example' => "const QBCore = exports['qb-core']:GetCoreObject(['Shared']);\nfor (const [item, amount] of Object.entries(QBCore.Shared.StarterItems)) {\n    const itemInfo = QBCore.Shared.Items[item];\n    if (itemInfo) {\n        console.log(`New players get \${amount}x \${itemInfo.label}`);\n    }\n}",
            ],
            'Utilities' => [
                'type' => 'Utilities',
                'description' => 'Utility functions available in QBShared for common operations like string manipulation and vehicle handling.',
                'location' => 'qb-core/shared/main.lua',
                'methods' => [
                    ['name' => 'RandomStr(length)', 'description' => 'Generate random string of specified length'],
                    ['name' => 'RandomInt(length)', 'description' => 'Generate random numeric string of specified length'],
                    ['name' => 'SplitStr(str, delimiter)', 'description' => 'Split string by delimiter into table'],
                    ['name' => 'Trim(value)', 'description' => 'Remove leading/trailing whitespace'],
                    ['name' => 'Round(number, decimals)', 'description' => 'Round number to specified decimal places'],
                    ['name' => 'ChangeVehicleExtra(vehicle, extra, enable)', 'description' => 'Toggle vehicle extra on/off'],
                    ['name' => 'SetDefaultVehicleExtras(vehicle, config)', 'description' => 'Set default vehicle extras from config table'],
                ],
                'lua_example' => "local QBCore = exports['qb-core']:GetCoreObject({'Shared'})\nlocal randomId = QBCore.Shared.RandomStr(8)\nlocal trimmed = QBCore.Shared.Trim('  hello world  ')\nlocal rounded = QBCore.Shared.Round(3.14159, 2)  -- Returns 3.14\nprint('Random ID: ' .. randomId)\nprint('Trimmed: ' .. trimmed)\nprint('Rounded: ' .. rounded)",
                'js_example' => "const QBCore = exports['qb-core']:GetCoreObject(['Shared']);\nconst randomId = QBCore.Shared.RandomStr(8);\nconst trimmed = QBCore.Shared.Trim('  hello world  ');\nconst rounded = QBCore.Shared.Round(3.14159, 2);  // Returns 3.14\nconsole.log(`Random ID: \${randomId}`);\nconsole.log(`Trimmed: \${trimmed}`);\nconsole.log(`Rounded: \${rounded}`);",
            ],
        ];

        $nameLower = strtolower($name);

        foreach ($sharedData as $typeName => $typeData) {
            if (strtolower($typeName) === $nameLower) {
                return $typeData;
            }
        }

        return null;
    }

    /**
     * Format shared data info.
     */
    protected function formatSharedInfo(array $data, string $language): string
    {
        return view('mcp.qbcore.qbcore-shared-data', [
            'data' => $data,
            'language' => $language,
        ])->render();
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\Contracts\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'data_type' => $schema
                ->string()
                ->description('The type of QBCore shared data to look up (Items, Jobs, Gangs, Vehicles, Weapons, StarterItems, Utilities)')
                ->enum(['Items', 'Jobs', 'Gangs', 'Vehicles', 'Weapons', 'StarterItems', 'Utilities'])
                ->required(),
            'language' => $schema
                ->string()
                ->enum(['lua', 'js'])
                ->description('The scripting language for code examples')
                ->default('lua'),
        ];
    }
}
