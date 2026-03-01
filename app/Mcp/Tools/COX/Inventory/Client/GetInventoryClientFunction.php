<?php

namespace App\Mcp\Tools\COX\Inventory\Client;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Look up ox_inventory client functions by name. Returns function signature, parameters, return type, and usage examples for client-side inventory management.')]
class GetInventoryClientFunction extends Tool
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
            return Response::text(sprintf("ox_inventory client function '%s' not found. Check ox_inventory documentation at https://coxdocs.dev/ox_inventory", $functionName));
        }

        $output = $this->formatFunctionInfo($function, $language);

        return Response::text($output);
    }

    /**
     * Find an ox_inventory client function.
     */
    protected function findFunction(string $name): ?array
    {
        $functions = [
            'openInventory' => [
                'namespace' => 'Client',
                'description' => 'Opens an inventory using the passed data',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'invType', 'type' => 'string', 'description' => "Type: 'player', 'shop', 'stash', 'crafting', 'container', 'drop', 'glovebox', 'trunk', 'dumpster'"],
                    ['name' => 'data', 'type' => 'number|string|table', 'description' => 'Inventory data (playerId, stash name, etc.)'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "exports.ox_inventory:openInventory('player', 3)",
                'js_example' => "exports.ox_inventory.openInventory('player', 3);",
            ],
            'SearchPlayer' => [
                'namespace' => 'Client',
                'description' => 'Searches the player inventory for an item. Can return slots or count.',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'search', 'type' => 'string', 'description' => "'slots' or 'count'"],
                    ['name' => 'item', 'type' => 'table|string', 'description' => 'Item name or array of items'],
                    ['name' => 'metadata', 'type' => 'table|string|nil', 'description' => 'Metadata filter'],
                ],
                'returns' => ['type' => 'table|number', 'description' => 'Slots or count depending on search type'],
                'lua_example' => "local count = exports.ox_inventory:SearchPlayer('count', 'water')\nprint('You have '..count..' water')",
                'js_example' => "const count = exports.ox_inventory.SearchPlayer('count', 'water');\nconsole.log(`You have \${count} water`);",
            ],
            'GetCurrentWeapon' => [
                'namespace' => 'Client',
                'description' => 'Get data for the currently equipped weapon',
                'side' => 'client',
                'parameters' => [],
                'returns' => ['type' => 'table|nil', 'description' => 'Weapon data or nil if no weapon equipped'],
                'lua_example' => "local weapon = exports.ox_inventory:GetCurrentWeapon()\nif weapon then\n    print('Current weapon: '..weapon.name)\nend",
                'js_example' => "const weapon = exports.ox_inventory.GetCurrentWeapon();\nif (weapon) {\n    console.log('Current weapon: ' + weapon.name);\n}",
            ],
            'closeInventory' => [
                'namespace' => 'Client',
                'description' => 'Closes the currently open inventory',
                'side' => 'client',
                'parameters' => [],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => 'exports.ox_inventory:closeInventory()',
                'js_example' => 'exports.ox_inventory.closeInventory();',
            ],
            'useItem' => [
                'namespace' => 'Client',
                'description' => 'Uses an item in the inventory. Triggers server-side item use callback.',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'item', 'type' => 'table', 'description' => 'Item data with slot and name'],
                    ['name' => 'cb', 'type' => 'function|nil', 'description' => 'Callback function after use'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "local item = {slot = 1, name = 'water'}\nexports.ox_inventory:useItem(item)",
                'js_example' => "const item = {slot: 1, name: 'water'};\nexports.ox_inventory.useItem(item);",
            ],
            'displayMetadata' => [
                'namespace' => 'Client',
                'description' => 'Shows metadata information for an item',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'metadata', 'type' => 'table', 'description' => 'Metadata table to display'],
                ],
                'returns' => ['type' => 'table', 'description' => 'Formatted metadata for display'],
                'lua_example' => "local formatted = exports.ox_inventory:displayMetadata({serial = 'ABC123', owner = 'John'})",
                'js_example' => "const formatted = exports.ox_inventory.displayMetadata({serial: 'ABC123', owner: 'John'});",
            ],
            'Items' => [
                'namespace' => 'Client',
                'description' => 'Register client-side usable items with callbacks',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'itemName', 'type' => 'string', 'description' => 'Name of the item to register'],
                    ['name' => 'callback', 'type' => 'function', 'description' => 'Function(data, slot) called when item is used'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "exports.ox_inventory:Items('bandage', function(data, slot)\n    -- Apply bandage healing logic\n    print('Used bandage from slot '..slot)\nend)",
                'js_example' => "exports.ox_inventory.Items('bandage', (data, slot) => {\n    // Apply bandage healing logic\n    console.log(`Used bandage from slot \${slot}`);\n});",
            ],
            'getCurrentWeight' => [
                'namespace' => 'Client',
                'description' => 'Returns the current total weight of the player inventory',
                'side' => 'client',
                'parameters' => [],
                'returns' => ['type' => 'number', 'description' => 'Current weight in grams'],
                'lua_example' => "local weight = exports.ox_inventory:getCurrentWeight()\nprint('Current inventory weight: '..weight..'g')",
                'js_example' => "const weight = exports.ox_inventory.getCurrentWeight();\nconsole.log(`Current inventory weight: \${weight}g`);",
            ],
            'getPlayerInventory' => [
                'namespace' => 'Client',
                'description' => 'Returns the full player inventory data',
                'side' => 'client',
                'parameters' => [],
                'returns' => ['type' => 'table', 'description' => 'Complete inventory data'],
                'lua_example' => "local inventory = exports.ox_inventory:getPlayerInventory()\nprint(json.encode(inventory, {indent=true}))",
                'js_example' => "const inventory = exports.ox_inventory.getPlayerInventory();\nconsole.log(JSON.stringify(inventory, null, 2));",
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
        return view('mcp.cox-function', [
            'function' => $function,
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
            'function_name' => $schema
                ->string()
                ->description('The name of the ox_inventory client function to look up (e.g., "openInventory", "SearchPlayer", "useItem", "closeInventory")')
                ->required(),
            'language' => $schema
                ->string()
                ->enum(['lua', 'js'])
                ->description('The scripting language for code examples')
                ->default('lua'),
        ];
    }
}
