<?php

namespace App\Mcp\Tools\Prodigy\Server;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('prp-bridge event reference for server-side events fired by the Prodigy Studios bridge resource. Includes player load/unload, group lifecycle, medical, and UniQueue events.')]
class GetProdigyServerEventReference extends Tool
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

            return Response::text("prp-bridge server-side event '$eventName' not found. See https://docs.prodigyrp.net/prp-bridge/events.html for the full reference.");
        }

        return Response::text($this->formatEventList($events));
    }

    /**
     * Get all prp-bridge server-side events.
     */
    protected function getServerEvents(): array
    {
        return [
            // Framework
            [
                'name' => 'prp-bridge:server:playerLoad',
                'side' => 'server',
                'category' => 'Framework',
                'description' => 'Fired when a player character loads on the server.',
                'parameters' => [
                    'src' => 'number — the player server ID',
                ],
                'lua_example' => "AddEventHandler('prp-bridge:server:playerLoad', function(src)\n    print('Player ' .. src .. ' character loaded')\nend)",
                'js_example' => "on('prp-bridge:server:playerLoad', (src) => {\n    console.log(`Player \${src} character loaded`);\n});",
            ],
            [
                'name' => 'prp-bridge:server:playerUnload',
                'side' => 'server',
                'category' => 'Framework',
                'description' => 'Fired when a player character unloads on the server.',
                'parameters' => [
                    'src' => 'number — the player server ID',
                ],
                'lua_example' => "AddEventHandler('prp-bridge:server:playerUnload', function(src)\n    print('Player ' .. src .. ' character unloaded')\nend)",
                'js_example' => "on('prp-bridge:server:playerUnload', (src) => {\n    console.log(`Player \${src} character unloaded`);\n});",
            ],

            // Groups
            [
                'name' => 'prp-bridge:server:groupMemberAdded',
                'side' => 'server',
                'category' => 'Groups',
                'description' => 'Fired when a member joins a group.',
                'parameters' => [
                    'src' => 'number — the player server ID who joined',
                    'groupUuid' => 'string — unique identifier of the group',
                ],
                'lua_example' => "AddEventHandler('prp-bridge:server:groupMemberAdded', function(src, groupUuid)\n    print('Player ' .. src .. ' joined group ' .. groupUuid)\nend)",
                'js_example' => "on('prp-bridge:server:groupMemberAdded', (src, groupUuid) => {\n    console.log(`Player \${src} joined group \${groupUuid}`);\n});",
            ],
            [
                'name' => 'prp-bridge:server:groupMemberRemoved',
                'side' => 'server',
                'category' => 'Groups',
                'description' => 'Fired when a member leaves or is kicked from a group.',
                'parameters' => [
                    'src' => 'number — the player server ID who left',
                    'groupUuid' => 'string — unique identifier of the group',
                ],
                'lua_example' => "AddEventHandler('prp-bridge:server:groupMemberRemoved', function(src, groupUuid)\n    print('Player ' .. src .. ' left group ' .. groupUuid)\nend)",
                'js_example' => "on('prp-bridge:server:groupMemberRemoved', (src, groupUuid) => {\n    console.log(`Player \${src} left group \${groupUuid}`);\n});",
            ],
            [
                'name' => 'prp-bridge:server:groupDisbanded',
                'side' => 'server',
                'category' => 'Groups',
                'description' => 'Fired when a group is disbanded.',
                'parameters' => [
                    'groupUuid' => 'string — unique identifier of the disbanded group',
                ],
                'lua_example' => "AddEventHandler('prp-bridge:server:groupDisbanded', function(groupUuid)\n    print('Group ' .. groupUuid .. ' was disbanded')\nend)",
                'js_example' => "on('prp-bridge:server:groupDisbanded', (groupUuid) => {\n    console.log(`Group \${groupUuid} was disbanded`);\n});",
            ],

            // Medical
            [
                'name' => 'prp-bridge:server:revived',
                'side' => 'server',
                'category' => 'Medical',
                'description' => 'Fired when a player is revived. Source is implicit (use source/global.source).',
                'parameters' => [],
                'lua_example' => "AddEventHandler('prp-bridge:server:revived', function()\n    local src = source\n    print('Player ' .. src .. ' was revived')\nend)",
                'js_example' => "on('prp-bridge:server:revived', () => {\n    const src = global.source;\n    console.log(`Player \${src} was revived`);\n});",
            ],
            [
                'name' => 'prp-bridge:server:died',
                'side' => 'server',
                'category' => 'Medical',
                'description' => 'Fired when a player dies. Source is implicit (use source/global.source).',
                'parameters' => [],
                'lua_example' => "AddEventHandler('prp-bridge:server:died', function()\n    local src = source\n    print('Player ' .. src .. ' died')\nend)",
                'js_example' => "on('prp-bridge:server:died', () => {\n    const src = global.source;\n    console.log(`Player \${src} died`);\n});",
            ],

            // UniQueue
            [
                'name' => 'prp-bridge:uniqueue:partyDestroyed',
                'side' => 'server',
                'category' => 'UniQueue',
                'description' => 'Fired when a UniQueue party is destroyed.',
                'parameters' => [
                    'partyUuid' => 'string — unique identifier of the destroyed party',
                ],
                'lua_example' => "AddEventHandler('prp-bridge:uniqueue:partyDestroyed', function(partyUuid)\n    print('Party ' .. partyUuid .. ' was destroyed')\nend)",
                'js_example' => "on('prp-bridge:uniqueue:partyDestroyed', (partyUuid) => {\n    console.log(`Party \${partyUuid} was destroyed`);\n});",
            ],
        ];
    }

    /**
     * Format a single event for output.
     */
    protected function formatEvent(array $event): string
    {
        return view('mcp.prodigy.prodigy-event-reference', [
            'event' => $event,
        ])->render();
    }

    /**
     * Format full event listing grouped by category.
     */
    protected function formatEventList(array $events): string
    {
        $byCategory = collect($events)->groupBy('category')->toArray();

        return view('mcp.prodigy.prodigy-event-list', [
            'eventsByCategory' => $byCategory,
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
            'event_name' => $schema
                ->string()
                ->description('The prp-bridge server event name to look up (e.g. "prp-bridge:server:playerLoad"). Omit to list all server events.')
                ->default(''),
        ];
    }
}
