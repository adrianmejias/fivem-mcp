<?php

namespace App\Mcp\Tools\Prodigy\Client;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('prp-bridge client-side exports reference. Covers the IsAllowlisted allowlist check, the PropPlacer UI, and the Ped Interactions system (AddPedInteraction, RemovePedInteraction).')]
class GetProdigyClientExport extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $exportName = $request->get('export_name', '');

        $exports = $this->getClientExports();

        if ($exportName) {
            $export = collect($exports)->firstWhere('name', $exportName);
            if ($export) {
                return Response::text($this->formatExport($export));
            }

            return Response::text("prp-bridge client export '$exportName' not found. See https://docs.prodigyrp.net/prp-bridge/exports.html for the full reference.");
        }

        return Response::text($this->formatExportList($exports));
    }

    /**
     * Get all prp-bridge client-side exports.
     */
    protected function getClientExports(): array
    {
        return [
            // Allowlist
            [
                'name' => 'IsAllowlisted',
                'category' => 'Allowlist',
                'side' => 'client',
                'description' => 'Returns whether the current player character has a specific allowlist.',
                'parameters' => [
                    ['name' => 'allowlist', 'type' => 'string', 'description' => 'The allowlist key to check (e.g. "police_allowlist")'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'true if the character has the allowlist, false otherwise'],
                'lua_example' => "local isAllowed = exports['prp-bridge']:IsAllowlisted('police_allowlist')\nif isAllowed then\n    print('Player has police_allowlist')\nend",
            ],

            // Prop Placer
            [
                'name' => 'PropPlacer',
                'category' => 'Prop Placer',
                'side' => 'client',
                'description' => 'Opens the interactive prop placement UI. Returns the final position and heading, or nil if the player cancelled.',
                'parameters' => [
                    ['name' => 'model', 'type' => 'number', 'description' => 'Model hash of the prop to place'],
                    ['name' => 'forceGround', 'type' => 'boolean?', 'description' => 'Force the prop to snap to the ground (optional)'],
                    ['name' => 'allowedMaterials', 'type' => 'table?', 'description' => 'Table of allowed surface material types (optional)'],
                    ['name' => 'maxDistance', 'type' => 'number?', 'description' => 'Maximum placement distance from the player (optional)'],
                ],
                'returns' => ['type' => 'vector4 | nil', 'description' => 'vector4(x, y, z, heading) of the placed prop, or nil if cancelled'],
                'lua_example' => "local result = exports['prp-bridge']:PropPlacer(\n    GetHashKey('prop_barrier_work05a'),\n    true,\n    nil,\n    10.0\n)\nif result then\n    print('Placed at:', result.x, result.y, result.z, 'heading:', result.w)\nelse\n    print('Placement cancelled')\nend",
            ],

            // Ped Interactions
            [
                'name' => 'AddPedInteraction',
                'category' => 'Ped Interactions',
                'side' => 'client',
                'description' => 'Registers a ped interaction. The ped spawns when a player enters the radius and is removed when they leave. Returns true on success, or false with an error string.',
                'parameters' => [
                    ['name' => 'id', 'type' => 'string', 'description' => 'Unique identifier for this ped interaction'],
                    ['name' => 'payload', 'type' => 'table', 'description' => 'Configuration table (model, coords, heading, radius, options, scenario/anim, component, weapon)'],
                ],
                'returns' => ['type' => 'boolean, string?', 'description' => 'true on success; false, "missing" if id/payload is nil; false, "exists" if id is already registered'],
                'lua_example' => "exports['prp-bridge']:AddPedInteraction('my_dealer', {\n    model = 's_m_y_dealer_01',\n    coords = vector3(100.0, 200.0, 30.0),\n    heading = 180.0,\n    radius = 50.0,\n    scenario = 'WORLD_HUMAN_STAND_IMPATIENT',\n    options = {\n        {\n            icon = 'fas fa-comments',\n            label = 'Talk to Dealer',\n            distance = 2.0,\n            onSelect = function()\n                openDealerMenu()\n            end\n        }\n    }\n})",
            ],
            [
                'name' => 'RemovePedInteraction',
                'category' => 'Ped Interactions',
                'side' => 'client',
                'description' => 'Removes a registered ped interaction and deletes the ped entity if it currently exists.',
                'parameters' => [
                    ['name' => 'id', 'type' => 'string', 'description' => 'The ID used when the ped was registered with AddPedInteraction'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'true if the ped was removed; false if the ID was not found'],
                'lua_example' => "local removed = exports['prp-bridge']:RemovePedInteraction('my_dealer')\nif not removed then\n    print('Ped not found')\nend\n\n-- Auto-cleanup on resource stop is handled automatically.\n-- Use manual removal only when you need to remove the ped while the resource is still running.",
            ],
        ];
    }

    /**
     * Format a single export for output.
     */
    protected function formatExport(array $export): string
    {
        return view('mcp.prodigy.prodigy-export-reference', [
            'export' => $export,
        ])->render();
    }

    /**
     * Format full export listing grouped by category.
     */
    protected function formatExportList(array $exports): string
    {
        $byCategory = collect($exports)->groupBy('category')->toArray();

        return view('mcp.prodigy.prodigy-export-list', [
            'exportsByCategory' => $byCategory,
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
            'export_name' => $schema
                ->string()
                ->description('The prp-bridge client export name to look up (e.g. "IsAllowlisted", "AddPedInteraction"). Omit to list all client exports.')
                ->default(''),
        ];
    }
}
