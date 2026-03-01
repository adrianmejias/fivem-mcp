<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Get information about QBCore framework events including player, job, inventory, and vehicle events.')]
class GetQBCoreEventReference extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $eventName = $request->get('event_name');
        $eventCategory = $request->get('event_category', 'all');
        $language = $request->get('language', 'lua');

        if ($eventName) {
            return $this->getEventInfo($eventName, $language);
        }

        return $this->listEvents($eventCategory, $language);
    }

    /**
     * Get specific event information.
     */
    protected function getEventInfo(string $eventName, string $language): Response
    {
        $events = $this->getQBCoreEventsDatabase();

        foreach ($events as $event) {
            if (strtolower($event['name']) === strtolower($eventName)) {
                return Response::text($this->formatEventInfo($event, $language));
            }
        }

        return Response::text(sprintf("QBCore event '%s' not found in the database.", $eventName));
    }

    /**
     * List events by category.
     */
    protected function listEvents(string $category, string $language): Response
    {
        $events = $this->getQBCoreEventsDatabase();

        if ($category !== 'all') {
            $events = array_filter($events, fn ($e) => $e['category'] === $category);
        }

        $eventsByCategory = [];
        foreach ($events as $event) {
            $eventsByCategory[$event['category']][] = $event;
        }

        return Response::text(
            view('mcp.qbcore-event-list', [
                'eventsByCategory' => $eventsByCategory,
            ])->render()
        );
    }

    /**
     * Format event information.
     */
    protected function formatEventInfo(array $event, string $language): string
    {
        return view('mcp.qbcore-event-reference', [
            'event' => $event,
            'language' => $language,
        ])->render();
    }

    /**
     * Get QBCore events database.
     */
    protected function getQBCoreEventsDatabase(): array
    {
        return [
            // Player Events
            [
                'name' => 'QBCore:Client:OnPlayerLoaded',
                'category' => 'player',
                'side' => 'client',
                'description' => 'Triggered when a player\'s data is loaded on the client.',
                'parameters' => [],
                'lua_example' => "RegisterNetEvent('QBCore:Client:OnPlayerLoaded', function()\n    local PlayerData = QBCore.Functions.GetPlayerData()\n    print('Player loaded: ' .. PlayerData.charinfo.firstname)\nend)",
                'js_example' => "RegisterNetEvent('QBCore:Client:OnPlayerLoaded', () => {\n    const PlayerData = QBCore.Functions.GetPlayerData();\n    console.log('Player loaded: ' + PlayerData.charinfo.firstname);\n});",
                'documentation' => 'https://docs.qbcore.org/qbcore-documentation/core-concepts/player',
            ],
            [
                'name' => 'QBCore:Client:OnPlayerUnload',
                'category' => 'player',
                'side' => 'client',
                'description' => 'Triggered when a player\'s data is unloaded (before character switch or logout).',
                'parameters' => [],
                'lua_example' => "RegisterNetEvent('QBCore:Client:OnPlayerUnload', function()\n    print('Player unloaded')\nend)",
                'js_example' => "RegisterNetEvent('QBCore:Client:OnPlayerUnload', () => {\n    console.log('Player unloaded');\n});",
                'documentation' => 'https://docs.qbcore.org/qbcore-documentation/core-concepts/player',
            ],
            [
                'name' => 'QBCore:Server:OnPlayerLoaded',
                'category' => 'player',
                'side' => 'server',
                'description' => 'Triggered on the server when a player\'s data is successfully loaded.',
                'parameters' => [
                    ['name' => 'source', 'type' => 'int', 'description' => 'The player server ID'],
                ],
                'lua_example' => "RegisterNetEvent('QBCore:Server:OnPlayerLoaded', function()\n    local Player = QBCore.Functions.GetPlayer(source)\n    if Player then\n        print('Server: Player loaded - ' .. Player.PlayerData.charinfo.firstname)\n    end\nend)",
                'js_example' => "RegisterNetEvent('QBCore:Server:OnPlayerLoaded', () => {\n    const Player = QBCore.Functions.GetPlayer(global.source);\n    if (Player) {\n        console.log('Server: Player loaded - ' + Player.PlayerData.charinfo.firstname);\n    }\n});",
                'documentation' => 'https://docs.qbcore.org/qbcore-documentation/core-concepts/player',
            ],

            // Job Events
            [
                'name' => 'QBCore:Client:OnJobUpdate',
                'category' => 'job',
                'side' => 'client',
                'description' => 'Triggered when a player\'s job is updated.',
                'parameters' => [
                    ['name' => 'job', 'type' => 'table', 'description' => 'The job data table'],
                ],
                'lua_example' => "RegisterNetEvent('QBCore:Client:OnJobUpdate', function(job)\n    PlayerData.job = job\n    print('Job changed to: ' .. job.name .. ' - Grade: ' .. job.grade.name)\nend)",
                'js_example' => "RegisterNetEvent('QBCore:Client:OnJobUpdate', (job) => {\n    PlayerData.job = job;\n    console.log(`Job changed to: \${job.name} - Grade: \${job.grade.name}`);\n});",
                'documentation' => 'https://docs.qbcore.org/qbcore-documentation/core-concepts/jobs',
            ],
            [
                'name' => 'QBCore:Server:SetJob',
                'category' => 'job',
                'side' => 'server',
                'description' => 'Set a player\'s job on the server (requires permission).',
                'parameters' => [
                    ['name' => 'source', 'type' => 'int', 'description' => 'The player server ID'],
                    ['name' => 'job', 'type' => 'string', 'description' => 'The job name'],
                    ['name' => 'grade', 'type' => 'int', 'description' => 'The job grade level'],
                ],
                'lua_example' => "TriggerServerEvent('QBCore:Server:SetJob', source, 'police', 2)",
                'js_example' => "TriggerServerEvent('QBCore:Server:SetJob', global.source, 'police', 2);",
                'documentation' => 'https://docs.qbcore.org/qbcore-documentation/core-concepts/jobs',
            ],

            // Inventory Events
            [
                'name' => 'QBCore:Client:OnItemAdd',
                'category' => 'inventory',
                'side' => 'client',
                'description' => 'Triggered when an item is added to the player inventory.',
                'parameters' => [
                    ['name' => 'itemName', 'type' => 'string', 'description' => 'The item name'],
                    ['name' => 'amount', 'type' => 'int', 'description' => 'The amount added'],
                ],
                'lua_example' => "RegisterNetEvent('QBCore:Client:OnItemAdd', function(itemName, amount)\n    print('Added ' .. amount .. 'x ' .. itemName)\nend)",
                'js_example' => "RegisterNetEvent('QBCore:Client:OnItemAdd', (itemName, amount) => {\n    console.log(`Added \${amount}x \${itemName}`);\n});",
                'documentation' => 'https://docs.qbcore.org/qbcore-documentation/core-concepts/items',
            ],
            [
                'name' => 'QBCore:Client:OnItemRemove',
                'category' => 'inventory',
                'side' => 'client',
                'description' => 'Triggered when an item is removed from the player inventory.',
                'parameters' => [
                    ['name' => 'itemName', 'type' => 'string', 'description' => 'The item name'],
                    ['name' => 'amount', 'type' => 'int', 'description' => 'The amount removed'],
                ],
                'lua_example' => "RegisterNetEvent('QBCore:Client:OnItemRemove', function(itemName, amount)\n    print('Removed ' .. amount .. 'x ' .. itemName)\nend)",
                'js_example' => "RegisterNetEvent('QBCore:Client:OnItemRemove', (itemName, amount) => {\n    console.log(`Removed \${amount}x \${itemName}`);\n});",
                'documentation' => 'https://docs.qbcore.org/qbcore-documentation/core-concepts/items',
            ],
            [
                'name' => 'QBCore:Server:AddItem',
                'category' => 'inventory',
                'side' => 'server',
                'description' => 'Add an item to a player\'s inventory.',
                'parameters' => [
                    ['name' => 'source', 'type' => 'int', 'description' => 'The player server ID'],
                    ['name' => 'item', 'type' => 'string', 'description' => 'The item name'],
                    ['name' => 'amount', 'type' => 'int', 'description' => 'The amount to add'],
                    ['name' => 'slot', 'type' => 'int', 'description' => 'Optional inventory slot'],
                    ['name' => 'info', 'type' => 'table', 'description' => 'Optional item info'],
                ],
                'lua_example' => "exports['qb-core']:GetPlayer(source):AddItem('water', 1)",
                'js_example' => "exports['qb-core']:GetPlayer(global.source).AddItem('water', 1);",
                'documentation' => 'https://docs.qbcore.org/qbcore-documentation/core-concepts/items',
            ],

            // Vehicle Events
            [
                'name' => 'QBCore:Server:AddVehicle',
                'category' => 'vehicle',
                'side' => 'server',
                'description' => 'Add a vehicle to a player\'s garage.',
                'parameters' => [
                    ['name' => 'source', 'type' => 'int', 'description' => 'The player server ID'],
                    ['name' => 'modelName', 'type' => 'string', 'description' => 'The vehicle model name'],
                    ['name' => 'plate', 'type' => 'string', 'description' => 'The vehicle license plate'],
                ],
                'lua_example' => "TriggerServerEvent('QBCore:Server:AddVehicle', 'adder', 'QBTEST123')",
                'js_example' => "TriggerServerEvent('QBCore:Server:AddVehicle', 'adder', 'QBTEST123');",
                'documentation' => 'https://docs.qbcore.org/qbcore-documentation/core-concepts/vehicles',
            ],
            [
                'name' => 'QBCore:Server:DeleteVehicle',
                'category' => 'vehicle',
                'side' => 'server',
                'description' => 'Remove a vehicle from a player\'s garage.',
                'parameters' => [
                    ['name' => 'source', 'type' => 'int', 'description' => 'The player server ID'],
                    ['name' => 'plate', 'type' => 'string', 'description' => 'The vehicle license plate'],
                ],
                'lua_example' => "TriggerServerEvent('QBCore:Server:DeleteVehicle', 'QBTEST123')",
                'js_example' => "TriggerServerEvent('QBCore:Server:DeleteVehicle', 'QBTEST123');",
                'documentation' => 'https://docs.qbcore.org/qbcore-documentation/core-concepts/vehicles',
            ],

            // Money Events
            [
                'name' => 'QBCore:Client:OnMoneyChange',
                'category' => 'money',
                'side' => 'client',
                'description' => 'Triggered when a player\'s money amount changes.',
                'parameters' => [
                    ['name' => 'type', 'type' => 'string', 'description' => 'Money type (cash, bank, etc.)'],
                    ['name' => 'amount', 'type' => 'int', 'description' => 'The new amount'],
                    ['name' => 'difference', 'type' => 'int', 'description' => 'The amount changed'],
                ],
                'lua_example' => "RegisterNetEvent('QBCore:Client:OnMoneyChange', function(type, amount, difference)\n    print('Money changed: ' .. type .. ' = ' .. amount)\nend)",
                'js_example' => "RegisterNetEvent('QBCore:Client:OnMoneyChange', (type, amount, difference) => {\n    console.log(`Money changed: \${type} = \${amount}`);\n});",
                'documentation' => 'https://docs.qbcore.org/qbcore-documentation/core-concepts/player',
            ],
            [
                'name' => 'QBCore:Server:AddMoney',
                'category' => 'money',
                'side' => 'server',
                'description' => 'Add money to a player\'s account.',
                'parameters' => [
                    ['name' => 'source', 'type' => 'int', 'description' => 'The player server ID'],
                    ['name' => 'moneyType', 'type' => 'string', 'description' => 'Money type (cash, bank)'],
                    ['name' => 'amount', 'type' => 'int', 'description' => 'The amount to add'],
                    ['name' => 'reason', 'type' => 'string', 'description' => 'Optional reason for log'],
                ],
                'lua_example' => "exports['qb-core']:AddMoney(source, 'cash', 500, 'Job Payment')",
                'js_example' => "exports['qb-core']:AddMoney(global.source, 'cash', 500, 'Job Payment');",
                'documentation' => 'https://docs.qbcore.org/qbcore-documentation/core-concepts/player',
            ],
        ];
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\Contracts\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'event_name' => $schema
                ->string()
                ->description('Specific QBCore event name to look up (optional, leave empty to list all events)'),
            'event_category' => $schema
                ->string()
                ->enum(['all', 'player', 'job', 'inventory', 'vehicle', 'money'])
                ->description('Filter events by category')
                ->default('all'),
            'language' => $schema
                ->string()
                ->enum(['lua', 'js'])
                ->description('The scripting language for code examples')
                ->default('lua'),
        ];
    }
}
