<?php

namespace App\Mcp\Tools\COX\Inventory\Server;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Look up ox_inventory server functions by name. Returns function signature, parameters, return type, and usage examples for server-side inventory management.')]
class GetInventoryServerFunction extends Tool
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
            return Response::text(sprintf("ox_inventory server function '%s' not found. Check ox_inventory documentation at https://coxdocs.dev/ox_inventory", $functionName));
        }

        $output = $this->formatFunctionInfo($function, $language);

        return Response::text($output);
    }

    /**
     * Find an ox_inventory server function.
     */
    protected function findFunction(string $name): ?array
    {
        $functions = [
            'AddItem' => [
                'namespace' => 'Server',
                'description' => 'Adds an item into the specified inventory. Should be used alongside CanCarryItem.',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'inv', 'type' => 'table|string|number', 'description' => 'Inventory unique id, playerId, or table with id and owner'],
                    ['name' => 'item', 'type' => 'string', 'description' => 'Name of the item to add'],
                    ['name' => 'count', 'type' => 'number', 'description' => 'Number of items to add'],
                    ['name' => 'metadata', 'type' => 'table|string|nil', 'description' => 'Unique data to attach to item. String creates table with "type" field'],
                    ['name' => 'slot', 'type' => 'number|nil', 'description' => 'Specific slot to add item to'],
                    ['name' => 'cb', 'type' => 'function|nil', 'description' => 'Callback function(success, response)'],
                ],
                'returns' => ['type' => 'boolean, string|table', 'description' => 'success and response (or via callback)'],
                'lua_example' => "local success, response = exports.ox_inventory:AddItem('gloveVGH283', 'bread', 4)\nif not success then\n    return print(response) -- 'invalid_item', 'invalid_inventory', 'inventory_full'\nend\nprint(json.encode(response, {indent=true}))",
                'js_example' => "const [success, response] = exports.ox_inventory.AddItem('gloveVGH283', 'bread', 4);\nif (!success) {\n    return console.log(response);\n}\nconsole.log(JSON.stringify(response, null, 2));",
            ],
            'RemoveItem' => [
                'namespace' => 'Server',
                'description' => 'Removes the specified item from the specified inventory',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'inv', 'type' => 'table|string|number', 'description' => 'Inventory unique id, playerId, or table'],
                    ['name' => 'item', 'type' => 'string', 'description' => 'Name of the item to remove'],
                    ['name' => 'count', 'type' => 'number', 'description' => 'Number of items to remove'],
                    ['name' => 'metadata', 'type' => 'table|string|nil', 'description' => 'Only remove items with matching metadata'],
                    ['name' => 'slot', 'type' => 'number|nil', 'description' => 'Specific slot to remove from'],
                    ['name' => 'ignoreTotal', 'type' => 'boolean|nil', 'description' => 'Removes as many as possible up to count'],
                    ['name' => 'strict', 'type' => 'boolean|nil', 'description' => 'Exact match on metadata (default true)'],
                ],
                'returns' => ['type' => 'boolean, string', 'description' => 'success and optional error message'],
                'lua_example' => "local success = exports.ox_inventory:RemoveItem('gloveVGH283', 'water', 2)\nif not success then\n    print('Failed to remove item')\nend",
                'js_example' => "const success = exports.ox_inventory.RemoveItem('gloveVGH283', 'water', 2);\nif (!success) {\n    console.log('Failed to remove item');\n}",
            ],
            'GetItem' => [
                'namespace' => 'Server',
                'description' => 'Returns generic item data from the specified inventory with total count',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'inv', 'type' => 'table|string|number', 'description' => 'Inventory identifier'],
                    ['name' => 'item', 'type' => 'table|string', 'description' => 'Item name or array of item names'],
                    ['name' => 'metadata', 'type' => 'any|nil', 'description' => 'Only count items matching metadata'],
                    ['name' => 'returnsCount', 'type' => 'boolean|nil', 'description' => 'If true, returns count only'],
                ],
                'returns' => ['type' => 'table|number', 'description' => 'Item data with count, or just count if returnsCount=true'],
                'lua_example' => "local item = ox_inventory:GetItem(source, 'water', nil, false)\nprint(json.encode(item, {indent=true}))",
                'js_example' => "const item = ox_inventory.GetItem(source, 'water', null, false);\nconsole.log(JSON.stringify(item, null, 2));",
            ],
            'CanCarryItem' => [
                'namespace' => 'Server',
                'description' => 'Returns true or false depending if inventory can carry the specified item. Checks weight and slots.',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'inv', 'type' => 'table|string|number', 'description' => 'Inventory identifier'],
                    ['name' => 'item', 'type' => 'table|string', 'description' => 'Item name or array of items'],
                    ['name' => 'count', 'type' => 'number', 'description' => 'Number of items'],
                    ['name' => 'metadata', 'type' => 'table|string|nil', 'description' => 'Item metadata'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'True if can carry, false otherwise'],
                'lua_example' => "if exports.ox_inventory:CanCarryItem(source, 'water', 3) then\n    -- Do stuff if can carry\nelse\n    -- Do stuff if can't carry\nend",
                'js_example' => "if (exports.ox_inventory.CanCarryItem(source, 'water', 3)) {\n    // Do stuff if can carry\n} else {\n    // Do stuff if can't carry\n}",
            ],
            'GetInventory' => [
                'namespace' => 'Server',
                'description' => 'Returns the inventory associated with the ID and owner. Returns null if not found.',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'inv', 'type' => 'number|table', 'description' => 'Inventory identifier'],
                    ['name' => 'owner', 'type' => 'string|boolean|nil', 'description' => 'Owner identifier'],
                ],
                'returns' => ['type' => 'table|nil', 'description' => 'Inventory data or nil'],
                'lua_example' => "local inventory = exports.ox_inventory:GetInventory('example_stash', false)\nprint(json.encode(inventory, {indent = true}))",
                'js_example' => "const inventory = exports.ox_inventory.GetInventory('example_stash', false);\nconsole.log(JSON.stringify(inventory, null, 2));",
            ],
            'RegisterStash' => [
                'namespace' => 'Server',
                'description' => 'Creates a new custom stash. Must be called before player can open the stash.',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'id', 'type' => 'string|number', 'description' => 'Stash identifier for database'],
                    ['name' => 'label', 'type' => 'string', 'description' => 'Display name when inventory is open'],
                    ['name' => 'slots', 'type' => 'number', 'description' => 'Number of slots'],
                    ['name' => 'maxWeight', 'type' => 'number', 'description' => 'Maximum weight in grams'],
                    ['name' => 'owner', 'type' => 'string|boolean|nil', 'description' => 'Owner: string=specific, true=per-player, nil=shared'],
                    ['name' => 'groups', 'type' => 'table|nil', 'description' => 'Table of jobs with minimum grades'],
                    ['name' => 'coords', 'type' => 'vector3|table|nil', 'description' => 'Coordinates for proximity check'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "exports.ox_inventory:RegisterStash('policestash', 'Police Storage', 50, 100000, false, {['police'] = 0})",
                'js_example' => "exports.ox_inventory.RegisterStash('policestash', 'Police Storage', 50, 100000, false, {police: 0});",
            ],
            'Search' => [
                'namespace' => 'Server',
                'description' => 'Searches an inventory for a specified item. Can return slots or count.',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'inv', 'type' => 'table|string|number', 'description' => 'Inventory identifier'],
                    ['name' => 'search', 'type' => 'string', 'description' => "'slots' or 'count'"],
                    ['name' => 'item', 'type' => 'table|string', 'description' => 'Item name or array of names'],
                    ['name' => 'metadata', 'type' => 'table|string|nil', 'description' => 'Item metadata filter'],
                ],
                'returns' => ['type' => 'table|number', 'description' => 'Slots data or count depending on search type'],
                'lua_example' => "local slots = exports.ox_inventory:Search(source, 'slots', 'water')\nfor k, v in pairs(slots) do\n    print(v.slot..' contains '..v.count..' water')\nend",
                'js_example' => "const slots = exports.ox_inventory.Search(source, 'slots', 'water');\nfor (const [k, v] of Object.entries(slots)) {\n    console.log(`\${v.slot} contains \${v.count} water`);\n}",
            ],
            'SetItem' => [
                'namespace' => 'Server',
                'description' => 'Sets data for a specific item slot in an inventory',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'inv', 'type' => 'table|string|number', 'description' => 'Inventory identifier'],
                    ['name' => 'slot', 'type' => 'number', 'description' => 'Slot number to modify'],
                    ['name' => 'item', 'type' => 'table', 'description' => 'Item data to set'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'Success status'],
                'lua_example' => "exports.ox_inventory:SetItem(source, 1, {name = 'water', count = 5})",
                'js_example' => "exports.ox_inventory.SetItem(source, 1, {name: 'water', count: 5});",
            ],
            'ClearInventory' => [
                'namespace' => 'Server',
                'description' => 'Removes all items from the specified inventory',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'inv', 'type' => 'table|string|number', 'description' => 'Inventory identifier'],
                    ['name' => 'filterItem', 'type' => 'string|nil', 'description' => 'Optional item name to keep'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "exports.ox_inventory:ClearInventory(source) -- Clear all items\nexports.ox_inventory:ClearInventory(source, 'id_card') -- Clear all except id_card",
                'js_example' => "exports.ox_inventory.ClearInventory(source); // Clear all items\nexports.ox_inventory.ClearInventory(source, 'id_card'); // Clear all except id_card",
            ],
            'ConfiscateInventory' => [
                'namespace' => 'Server',
                'description' => 'Temporarily removes all items from inventory and stores them for later return',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'inv', 'type' => 'table|string|number', 'description' => 'Inventory identifier'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'Success status'],
                'lua_example' => "local success = exports.ox_inventory:ConfiscateInventory(source)\nif success then\n    print('Inventory confiscated')\nend",
                'js_example' => "const success = exports.ox_inventory.ConfiscateInventory(source);\nif (success) {\n    console.log('Inventory confiscated');\n}",
            ],
            'ReturnInventory' => [
                'namespace' => 'Server',
                'description' => 'Returns previously confiscated items to the inventory',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'inv', 'type' => 'table|string|number', 'description' => 'Inventory identifier'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'Success status'],
                'lua_example' => "local success = exports.ox_inventory:ReturnInventory(source)\nif success then\n    print('Inventory returned')\nend",
                'js_example' => "const success = exports.ox_inventory.ReturnInventory(source);\nif (success) {\n    console.log('Inventory returned');\n}",
            ],
            'SetDurability' => [
                'namespace' => 'Server',
                'description' => 'Sets the durability value for an item in a specific slot',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'inv', 'type' => 'table|string|number', 'description' => 'Inventory identifier'],
                    ['name' => 'slot', 'type' => 'number', 'description' => 'Slot number'],
                    ['name' => 'durability', 'type' => 'number', 'description' => 'Durability value (0-100)'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'Success status'],
                'lua_example' => 'exports.ox_inventory:SetDurability(source, 1, 50) -- Set to 50% durability',
                'js_example' => 'exports.ox_inventory.SetDurability(source, 1, 50); // Set to 50% durability',
            ],
            'SetMetadata' => [
                'namespace' => 'Server',
                'description' => 'Updates metadata for an item in a specific slot',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'inv', 'type' => 'table|string|number', 'description' => 'Inventory identifier'],
                    ['name' => 'slot', 'type' => 'number', 'description' => 'Slot number'],
                    ['name' => 'metadata', 'type' => 'table', 'description' => 'New metadata table'],
                ],
                'returns' => ['type' => 'boolean, table', 'description' => 'Success status and updated item data'],
                'lua_example' => "local success, item = exports.ox_inventory:SetMetadata(source, 1, {serial = 'ABC123', registered = 'John Doe'})",
                'js_example' => "const [success, item] = exports.ox_inventory.SetMetadata(source, 1, {serial: 'ABC123', registered: 'John Doe'});",
            ],
            'SwapSlots' => [
                'namespace' => 'Server',
                'description' => 'Swaps items between two slots in an inventory',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'inv', 'type' => 'table|string|number', 'description' => 'Inventory identifier'],
                    ['name' => 'fromSlot', 'type' => 'number', 'description' => 'Source slot number'],
                    ['name' => 'toSlot', 'type' => 'number', 'description' => 'Destination slot number'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'Success status'],
                'lua_example' => 'exports.ox_inventory:SwapSlots(source, 1, 5) -- Swap items in slots 1 and 5',
                'js_example' => 'exports.ox_inventory.SwapSlots(source, 1, 5); // Swap items in slots 1 and 5',
            ],
            'SetMaxWeight' => [
                'namespace' => 'Server',
                'description' => 'Sets the maximum weight capacity for an inventory',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'inv', 'type' => 'table|string|number', 'description' => 'Inventory identifier'],
                    ['name' => 'maxWeight', 'type' => 'number', 'description' => 'Maximum weight in grams'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'Success status'],
                'lua_example' => 'exports.ox_inventory:SetMaxWeight(source, 50000) -- Set max weight to 50kg',
                'js_example' => 'exports.ox_inventory.SetMaxWeight(source, 50000); // Set max weight to 50kg',
            ],
            'GetItemCount' => [
                'namespace' => 'Server',
                'description' => 'Returns the total count of a specific item in an inventory',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'inv', 'type' => 'table|string|number', 'description' => 'Inventory identifier'],
                    ['name' => 'item', 'type' => 'string', 'description' => 'Item name'],
                    ['name' => 'metadata', 'type' => 'table|nil', 'description' => 'Optional metadata filter'],
                ],
                'returns' => ['type' => 'number', 'description' => 'Total count of the item'],
                'lua_example' => "local count = exports.ox_inventory:GetItemCount(source, 'water')\nprint('Player has '..count..' water')",
                'js_example' => "const count = exports.ox_inventory.GetItemCount(source, 'water');\nconsole.log(`Player has \${count} water`);",
            ],
            'GetSlotWeight' => [
                'namespace' => 'Server',
                'description' => 'Returns the weight of an item in a specific slot',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'inv', 'type' => 'table|string|number', 'description' => 'Inventory identifier'],
                    ['name' => 'slot', 'type' => 'number', 'description' => 'Slot number'],
                ],
                'returns' => ['type' => 'number', 'description' => 'Weight in grams'],
                'lua_example' => "local weight = exports.ox_inventory:GetSlotWeight(source, 1)\nprint('Slot 1 weight: '..weight..'g')",
                'js_example' => "const weight = exports.ox_inventory.GetSlotWeight(source, 1);\nconsole.log(`Slot 1 weight: \${weight}g`);",
            ],
            'CustomDrop' => [
                'namespace' => 'Server',
                'description' => 'Creates a custom drop inventory at specific coordinates',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'prefix', 'type' => 'string', 'description' => 'Prefix for drop ID'],
                    ['name' => 'items', 'type' => 'table', 'description' => 'Array of items to add'],
                    ['name' => 'coords', 'type' => 'vector3', 'description' => 'Drop coordinates'],
                    ['name' => 'slots', 'type' => 'number|nil', 'description' => 'Number of slots (default config)'],
                    ['name' => 'maxWeight', 'type' => 'number|nil', 'description' => 'Max weight (default config)'],
                    ['name' => 'model', 'type' => 'string|number|nil', 'description' => 'Model hash for drop'],
                ],
                'returns' => ['type' => 'string', 'description' => 'Drop inventory ID'],
                'lua_example' => "local dropId = exports.ox_inventory:CustomDrop('evidence', {{name='phone', count=1}}, vector3(100.0, 200.0, 30.0))",
                'js_example' => "const dropId = exports.ox_inventory.CustomDrop('evidence', [{name: 'phone', count: 1}], [100.0, 200.0, 30.0]);",
            ],
            'ConvertItems' => [
                'namespace' => 'Server',
                'description' => 'Converts old items to new items with metadata (for migration)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'playerId', 'type' => 'number', 'description' => 'Player server ID'],
                    ['name' => 'items', 'type' => 'table', 'description' => 'Array of items to convert'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'Success status'],
                'lua_example' => "local items = {{name='old_phone', count=1, info={}}}\nexports.ox_inventory:ConvertItems(source, items)",
                'js_example' => "const items = [{name: 'old_phone', count: 1, info: {}}];\nexports.ox_inventory.ConvertItems(source, items);",
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
     * @return array<string, \Illuminate\Contracts\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'function_name' => $schema
                ->string()
                ->description('The name of the ox_inventory server function to look up (e.g., "AddItem", "RemoveItem", "GetItem", "RegisterStash", "SetDurability", "SetMetadata")')
                ->required(),
            'language' => $schema
                ->string()
                ->enum(['lua', 'js'])
                ->description('The scripting language for code examples')
                ->default('lua'),
        ];
    }
}
