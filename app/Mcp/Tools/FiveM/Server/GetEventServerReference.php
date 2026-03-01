<?php

namespace App\Mcp\Tools\FiveM\Server;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Get information about FiveM server-side events including framework events (ESX, QBCore).')]
class GetEventServerReference extends Tool
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

        return Response::text(sprintf("Server event '%s' not found in the database.", $eventName));
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
            view('mcp.event-list', [
                'eventsByType' => $eventsByType,
            ])->render()
        );
    }

    /**
     * Format event information.
     */
    protected function formatEventInfo(array $event, string $language): string
    {
        return view('mcp.event-reference', [
            'event' => $event,
            'language' => $language,
        ])->render();
    }

    /**
     * Get server events database.
     */
    protected function getEventsDatabase(): array
    {
        return [
            // Core FiveM Server Events
            [
                'name' => 'onResourceStart',
                'type' => 'core',
                'side' => 'server',
                'description' => 'Triggered when a resource starts (server-side)',
                'parameters' => [
                    ['name' => 'resourceName', 'type' => 'string', 'description' => 'The name of the resource that started'],
                ],
                'lua_example' => "AddEventHandler('onResourceStart', function(resourceName)\n    if resourceName == GetCurrentResourceName() then\n        print('Resource started on server!')\n    end\nend)",
                'js_example' => "on('onResourceStart', (resourceName) => {\n    if (resourceName === GetCurrentResourceName()) {\n        console.log('Resource started on server!');\n    }\n});",
                'documentation' => 'https://docs.fivem.net/docs/scripting-reference/events/list/onResourceStart/',
            ],
            [
                'name' => 'onResourceStop',
                'type' => 'core',
                'side' => 'server',
                'description' => 'Triggered when a resource stops (server-side)',
                'parameters' => [
                    ['name' => 'resourceName', 'type' => 'string', 'description' => 'The name of the resource that stopped'],
                ],
                'lua_example' => "AddEventHandler('onResourceStop', function(resourceName)\n    if resourceName == GetCurrentResourceName() then\n        print('Resource stopped on server!')\n    end\nend)",
                'js_example' => "on('onResourceStop', (resourceName) => {\n    if (resourceName === GetCurrentResourceName()) {\n        console.log('Resource stopped on server!');\n    }\n});",
                'documentation' => 'https://docs.fivem.net/docs/scripting-reference/events/list/onResourceStop/',
            ],
            [
                'name' => 'playerConnecting',
                'type' => 'core',
                'side' => 'server',
                'description' => 'Triggered when a player is connecting to the server',
                'parameters' => [
                    ['name' => 'name', 'type' => 'string', 'description' => 'Player name'],
                    ['name' => 'setKickReason', 'type' => 'function', 'description' => 'Function to deny connection'],
                    ['name' => 'deferrals', 'type' => 'object', 'description' => 'Deferrals object for async connection'],
                ],
                'lua_example' => "AddEventHandler('playerConnecting', function(name, setKickReason, deferrals)\n    local source = source\n    deferrals.defer()\n    -- Check player\n    deferrals.done()\nend)",
                'js_example' => "on('playerConnecting', (name, setKickReason, deferrals) => {\n    const source = global.source;\n    deferrals.defer();\n    // Check player\n    deferrals.done();\n});",
                'documentation' => 'https://docs.fivem.net/docs/scripting-reference/events/list/playerConnecting/',
            ],
            [
                'name' => 'playerDropped',
                'type' => 'core',
                'side' => 'server',
                'description' => 'Triggered when a player disconnects from the server',
                'parameters' => [
                    ['name' => 'reason', 'type' => 'string', 'description' => 'Disconnect reason'],
                ],
                'lua_example' => "AddEventHandler('playerDropped', function(reason)\n    local source = source\n    print('Player ' .. GetPlayerName(source) .. ' dropped: ' .. reason)\nend)",
                'js_example' => "on('playerDropped', (reason) => {\n    const source = global.source;\n    console.log(`Player \${GetPlayerName(source)} dropped: \${reason}`);\n});",
                'documentation' => 'https://docs.fivem.net/docs/scripting-reference/events/list/playerDropped/',
            ],
            // ESX Server Events
            [
                'name' => 'esx:playerLoaded',
                'type' => 'esx',
                'side' => 'server',
                'description' => 'Triggered on server when ESX player loads',
                'parameters' => [
                    ['name' => 'xPlayer', 'type' => 'table', 'description' => 'ESX player object'],
                ],
                'lua_example' => "AddEventHandler('esx:playerLoaded', function(source, xPlayer)\n    -- Server-side player loaded\nend)",
                'js_example' => "on('esx:playerLoaded', (source, xPlayer) => {\n    // Server-side player loaded\n});",
            ],
            // QBCore Server Events
            [
                'name' => 'QBCore:Server:PlayerLoaded',
                'type' => 'qbcore',
                'side' => 'server',
                'description' => 'Triggered on server when QBCore player loads',
                'parameters' => [
                    ['name' => 'Player', 'type' => 'table', 'description' => 'QBCore player object'],
                ],
                'lua_example' => "AddEventHandler('QBCore:Server:PlayerLoaded', function(Player)\n    -- Server-side player loaded\nend)",
                'js_example' => "on('QBCore:Server:PlayerLoaded', (Player) => {\n    // Server-side player loaded\n});",
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
                ->description('Specific server event name to look up (optional, leave empty to list all events)'),
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
