<?php

namespace App\Mcp\Tools\Prodigy\Client;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('prp-bridge event reference for client-side events fired by the Prodigy Studios bridge resource. Includes notifications, sound, callbacks, allowlist, and medical events.')]
class GetProdigyClientEventReference extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $eventName = $request->get('event_name', '');

        $events = $this->getClientEvents();

        if ($eventName) {
            $event = collect($events)->firstWhere('name', $eventName);
            if ($event) {
                return Response::text($this->formatEvent($event));
            }

            return Response::text("prp-bridge client-side event '$eventName' not found. See https://docs.prodigyrp.net/prp-bridge/events.html for the full reference.");
        }

        return Response::text($this->formatEventList($events));
    }

    /**
     * Get all prp-bridge client-side events.
     */
    protected function getClientEvents(): array
    {
        return [
            // Core — Notifications & Sound
            [
                'name' => 'prp-bridge:notify',
                'side' => 'client',
                'category' => 'Core — Notifications & Sound',
                'description' => 'Sends a notification to the player. Trigger from server via bridge.fw.notify. Type: success, error, inform.',
                'parameters' => [
                    'type' => 'Notification type: "success", "error", or "inform"',
                    'message' => 'The notification body text',
                    'title' => 'Optional title text',
                    'duration' => 'Duration in milliseconds (optional)',
                ],
                'lua_example' => "-- Trigger from client\nTriggerEvent('prp-bridge:notify', 'success', 'You sold the item!', 'Shop', 5000)\n\n-- Trigger from server\nTriggerClientEvent('prp-bridge:notify', source, 'success', 'You sold the item!', 'Shop', 5000)",
                'js_example' => "// Trigger from client\nemit('prp-bridge:notify', 'success', 'You sold the item!', 'Shop', 5000);\n\n// Trigger from server\nemitNet('prp-bridge:notify', source, 'success', 'You sold the item!', 'Shop', 5000);",
            ],
            [
                'name' => 'prp-bridge:sound:play',
                'side' => 'client',
                'category' => 'Core — Notifications & Sound',
                'description' => 'Plays a sound on the client.',
                'parameters' => [
                    'fileName' => 'Sound file name',
                    'volume' => 'Volume level (0.0 – 1.0)',
                    'noAutoPath' => 'Set true to use a fully qualified path instead of the automatic one',
                    'resourceName' => 'Resource that owns the sound file',
                ],
                'lua_example' => "TriggerEvent('prp-bridge:sound:play', 'cash_register', 0.5, false, 'my-resource')",
                'js_example' => "emit('prp-bridge:sound:play', 'cash_register', 0.5, false, 'my-resource');",
            ],
            [
                'name' => 'prp-bridge:sound:playSpatial',
                'side' => 'client',
                'category' => 'Core — Notifications & Sound',
                'description' => 'Plays a 3D spatial sound at world coordinates.',
                'parameters' => [
                    'fileName' => 'Sound file name',
                    'volume' => 'Volume level (0.0 – 1.0)',
                    'coords' => 'vector3 world position',
                    'distance' => 'Max audible distance',
                    'shouldMuffle' => 'Whether to apply interior muffling',
                    'noAutoPath' => 'Set true to use a fully qualified path',
                    'resourceName' => 'Resource that owns the sound file',
                ],
                'lua_example' => "TriggerEvent('prp-bridge:sound:playSpatial', 'explosion', 1.0, vector3(100.0, 200.0, 30.0), 50.0, false, false, 'my-resource')",
                'js_example' => "emit('prp-bridge:sound:playSpatial', 'explosion', 1.0, [100.0, 200.0, 30.0], 50.0, false, false, 'my-resource');",
            ],

            // Core — Callbacks
            [
                'name' => 'prp-bridge:progress',
                'side' => 'client',
                'category' => 'Core — Callbacks',
                'description' => 'Progress bar callback. Invoked via lib.callback from the server.',
                'parameters' => [],
                'lua_example' => "-- Invoked internally via lib.callback from the server\n-- Use lib.callback.await on the server to trigger it\nlib.callback('prp-bridge:progress', source, function(result)\n    print('Progress result:', result)\nend, { duration = 5000, label = 'Processing...' })",
                'js_example' => '// Invoked internally via lib.callback from the server',
            ],
            [
                'name' => 'prp-bridge:minigame',
                'side' => 'client',
                'category' => 'Core — Callbacks',
                'description' => 'Minigame callback. Invoked via lib.callback from the server.',
                'parameters' => [],
                'lua_example' => "-- Invoked internally via lib.callback from the server\nlib.callback('prp-bridge:minigame', source, function(success)\n    if success then\n        print('Minigame passed!')\n    end\nend, { type = 'circle', difficulty = 'easy' })",
                'js_example' => '// Invoked internally via lib.callback from the server',
            ],
            [
                'name' => 'prp-bridge:confirmDialog',
                'side' => 'client',
                'category' => 'Core — Callbacks',
                'description' => 'Confirm dialog callback. Invoked via lib.callback from the server.',
                'parameters' => [],
                'lua_example' => "-- Invoked internally via lib.callback from the server\nlib.callback('prp-bridge:confirmDialog', source, function(confirmed)\n    if confirmed then\n        print('Player confirmed!')\n    end\nend, { title = 'Are you sure?', content = 'This cannot be undone.' })",
                'js_example' => '// Invoked internally via lib.callback from the server',
            ],
            [
                'name' => 'prp-bridge:inputDialog',
                'side' => 'client',
                'category' => 'Core — Callbacks',
                'description' => 'Input dialog callback. Invoked via lib.callback from the server.',
                'parameters' => [],
                'lua_example' => "-- Invoked internally via lib.callback from the server\nlib.callback('prp-bridge:inputDialog', source, function(input)\n    print('Player entered:', input)\nend, { title = 'Enter value', inputs = { { type = 'input', label = 'Name' } } })",
                'js_example' => '// Invoked internally via lib.callback from the server',
            ],
            [
                'name' => 'prp-bridge:placeProp',
                'side' => 'client',
                'category' => 'Core — Callbacks',
                'description' => 'Prop placer callback. Opens the prop placement UI. Invoked via lib.callback from the server.',
                'parameters' => [],
                'lua_example' => "-- Invoked internally via lib.callback from the server\nlib.callback('prp-bridge:placeProp', source, function(coords)\n    if coords then\n        print('Prop placed at:', coords)\n    end\nend, { model = 'prop_barrier_work05a' })",
                'js_example' => '// Invoked internally via lib.callback from the server',
            ],

            // Allowlist
            [
                'name' => 'prp-bridge:client:updateAllowlist',
                'side' => 'client',
                'category' => 'Allowlist',
                'description' => 'Fired when the player\'s allowlists are updated or loaded.',
                'parameters' => [
                    'allowlists' => 'table<string, boolean> — map of allowlist name to whether the player has it',
                ],
                'lua_example' => "AddEventHandler('prp-bridge:client:updateAllowlist', function(allowlists)\n    if allowlists['my_allowlist'] then\n        print('Player has my_allowlist!')\n    end\nend)",
                'js_example' => "on('prp-bridge:client:updateAllowlist', (allowlists) => {\n    if (allowlists['my_allowlist']) {\n        console.log('Player has my_allowlist!');\n    }\n});",
            ],

            // Medical
            [
                'name' => 'prp-bridge:client:revived',
                'side' => 'client',
                'category' => 'Medical',
                'description' => 'Fired when the local player is revived.',
                'parameters' => [],
                'lua_example' => "AddEventHandler('prp-bridge:client:revived', function()\n    print('Player was revived!')\nend)",
                'js_example' => "on('prp-bridge:client:revived', () => {\n    console.log('Player was revived!');\n});",
            ],
            [
                'name' => 'prp-bridge:client:died',
                'side' => 'client',
                'category' => 'Medical',
                'description' => 'Fired when the local player dies.',
                'parameters' => [],
                'lua_example' => "AddEventHandler('prp-bridge:client:died', function()\n    print('Player died!')\nend)",
                'js_example' => "on('prp-bridge:client:died', () => {\n    console.log('Player died!');\n});",
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
                ->description('The prp-bridge client event name to look up (e.g. "prp-bridge:notify"). Omit to list all client events.')
                ->default(''),
        ];
    }
}
