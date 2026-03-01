<?php

namespace App\Mcp\Tools\COX\Doorlock\Server;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Look up ox_doorlock server events by name. Returns event signature, parameters, and usage examples for server-side door events.')]
class GetOxDoorlockServerEvent extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $eventName = $request->get('event_name');
        $language = $request->get('language', 'lua');

        $event = $this->findEvent($eventName);

        if (! $event) {
            return Response::text(sprintf("ox_doorlock server event '%s' not found. Check ox_doorlock documentation at https://coxdocs.dev/ox_doorlock", $eventName));
        }

        $output = $this->formatEventInfo($event, $language);

        return Response::text($output);
    }

    /**
     * Find an ox_doorlock server event.
     */
    protected function findEvent(string $name): ?array
    {
        $events = [
            'ox_doorlock:stateChanged' => [
                'type' => 'doorlock',
                'namespace' => 'Server',
                'description' => 'Triggered when a door lock state changes',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'doorId', 'type' => 'number', 'description' => 'The door ID'],
                    ['name' => 'state', 'type' => 'number', 'description' => 'New door state (0 = unlocked, 1 = locked)'],
                ],
                'lua_example' => "AddEventHandler('ox_doorlock:stateChanged', function(doorId, state)\n    local doorName = exports.ox_doorlock:getDoor(doorId)?.name or 'Unknown'\n    local stateText = state == 1 and 'locked' or 'unlocked'\n    print(('Door %s (ID: %s) is now %s'):format(doorName, doorId, stateText))\nend)",
                'js_example' => "on('ox_doorlock:stateChanged', (doorId, state) => {\n    const door = exports.ox_doorlock.getDoor(doorId);\n    const doorName = door?.name || 'Unknown';\n    const stateText = state === 1 ? 'locked' : 'unlocked';\n    console.log(`Door \${doorName} (ID: \${doorId}) is now \${stateText}`);\n});",
            ],
        ];

        $nameLower = strtolower($name);

        foreach ($events as $eventName => $eventData) {
            if (strtolower($eventName) === $nameLower) {
                return array_merge(['name' => $eventName], $eventData);
            }
        }

        return null;
    }

    /**
     * Format event info.
     */
    protected function formatEventInfo(array $event, string $language): string
    {
        return view('mcp.cox.cox-event-reference', [
            'event' => $event,
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
            'event_name' => $schema
                ->string()
                ->description('The name of the ox_doorlock server event to look up (e.g., "ox_doorlock:stateChanged")')
                ->required(),
            'language' => $schema
                ->string()
                ->enum(['lua', 'js'])
                ->description('The scripting language for code examples')
                ->default('lua'),
        ];
    }
}
