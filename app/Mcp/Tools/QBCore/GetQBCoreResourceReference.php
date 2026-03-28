<?php

namespace App\Mcp\Tools\QBCore;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Get detailed documentation for a specific QBCore resource. Returns information about configuration, commands, events, callbacks, and usage examples.')]
class GetQBCoreResourceReference extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $resourceName = $request->get('resource_name');

        $resource = $this->findResource($resourceName);

        if (! $resource) {
            return Response::text(sprintf("QBCore resource '%s' not found. Use GetQBCoreResourceList to see all available resources.", $resourceName));
        }

        return Response::text($this->formatResourceReference($resource));
    }

    /**
     * Find a QBCore resource by name.
     */
    protected function findResource(string $name): ?array
    {
        $resources = [
            'qb-adminmenu' => [
                'name' => 'qb-adminmenu',
                'title' => 'Admin Menu',
                'category' => 'Admin & Management',
                'description' => 'Admin panel for server management and player moderation.',
                'features' => ['Player Management', 'Moderation', 'Coordinate Tools', 'Announcements', 'Reports', 'Warnings'],
                'commands' => [
                    '/admin' => 'Opens the admin menu',
                    '/blips' => 'Toggles player blips',
                    '/names' => 'Toggles player names display',
                    '/coords' => 'Shows your current coordinates',
                    '/maxmods' => 'Sets vehicle to max mods',
                    '/noclip' => 'Toggles noclip mode',
                    '/admincar' => 'Adds current vehicle to garage',
                    '/announce [message]' => 'Creates an announcement',
                    '/report [message]' => 'Create a report to staff',
                    '/reportr [message]' => 'Replies to a user report',
                    '/reporttoggle' => 'Opt in/out of receiving player reports',
                    '/staffchat [message]' => 'Sends a staff-only message',
                    '/warn [id] [reason]' => 'Warn a player',
                    '/checkwarns [id] [opt: number]' => 'View warnings for a player',
                    '/delwarn [id] [number]' => 'Delete a warning from a player',
                    '/givenuifocus [id] [hasFocus] [hasCursor]' => 'Sets nuifocus state for player',
                    '/setmodel [model] [id]' => 'Changes the players ped model',
                    '/setspeed [opt: speed]' => 'Sets players foot speed',
                    '/kickall' => 'Kick all players from server',
                    '/setammo [amount] [opt: weapon]' => 'Set weapon ammo',
                    '/vector2' => 'Copies vector2 to clipboard',
                    '/vector3' => 'Copies vector3 to clipboard',
                    '/vector4' => 'Copies vector4 to clipboard',
                    '/heading' => 'Copies heading to clipboard',
                ],
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-adminmenu',
            ],

            'qb-ambulancejob' => [
                'name' => 'qb-ambulancejob',
                'title' => 'Ambulance Job',
                'category' => 'Jobs',
                'description' => 'Medical job with hospital check-in system, damage/bleeding mechanics, and patient revival.',
                'features' => ['Hospital Check-in System', 'Damage Tracking', 'Bleeding System', 'Patient Revival', 'Job Shop', 'Vehicle Spawner'],
                'commands' => [
                    '/911e [message]' => 'Sends a message to EMS',
                    '/status' => 'Check the status of the nearest player',
                    '/heal' => 'Heals the nearest player',
                    '/revivep' => 'Revives the nearest player',
                    '/revive' => 'Revive yourself',
                    '/setpain [opt: id]' => 'Sets the pain level',
                    '/kill [opt: id]' => 'Kills the player',
                    '/aheal [opt: id]' => 'Heals a player',
                ],
                'items' => [
                    'ifaks' => 'Relieves player stress and heals them by +10',
                    'bandage' => 'Heals player by +10 and has 50% chance of removing 1 level of bleeding',
                    'firstaid' => 'Completely heals closest player as long as they are in last-stand',
                    'painkillers' => 'Stops bleeding temporarily but wears off over time',
                ],
                'events' => [
                    'hospital:server:SetDeathStatus' => 'Modify the players isdead metadata',
                    'hospital:server:SetLaststandStatus' => 'Modify the players inlaststand metadata',
                    'hospital:server:ambulanceAlert' => 'Send a notification to all EMTs',
                    'hospital:server:RevivePlayer' => 'Revive a player for free or a fee',
                ],
                'callbacks' => [
                    'hospital:GetDoctors' => 'Returns the number of players online with the ambulance job',
                ],
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-ambulancejob',
            ],

            'qb-banking' => [
                'name' => 'qb-banking',
                'title' => 'Banking',
                'category' => 'Commerce & Markets',
                'description' => 'Bank account system with deposits, withdrawals, and transfers between players.',
                'features' => ['Account Management', 'Money Transfer', 'Transaction History', 'ATM Withdrawals'],
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-banking',
            ],

            'qb-inventory' => [
                'name' => 'qb-inventory',
                'title' => 'Inventory',
                'category' => 'Player Systems',
                'description' => 'Player item inventory management system with weight-based limits.',
                'features' => ['Item Management', 'Weight Limits', 'Inventory UI', 'Drop Items', 'Trade Items'],
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-inventory',
            ],

            'qb-phone' => [
                'name' => 'qb-phone',
                'title' => 'Phone',
                'category' => 'Player Systems',
                'description' => 'In-game phone system with apps, messages, calls, and notifications.',
                'features' => ['Messaging', 'Phone Calls', 'Apps System', 'Contacts', 'Photos', 'Radio'],
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-phone',
            ],

            'qb-policejob' => [
                'name' => 'qb-policejob',
                'title' => 'Police Job',
                'category' => 'Jobs',
                'description' => 'Law enforcement job with dispatch, arrest, jailing, and evidence system.',
                'features' => ['Dispatch System', 'Arrests', 'Jail Management', 'Evidence Collection', 'Vehicle Impound'],
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-policejob',
            ],

            'qb-garages' => [
                'name' => 'qb-garages',
                'title' => 'Garages',
                'category' => 'Vehicles & Transportation',
                'description' => 'Vehicle parking and retrieval system with multiple garage locations.',
                'features' => ['Multiple Garages', 'Vehicle Storage', 'Quick Parking', 'Vehicle Retrieval'],
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-garages',
            ],

            'qb-shops' => [
                'name' => 'qb-shops',
                'title' => 'Shops',
                'category' => 'Commerce & Markets',
                'description' => 'General-purpose shop system for creating generic stores.',
                'features' => ['Shop Creation', 'Item Pricing', 'Purchase System', 'Multiple Locations'],
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-shops',
            ],

            'qb-target' => [
                'name' => 'qb-target',
                'title' => 'Target System',
                'category' => 'UI & Interface',
                'description' => 'NPC and object targeting system for player interactions.',
                'features' => ['Click Targeting', 'NPC Interaction', 'Object Targeting', 'Distance Based'],
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-target',
            ],

            'qb-hud' => [
                'name' => 'qb-hud',
                'title' => 'HUD',
                'category' => 'UI & Interface',
                'description' => 'Main heads-up display showing player information and status.',
                'features' => ['Health Display', 'Armor Display', 'Hunger/Thirst', 'Stress Level', 'Mini Map'],
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-hud',
            ],

            'qb-doorlock' => [
                'name' => 'qb-doorlock',
                'title' => 'Door Lock',
                'category' => 'Utilities & Miscellaneous',
                'description' => 'Door locking system with access control and permissions.',
                'features' => ['Door Locking', 'Access Control', 'Job Locks', 'Gang Locks'],
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-doorlock',
            ],

            'qb-mechanicjob' => [
                'name' => 'qb-mechanicjob',
                'title' => 'Mechanic Job',
                'category' => 'Jobs',
                'description' => 'Vehicle repair and customization job with parts system.',
                'features' => ['Vehicle Repair', 'Vehicle Customization', 'Parts Management', 'Customer Jobs'],
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-mechanicjob',
            ],

            'qb-housing' => [
                'name' => 'qb-houses',
                'title' => 'Houses',
                'category' => 'Housing & Interiors',
                'description' => 'Player house ownership with storage and customization.',
                'features' => ['House Purchase', 'House Storage', 'Furniture Customization', 'Multi-Garage'],
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-houses',
            ],

            'qb-prison' => [
                'name' => 'qb-prison',
                'title' => 'Prison',
                'category' => 'Player Systems',
                'description' => 'Prison system for jailed players with tasks and release.',
                'features' => ['Jailing System', 'Prison Jobs', 'Release System', 'Time Tracking'],
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-prison',
            ],

            'qb-bankrobbery' => [
                'name' => 'qb-bankrobbery',
                'title' => 'Bank Robbery',
                'category' => 'Crime & Robberies',
                'description' => 'Heist job with thermal drill, hacking, and loot system.',
                'features' => ['Thermal Drill', 'Hacking Minigame', 'Loot System', 'Police Alerts'],
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-bankrobbery',
            ],

            'qb-drugs' => [
                'name' => 'qb-drugs',
                'title' => 'Drug Dealing',
                'category' => 'Crime & Robberies',
                'description' => 'Drug production and dealing system with supply and demand.',
                'features' => ['Drug Production', 'Dealing Mechanics', 'Supply/Demand', 'Dealer Routes'],
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-drugs',
            ],

            'qb-fuel' => [
                'name' => 'qb-fuel',
                'title' => 'Fuel System',
                'category' => 'Vehicles & Transportation',
                'description' => 'Vehicle fueling system with gas stations.',
                'features' => ['Gas Stations', 'Fuel Usage', 'Cost Calculation', 'Player Jobs'],
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-fuel',
            ],

            'qb-multicharacter' => [
                'name' => 'qb-multicharacter',
                'title' => 'Multi-Character',
                'category' => 'Player Systems',
                'description' => 'Multiple character selection and management on login.',
                'features' => ['Character Creation', 'Character Selection', 'Character Deletion'],
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-multicharacter',
            ],

            'qb-clothing' => [
                'name' => 'qb-clothing',
                'title' => 'Clothing Shop',
                'category' => 'Commerce & Markets',
                'description' => 'Clothing store system for outfit customization.',
                'features' => ['Outfit Customization', 'Clothing Purchase', 'Clothing Categories'],
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-clothing',
            ],

            'qb-vehicleshop' => [
                'name' => 'qb-vehicleshop',
                'title' => 'Vehicle Shop',
                'category' => 'Vehicles & Transportation',
                'description' => 'Show room for purchasing new vehicles.',
                'features' => ['Vehicle Showcase', 'Vehicle Purchase', 'Test Drive'],
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-vehicleshop',
            ],

            'qb-input' => [
                'name' => 'qb-input',
                'title' => 'Input',
                'category' => 'UI & Interface',
                'description' => 'Generic input dialog system for NUI popups.',
                'features' => ['Text Input', 'Number Input', 'Validation', 'Custom Styling'],
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-input',
            ],

            'qb-manage' => [
                'name' => 'qb-management',
                'title' => 'Management',
                'category' => 'Player Systems',
                'description' => 'Org/faction/job management for player hierarchies.',
                'features' => ['Rank Management', 'Member Management', 'Permission Control'],
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-management',
            ],
        ];

        // Normalize the resource name for matching
        $normalized = strtolower(str_replace(['_', ' '], '-', $name));
        // Remove any prefix variations and ensure qb- prefix
        $normalized = preg_replace('/^(qbcore[-_]?)?/i', '', $normalized);
        $normalized = 'qb-'.ltrim($normalized, 'qb-');

        foreach ($resources as $resource) {
            if (strtolower($resource['name']) === $normalized) {
                return $resource;
            }
        }

        return null;
    }

    /**
     * Format the resource reference.
     */
    protected function formatResourceReference(array $resource): string
    {
        return view('mcp.qbcore.qbcore-resource-reference', [
            'resource' => $resource,
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
            'resource_name' => $schema
                ->string()
                ->description('The name of the QBCore resource to look up (e.g., qb-ambulancejob, qb-adminmenu, qb-inventory)')
                ->required(),
        ];
    }
}
