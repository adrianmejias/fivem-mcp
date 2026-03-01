<?php

namespace App\Mcp\Tools\QBCore\Server;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('QBCore event reference for server-side events. Includes built-in core events and framework-specific events for server scripts.')]
class GetQBCoreServerEventReference extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $eventName = $request->get('event_name', '');

        $events = $this->getServerEvents();

        if ($eventName) {
            $event = collect($events)->firstWhere('name', $eventName);
            if ($event) {
                return Response::text($this->formatEvent($event));
            }

            return Response::text("Server-side event '$eventName' not found.");
        }

        return Response::text($this->formatEventList($events, 'QBCore Server Events'));
    }

    /**
     * Get all server-side events.
     */
    protected function getServerEvents(): array
    {
        return [
            [
                'name' => 'QBCore:Server:PlayerLoaded',
                'side' => 'server',
                'description' => 'Fired when a player fully loads on the server',
                'parameters' => ['source' => 'Player server ID'],
                'lua_example' => "AddEventHandler('QBCore:Server:PlayerLoaded', function(source)\n    print('Player ' .. source .. ' loaded')\nend)",
                'js_example' => "AddEventHandler('QBCore:Server:PlayerLoaded', function(source) {\n    console.log('Player ' + source + ' loaded');\n});",
            ],
            [
                'name' => 'QBCore:Server:playerDropped',
                'side' => 'server',
                'description' => 'Fired when a player disconnects from the server',
                'parameters' => ['source' => 'Player server ID', 'reason' => 'Disconnect reason string'],
                'lua_example' => "AddEventHandler('QBCore:Server:playerDropped', function(source, reason)\n    print('Player ' .. source .. ' dropped: ' .. reason)\nend)",
                'js_example' => "AddEventHandler('QBCore:Server:playerDropped', function(source, reason) {\n    console.log('Player ' + source + ' dropped: ' + reason);\n});",
            ],
            [
                'name' => 'QBCore:Server:PlayerDataChanged',
                'side' => 'server',
                'description' => 'Fired when a player\'s data changes (job, money, items, etc)',
                'parameters' => ['source' => 'Player server ID', 'dataName' => 'Name of changed data'],
                'lua_example' => "AddEventHandler('QBCore:Server:PlayerDataChanged', function(source, dataName)\n    if dataName == 'money' then\n        print('Player money changed')\n    end\nend)",
                'js_example' => "AddEventHandler('QBCore:Server:PlayerDataChanged', function(source, dataName) {\n    if (dataName === 'money') {\n        console.log('Player money changed');\n    }\n});",
            ],
            [
                'name' => 'QBCore:Server:OnJobUpdate',
                'side' => 'server',
                'description' => 'Fired when a player\'s job is updated',
                'parameters' => ['source' => 'Player server ID', 'job' => 'New job table'],
                'lua_example' => "AddEventHandler('QBCore:Server:OnJobUpdate', function(source, job)\n    print('Player job: ' .. job.name)\nend)",
                'js_example' => "AddEventHandler('QBCore:Server:OnJobUpdate', function(source, job) {\n    console.log('Player job: ' + job.name);\n});",
            ],
            [
                'name' => 'QBCore:Server:SyncPlayerData',
                'side' => 'server',
                'description' => 'Sync player data across server (triggered internally)',
                'parameters' => ['source' => 'Player server ID'],
                'lua_example' => "TriggerEvent('QBCore:Server:SyncPlayerData', source)",
                'js_example' => "TriggerEvent('QBCore:Server:SyncPlayerData', global.source);",
            ],
            [
                'name' => 'QBCore:Server:SavePlayer',
                'side' => 'server',
                'description' => 'Save a player\'s data to database',
                'parameters' => ['source' => 'Player server ID'],
                'lua_example' => "TriggerEvent('QBCore:Server:SavePlayer', source)",
                'js_example' => "TriggerEvent('QBCore:Server:SavePlayer', global.source);",
            ],
        ];
    }

    /**
     * Format a single event.
     */
    protected function formatEvent(array $event): string
    {
        return view('mcp.qbcore.qbcore-event-reference', [
            'event' => $event,
        ])->render();
    }

    /**
     * Format event list.
     */
    protected function formatEventList(array $events, string $title): string
    {
        $list = sprintf("# %s\n\n", $title);

        foreach ($events as $event) {
            $list .= sprintf("- **%s**: %s\n", $event['name'], $event['description']);
        }

        return $list;
    }

    /**
     * Format parameters list.
     */
    protected function formatParameters(array $parameters): string
    {
        $formatted = '';
        foreach ($parameters as $name => $description) {
            $formatted .= sprintf("- `%s`: %s\n", $name, $description);
        }

        return $formatted;
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
                ->description('Specific event name to look up, or leave empty to list all server events')
                ->default(''),
        ];
    }
}
