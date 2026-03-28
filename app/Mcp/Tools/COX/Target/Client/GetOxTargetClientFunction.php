<?php

namespace App\Mcp\Tools\COX\Target\Client;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Look up ox_target client functions by name. Returns function signature, parameters, return type, and usage examples for targeting/third-eye interaction system.')]
class GetOxTargetClientFunction extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $functionName = $request->get('function_name');
        $language = $request->get('language', 'lua');

        $function = $this->findFunction($functionName);

        if (! $function) {
            return Response::text(sprintf("ox_target client function '%s' not found. Check ox_target documentation at https://coxdocs.dev/ox_target", $functionName));
        }

        $output = $this->formatFunctionInfo($function, $language);

        return Response::text($output);
    }

    /**
     * Find an ox_target client function.
     */
    protected function findFunction(string $name): ?array
    {
        $functions = [
            'disableTargeting' => [
                'namespace' => 'Client',
                'description' => 'Disables or enables the targeting system',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'disabled', 'type' => 'boolean', 'description' => 'true to disable, false to enable'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => 'exports.ox_target:disableTargeting(true) -- Disable targeting',
                'js_example' => 'exports.ox_target.disableTargeting(true); // Disable targeting',
            ],
            'isActive' => [
                'namespace' => 'Client',
                'description' => 'Check if the targeting system is currently active',
                'side' => 'client',
                'parameters' => [],
                'returns' => ['type' => 'boolean', 'description' => 'true if targeting is active'],
                'lua_example' => "local active = exports.ox_target:isActive()\nif active then\n    print('Targeting is active')\nend",
                'js_example' => "const active = exports.ox_target.isActive();\nif (active) {\n    console.log('Targeting is active');\n}",
            ],
            'addGlobalOption' => [
                'namespace' => 'Client',
                'description' => 'Add a global target option that appears on all entities',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'options', 'type' => 'table', 'description' => 'Target option configuration'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "exports.ox_target:addGlobalOption({\n    name = 'greet',\n    icon = 'fas fa-hand-wave',\n    label = 'Wave',\n    onSelect = function()\n        print('You waved!')\n    end\n})",
                'js_example' => "exports.ox_target.addGlobalOption({\n    name: 'greet',\n    icon: 'fas fa-hand-wave',\n    label: 'Wave',\n    onSelect: () => {\n        console.log('You waved!');\n    }\n});",
            ],
            'addGlobalPed' => [
                'namespace' => 'Client',
                'description' => 'Add target options to all peds',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'options', 'type' => 'table', 'description' => 'Array of target options'],
                ],
                'returns' => ['type' => 'number', 'description' => 'Option ID'],
                'lua_example' => "local id = exports.ox_target:addGlobalPed({\n    {\n        name = 'talk',\n        icon = 'fas fa-comment',\n        label = 'Talk',\n        onSelect = function(data)\n            print('Talking to ped: '..data.entity)\n        end\n    }\n})",
                'js_example' => "const id = exports.ox_target.addGlobalPed([\n    {\n        name: 'talk',\n        icon: 'fas fa-comment',\n        label: 'Talk',\n        onSelect: (data) => {\n            console.log('Talking to ped: ' + data.entity);\n        }\n    }\n]);",
            ],
            'addGlobalVehicle' => [
                'namespace' => 'Client',
                'description' => 'Add target options to all vehicles',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'options', 'type' => 'table', 'description' => 'Array of target options'],
                ],
                'returns' => ['type' => 'number', 'description' => 'Option ID'],
                'lua_example' => "local id = exports.ox_target:addGlobalVehicle({\n    {\n        name = 'lockpick',\n        icon = 'fas fa-lock',\n        label = 'Lockpick Vehicle',\n        canInteract = function(entity)\n            return GetVehicleDoorLockStatus(entity) == 2\n        end,\n        onSelect = function(data)\n            -- Lockpick logic\n        end\n    }\n})",
                'js_example' => "const id = exports.ox_target.addGlobalVehicle([\n    {\n        name: 'lockpick',\n        icon: 'fas fa-lock',\n        label: 'Lockpick Vehicle',\n        canInteract: (entity) => {\n            return GetVehicleDoorLockStatus(entity) === 2;\n        },\n        onSelect: (data) => {\n            // Lockpick logic\n        }\n    }\n]);",
            ],
            'addGlobalObject' => [
                'namespace' => 'Client',
                'description' => 'Add target options to all objects',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'options', 'type' => 'table', 'description' => 'Array of target options'],
                ],
                'returns' => ['type' => 'number', 'description' => 'Option ID'],
                'lua_example' => "local id = exports.ox_target:addGlobalObject({\n    {\n        name = 'examine',\n        icon = 'fas fa-search',\n        label = 'Examine',\n        onSelect = function(data)\n            print('Examining object')\n        end\n    }\n})",
                'js_example' => "const id = exports.ox_target.addGlobalObject([\n    {\n        name: 'examine',\n        icon: 'fas fa-search',\n        label: 'Examine',\n        onSelect: (data) => {\n            console.log('Examining object');\n        }\n    }\n]);",
            ],
            'addGlobalPlayer' => [
                'namespace' => 'Client',
                'description' => 'Add target options to all players',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'options', 'type' => 'table', 'description' => 'Array of target options'],
                ],
                'returns' => ['type' => 'number', 'description' => 'Option ID'],
                'lua_example' => "local id = exports.ox_target:addGlobalPlayer({\n    {\n        name = 'givemoney',\n        icon = 'fas fa-dollar-sign',\n        label = 'Give Money',\n        onSelect = function(data)\n            -- Give money logic\n        end\n    }\n})",
                'js_example' => "const id = exports.ox_target.addGlobalPlayer([\n    {\n        name: 'givemoney',\n        icon: 'fas fa-dollar-sign',\n        label: 'Give Money',\n        onSelect: (data) => {\n            // Give money logic\n        }\n    }\n]);",
            ],
            'addModel' => [
                'namespace' => 'Client',
                'description' => 'Add target options to specific entity models',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'models', 'type' => 'string|table', 'description' => 'Model hash or array of model hashes'],
                    ['name' => 'options', 'type' => 'table', 'description' => 'Array of target options'],
                ],
                'returns' => ['type' => 'number', 'description' => 'Option ID'],
                'lua_example' => "local id = exports.ox_target:addModel('prop_atm_01', {\n    {\n        name = 'atm',\n        icon = 'fas fa-credit-card',\n        label = 'Use ATM',\n        onSelect = function()\n            -- Open ATM interface\n        end\n    }\n})",
                'js_example' => "const id = exports.ox_target.addModel('prop_atm_01', [\n    {\n        name: 'atm',\n        icon: 'fas fa-credit-card',\n        label: 'Use ATM',\n        onSelect: () => {\n            // Open ATM interface\n        }\n    }\n]);",
            ],
            'addEntity' => [
                'namespace' => 'Client',
                'description' => 'Add target options to a specific entity',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'entity', 'type' => 'number', 'description' => 'Entity handle'],
                    ['name' => 'options', 'type' => 'table', 'description' => 'Array of target options'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "local ped = PlayerPedId()\nexports.ox_target:addEntity(ped, {\n    {\n        name = 'selfie',\n        icon = 'fas fa-camera',\n        label = 'Take Selfie',\n        onSelect = function()\n            print('Taking selfie')\n        end\n    }\n})",
                'js_example' => "const ped = PlayerPedId();\nexports.ox_target.addEntity(ped, [\n    {\n        name: 'selfie',\n        icon: 'fas fa-camera',\n        label: 'Take Selfie',\n        onSelect: () => {\n            console.log('Taking selfie');\n        }\n    }\n]);",
            ],
            'addLocalEntity' => [
                'namespace' => 'Client',
                'description' => 'Add target options to a networked entity (client-side only)',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'netId', 'type' => 'number', 'description' => 'Network ID'],
                    ['name' => 'options', 'type' => 'table', 'description' => 'Array of target options'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "local netId = NetworkGetNetworkIdFromEntity(entity)\nexports.ox_target:addLocalEntity(netId, {\n    {\n        name = 'interact',\n        icon = 'fas fa-hand',\n        label = 'Interact',\n        onSelect = function()\n            print('Interacting')\n        end\n    }\n})",
                'js_example' => "const netId = NetworkGetNetworkIdFromEntity(entity);\nexports.ox_target.addLocalEntity(netId, [\n    {\n        name: 'interact',\n        icon: 'fas fa-hand',\n        label: 'Interact',\n        onSelect: () => {\n            console.log('Interacting');\n        }\n    }\n]);",
            ],
            'addSphereZone' => [
                'namespace' => 'Client',
                'description' => 'Add a sphere zone with target options',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'name', 'type' => 'string', 'description' => 'Unique zone name'],
                    ['name' => 'coords', 'type' => 'vector3', 'description' => 'Zone center coordinates'],
                    ['name' => 'radius', 'type' => 'number', 'description' => 'Zone radius'],
                    ['name' => 'options', 'type' => 'table', 'description' => 'Array of target options'],
                    ['name' => 'zoneOptions', 'type' => 'table', 'description' => 'Zone configuration'],
                ],
                'returns' => ['type' => 'number', 'description' => 'Zone ID'],
                'lua_example' => "local id = exports.ox_target:addSphereZone('bank_atm', vector3(150.0, -1040.0, 29.5), 2.0, {\n    {\n        name = 'atm',\n        icon = 'fas fa-credit-card',\n        label = 'Use ATM',\n        onSelect = function()\n            print('Opening ATM')\n        end\n    }\n}, {})",
                'js_example' => "const id = exports.ox_target.addSphereZone('bank_atm', [150.0, -1040.0, 29.5], 2.0, [\n    {\n        name: 'atm',\n        icon: 'fas fa-credit-card',\n        label: 'Use ATM',\n        onSelect: () => {\n            console.log('Opening ATM');\n        }\n    }\n], {});",
            ],
            'addBoxZone' => [
                'namespace' => 'Client',
                'description' => 'Add a box zone with target options',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'name', 'type' => 'string', 'description' => 'Unique zone name'],
                    ['name' => 'coords', 'type' => 'vector3', 'description' => 'Zone center coordinates'],
                    ['name' => 'size', 'type' => 'vector3', 'description' => 'Zone size (length, width, height)'],
                    ['name' => 'rotation', 'type' => 'number', 'description' => 'Zone rotation'],
                    ['name' => 'options', 'type' => 'table', 'description' => 'Array of target options'],
                    ['name' => 'zoneOptions', 'type' => 'table', 'description' => 'Zone configuration'],
                ],
                'returns' => ['type' => 'number', 'description' => 'Zone ID'],
                'lua_example' => "local id = exports.ox_target:addBoxZone('garage', vector3(215.0, -809.0, 30.7), vector3(5.0, 10.0, 3.0), 0, {\n    {\n        name = 'vehicles',\n        icon = 'fas fa-car',\n        label = 'Garage',\n        onSelect = function()\n            print('Opening garage')\n        end\n    }\n}, {})",
                'js_example' => "const id = exports.ox_target.addBoxZone('garage', [215.0, -809.0, 30.7], [5.0, 10.0, 3.0], 0, [\n    {\n        name: 'vehicles',\n        icon: 'fas fa-car',\n        label: 'Garage',\n        onSelect: () => {\n            console.log('Opening garage');\n        }\n    }\n], {});",
            ],
            'addPolyZone' => [
                'namespace' => 'Client',
                'description' => 'Add a polygon zone with target options',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'name', 'type' => 'string', 'description' => 'Unique zone name'],
                    ['name' => 'points', 'type' => 'table', 'description' => 'Array of vector2 points'],
                    ['name' => 'options', 'type' => 'table', 'description' => 'Array of target options'],
                    ['name' => 'zoneOptions', 'type' => 'table', 'description' => 'Zone configuration'],
                ],
                'returns' => ['type' => 'number', 'description' => 'Zone ID'],
                'lua_example' => "local id = exports.ox_target:addPolyZone('parking', {\n    vector2(200.0, -800.0),\n    vector2(220.0, -800.0),\n    vector2(220.0, -820.0),\n    vector2(200.0, -820.0)\n}, {\n    {\n        name = 'park',\n        icon = 'fas fa-parking',\n        label = 'Park Vehicle',\n        onSelect = function()\n            print('Parking vehicle')\n        end\n    }\n}, {})",
                'js_example' => "const id = exports.ox_target.addPolyZone('parking', [\n    [200.0, -800.0],\n    [220.0, -800.0],\n    [220.0, -820.0],\n    [200.0, -820.0]\n], [\n    {\n        name: 'park',\n        icon: 'fas fa-parking',\n        label: 'Park Vehicle',\n        onSelect: () => {\n            console.log('Parking vehicle');\n        }\n    }\n], {});",
            ],
            'removeZone' => [
                'namespace' => 'Client',
                'description' => 'Remove a zone by ID',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'id', 'type' => 'number', 'description' => 'Zone ID to remove'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => 'exports.ox_target:removeZone(zoneId)',
                'js_example' => 'exports.ox_target.removeZone(zoneId);',
            ],
            'zoneExists' => [
                'namespace' => 'Client',
                'description' => 'Check if a zone exists by name',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'name', 'type' => 'string', 'description' => 'Zone name'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'true if zone exists'],
                'lua_example' => "local exists = exports.ox_target:zoneExists('bank_atm')\nif exists then\n    print('Zone exists')\nend",
                'js_example' => "const exists = exports.ox_target.zoneExists('bank_atm');\nif (exists) {\n    console.log('Zone exists');\n}",
            ],
        ];

        $nameLower = strtolower($name);

        foreach ($functions as $functionName => $functionData) {
            if (strtolower($functionName) === $nameLower) {
                return array_merge(['name' => $functionName], $functionData);
            }
        }

        return null;
    }

    /**
     * Format function info.
     */
    protected function formatFunctionInfo(array $function, string $language): string
    {
        return view('mcp.cox.cox-function', [
            'function' => $function,
            'language' => $language,
        ])->render();
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'function_name' => $schema
                ->string()
                ->description('The name of the ox_target client function to look up (e.g., "addSphereZone", "addGlobalVehicle", "addModel")')
                ->required(),
            'language' => $schema
                ->string()
                ->enum(['lua', 'js'])
                ->description('The scripting language for code examples')
                ->default('lua'),
        ];
    }
}
