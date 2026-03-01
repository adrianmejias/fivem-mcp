<?php

namespace App\Mcp\Tools\FiveM\Client;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Get information about FiveM client-side events including framework events (ESX, QBCore).')]
class GetEventClientReference extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $eventName = $request->get('event_name');
        $eventType = $request->get('event_type', 'all');
        $language = $request->get('language', 'lua');

        if ($eventName) {
            return $this->getEventInfo($eventName, $language);
        }

        return $this->listEvents($eventType, $language);
    }

    /**
     * Get specific event information.
     */
    protected function getEventInfo(string $eventName, string $language): Response
    {
        $events = $this->getEventsDatabase();

        foreach ($events as $event) {
            if (strtolower($event['name']) === strtolower($eventName)) {
                return Response::text($this->formatEventInfo($event, $language));
            }
        }

        return Response::text(sprintf("Client event '%s' not found in the database.", $eventName));
    }

    /**
     * List events by type.
     */
    protected function listEvents(string $type, string $language): Response
    {
        $events = $this->getEventsDatabase();

        if ($type !== 'all') {
            $events = array_filter($events, fn ($e) => $e['type'] === $type);
        }

        $eventsByType = [];
        foreach ($events as $event) {
            $eventsByType[$event['type']][] = $event;
        }

        return Response::text(
            view('mcp.fivem.event-list', [
                'eventsByType' => $eventsByType,
            ])->render()
        );
    }

    /**
     * Format event information.
     */
    protected function formatEventInfo(array $event, string $language): string
    {
        return view('mcp.fivem.event-reference', [
            'event' => $event,
            'language' => $language,
        ])->render();
    }

    /**
     * Get client events database.
     */
    protected function getEventsDatabase(): array
    {
        return [
            // Core FiveM Client Events
            [
                'name' => 'onResourceStart',
                'type' => 'core',
                'side' => 'client',
                'description' => 'Triggered when a resource starts (client-side)',
                'parameters' => [
                    ['name' => 'resourceName', 'type' => 'string', 'description' => 'The name of the resource that started'],
                ],
                'lua_example' => "AddEventHandler('onResourceStart', function(resourceName)\n    if resourceName == GetCurrentResourceName() then\n        print('Resource started on client!')\n    end\nend)",
                'js_example' => "on('onResourceStart', (resourceName) => {\n    if (resourceName === GetCurrentResourceName()) {\n        console.log('Resource started on client!');\n    }\n});",
                'documentation' => 'https://docs.fivem.net/docs/scripting-reference/events/list/onResourceStart/',
            ],
            [
                'name' => 'onResourceStop',
                'type' => 'core',
                'side' => 'client',
                'description' => 'Triggered when a resource stops (client-side)',
                'parameters' => [
                    ['name' => 'resourceName', 'type' => 'string', 'description' => 'The name of the resource that stopped'],
                ],
                'lua_example' => "AddEventHandler('onResourceStop', function(resourceName)\n    if resourceName == GetCurrentResourceName() then\n        print('Resource stopped on client!')\n    end\nend)",
                'js_example' => "on('onResourceStop', (resourceName) => {\n    if (resourceName === GetCurrentResourceName()) {\n        console.log('Resource stopped on client!');\n    }\n});",
                'documentation' => 'https://docs.fivem.net/docs/scripting-reference/events/list/onResourceStop/',
            ],
            // ESX Client Events
            [
                'name' => 'esx:playerLoaded',
                'type' => 'esx',
                'side' => 'client',
                'description' => 'Triggered when ESX player data is loaded on client',
                'parameters' => [
                    ['name' => 'xPlayer', 'type' => 'table', 'description' => 'ESX player object'],
                ],
                'lua_example' => "RegisterNetEvent('esx:playerLoaded', function(xPlayer)\n    ESX.PlayerData = xPlayer\n    -- Player is loaded\nend)",
                'js_example' => "RegisterNetEvent('esx:playerLoaded', (xPlayer) => {\n    ESX.PlayerData = xPlayer;\n    // Player is loaded\n});",
            ],
            [
                'name' => 'esx:setJob',
                'type' => 'esx',
                'side' => 'client',
                'description' => 'Triggered when player job changes',
                'parameters' => [
                    ['name' => 'job', 'type' => 'table', 'description' => 'New job object'],
                ],
                'lua_example' => "RegisterNetEvent('esx:setJob', function(job)\n    ESX.PlayerData.job = job\n    print('Job changed to: ' .. job.name)\nend)",
                'js_example' => "RegisterNetEvent('esx:setJob', (job) => {\n    ESX.PlayerData.job = job;\n    console.log(`Job changed to: \${job.name}`);\n});",
            ],
            // QBCore Client Events
            [
                'name' => 'QBCore:Client:OnPlayerLoaded',
                'type' => 'qbcore',
                'side' => 'client',
                'description' => 'Triggered when QBCore player data is loaded on client',
                'parameters' => [],
                'lua_example' => "RegisterNetEvent('QBCore:Client:OnPlayerLoaded', function()\n    local PlayerData = QBCore.Functions.GetPlayerData()\n    -- Player is loaded\nend)",
                'js_example' => "RegisterNetEvent('QBCore:Client:OnPlayerLoaded', () => {\n    const PlayerData = QBCore.Functions.GetPlayerData();\n    // Player is loaded\n});",
            ],
            [
                'name' => 'QBCore:Client:OnJobUpdate',
                'type' => 'qbcore',
                'side' => 'client',
                'description' => 'Triggered when player job changes in QBCore',
                'parameters' => [
                    ['name' => 'job', 'type' => 'table', 'description' => 'New job object'],
                ],
                'lua_example' => "RegisterNetEvent('QBCore:Client:OnJobUpdate', function(job)\n    PlayerData.job = job\n    print('Job changed to: ' .. job.name)\nend)",
                'js_example' => "RegisterNetEvent('QBCore:Client:OnJobUpdate', (job) => {\n    PlayerData.job = job;\n    console.log(`Job changed to: \${job.name}`);\n});",
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
                ->description('Specific client event name to look up (optional, leave empty to list all events)'),
            'event_type' => $schema
                ->string()
                ->enum(['all', 'core', 'esx', 'qbcore'])
                ->description('Filter events by type')
                ->default('all'),
            'language' => $schema
                ->string()
                ->enum(['lua', 'js'])
                ->description('The scripting language for code examples')
                ->default('lua'),
        ];
    }
}
