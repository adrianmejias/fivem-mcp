<?php

namespace App\Mcp\Tools\QBCore;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Get a complete list of all 58 documented QBCore resources with brief descriptions and categories.')]
class GetQBCoreResourceList extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $category = $request->get('category');
        $resources = $this->getAllResources($category);

        return Response::text($this->formatResourceList($resources));
    }

    /**
     * Get all QBCore resources, optionally filtered by category.
     */
    protected function getAllResources(?string $category = null): array
    {
        $allResources = [
            // Admin & Management
            'qb-adminmenu' => [
                'name' => 'qb-adminmenu',
                'title' => 'Admin Menu',
                'category' => 'Admin & Management',
                'description' => 'Admin panel for server management with player controls, moderation, warnings, and coordinate tools',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-adminmenu',
            ],

            // Jobs
            'qb-ambulancejob' => [
                'name' => 'qb-ambulancejob',
                'title' => 'Ambulance Job',
                'category' => 'Jobs',
                'description' => 'Medical job with hospital check-in, damage/bleeding system, and patient revival mechanics',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-ambulancejob',
            ],
            'qb-busjob' => [
                'name' => 'qb-busjob',
                'title' => 'Bus Job',
                'category' => 'Jobs',
                'description' => 'Public transportation job for bus drivers with route and payment system',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-busjob',
            ],
            'qb-garbagejob' => [
                'name' => 'qb-garbagejob',
                'title' => 'Garbage Job',
                'category' => 'Jobs',
                'description' => 'Garbage collection job with pickup routes and payment system',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-garbagejob',
            ],
            'qb-hotdogjob' => [
                'name' => 'qb-hotdogjob',
                'title' => 'Hot Dog Job',
                'category' => 'Jobs',
                'description' => 'Hot dog stand vendor job with payment system',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-hotdogjob',
            ],
            'qb-mechanicjob' => [
                'name' => 'qb-mechanicjob',
                'title' => 'Mechanic Job',
                'category' => 'Jobs',
                'description' => 'Vehicle repair and customization job with parts system',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-mechanicjob',
            ],
            'qb-newsjob' => [
                'name' => 'qb-newsjob',
                'title' => 'News Job',
                'category' => 'Jobs',
                'description' => 'News reporter job with interviews and broadcast system',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-newsjob',
            ],
            'qb-policejob' => [
                'name' => 'qb-policejob',
                'title' => 'Police Job',
                'category' => 'Jobs',
                'description' => 'Law enforcement job with dispatch, arrest, jailing, and evidence system',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-policejob',
            ],
            'qb-recyclejob' => [
                'name' => 'qb-recyclejob',
                'title' => 'Recycle Job',
                'category' => 'Jobs',
                'description' => 'Recycling center worker job with item pickup and crafting',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-recyclejob',
            ],
            'qb-taxijob' => [
                'name' => 'qb-taxijob',
                'title' => 'Taxi Job',
                'category' => 'Jobs',
                'description' => 'Taxi driver job with passenger pickup and payment system',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-taxijob',
            ],
            'qb-towjob' => [
                'name' => 'qb-towjob',
                'title' => 'Tow Job',
                'category' => 'Jobs',
                'description' => 'Vehicle towing service with impound and payment system',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-towjob',
            ],
            'qb-truckerjob' => [
                'name' => 'qb-truckerjob',
                'title' => 'Trucker Job',
                'category' => 'Jobs',
                'description' => 'Long-haul trucking job with delivery routes and payment',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-truckerjob',
            ],

            // Crime & Robberies
            'qb-bankrobbery' => [
                'name' => 'qb-bankrobbery',
                'title' => 'Bank Robbery',
                'category' => 'Crime & Robberies',
                'description' => 'Heist job with thermal drill, hacking, and loot system',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-bankrobbery',
            ],
            'qb-drugs' => [
                'name' => 'qb-drugs',
                'title' => 'Drug Dealing',
                'category' => 'Crime & Robberies',
                'description' => 'Drug production and dealing system with supply and demand',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-drugs',
            ],
            'qb-houserobbery' => [
                'name' => 'qb-houserobbery',
                'title' => 'House Robbery',
                'category' => 'Crime & Robberies',
                'description' => 'Residential burglary system with lockpicking and loot',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-houserobbery',
            ],
            'qb-jewelry' => [
                'name' => 'qb-jewelry',
                'title' => 'Jewelry Store Robbery',
                'category' => 'Crime & Robberies',
                'description' => 'Jewelry store heist with thermite drill and loot system',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-jewelry',
            ],
            'qb-storerobbery' => [
                'name' => 'qb-storerobbery',
                'title' => 'Store Robbery',
                'category' => 'Crime & Robberies',
                'description' => 'Convenience store robbery with holdup mechanics',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-storerobbery',
            ],
            'qb-truckrobbery' => [
                'name' => 'qb-truckrobbery',
                'title' => 'Truck Robbery',
                'category' => 'Crime & Robberies',
                'description' => 'Armored truck heist with combat and loot mechanics',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-truckrobbery',
            ],
            'qb-weed' => [
                'name' => 'qb-weed',
                'title' => 'Weed Growing',
                'category' => 'Crime & Robberies',
                'description' => 'Marijuana cultivation and harvesting system',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-weed',
            ],
            'qb-vineyard' => [
                'name' => 'qb-vineyard',
                'title' => 'Vineyard',
                'category' => 'Crime & Robberies',
                'description' => 'Grape harvesting and wine production system',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-vineyard',
            ],

            // Housing & Interiors
            'qb-apartments' => [
                'name' => 'qb-apartments',
                'title' => 'Apartments',
                'category' => 'Housing & Interiors',
                'description' => 'Player apartment rental and management system',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-apartments',
            ],
            'qb-houses' => [
                'name' => 'qb-houses',
                'title' => 'Houses',
                'category' => 'Housing & Interiors',
                'description' => 'Player house ownership with storage and customization',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-houses',
            ],
            'qb-interior' => [
                'name' => 'qb-interior',
                'title' => 'Interior',
                'category' => 'Housing & Interiors',
                'description' => 'Interior world system for building custom rooms',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-interior',
            ],

            // Vehicles & Transportation
            'qb-garages' => [
                'name' => 'qb-garages',
                'title' => 'Garages',
                'category' => 'Vehicles & Transportation',
                'description' => 'Vehicle parking and retrieval system with multiple garages',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-garages',
            ],
            'qb-vehiclekeys' => [
                'name' => 'qb-vehiclekeys',
                'title' => 'Vehicle Keys',
                'category' => 'Vehicles & Transportation',
                'description' => 'Vehicle ownership and key distribution system',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-vehiclekeys',
            ],
            'qb-vehiclesales' => [
                'name' => 'qb-vehiclesales',
                'title' => 'Vehicle Sales',
                'category' => 'Vehicles & Transportation',
                'description' => 'Vehicle dealership system for buying and trading vehicles',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-vehiclesales',
            ],
            'qb-vehicleshop' => [
                'name' => 'qb-vehicleshop',
                'title' => 'Vehicle Shop',
                'category' => 'Vehicles & Transportation',
                'description' => 'Show room for purchasing new vehicles',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-vehicleshop',
            ],
            'qb-fuel' => [
                'name' => 'qb-fuel',
                'title' => 'Fuel System',
                'category' => 'Vehicles & Transportation',
                'description' => 'Vehicle fueling system with gas stations',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-fuel',
            ],

            // Commerce & Markets
            'qb-banking' => [
                'name' => 'qb-banking',
                'title' => 'Banking',
                'category' => 'Commerce & Markets',
                'description' => 'Bank account system with deposits, withdrawals, and transfers',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-banking',
            ],
            'qb-cityhall' => [
                'name' => 'qb-cityhall',
                'title' => 'City Hall',
                'category' => 'Commerce & Markets',
                'description' => 'Government records and ID system for players',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-cityhall',
            ],
            'qb-clothing' => [
                'name' => 'qb-clothing',
                'title' => 'Clothing Shop',
                'category' => 'Commerce & Markets',
                'description' => 'Clothing store system for outfit customization',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-clothing',
            ],
            'qb-crypto' => [
                'name' => 'qb-crypto',
                'title' => 'Cryptocurrency',
                'category' => 'Commerce & Markets',
                'description' => 'Cryptocurrency trading platform system',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-crypto',
            ],
            'qb-pawnshop' => [
                'name' => 'qb-pawnshop',
                'title' => 'Pawn Shop',
                'category' => 'Commerce & Markets',
                'description' => 'Pawn shop for selling and buying items',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-pawnshop',
            ],
            'qb-shops' => [
                'name' => 'qb-shops',
                'title' => 'Shops',
                'category' => 'Commerce & Markets',
                'description' => 'General-purpose shop system for generic stores',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-shops',
            ],

            // Player Systems
            'qb-inventory' => [
                'name' => 'qb-inventory',
                'title' => 'Inventory',
                'category' => 'Player Systems',
                'description' => 'Player item inventory management system',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-inventory',
            ],
            'qb-management' => [
                'name' => 'qb-management',
                'title' => 'Management',
                'category' => 'Player Systems',
                'description' => 'Org/faction/job management system for player hierarchies',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-management',
            ],
            'qb-multicharacter' => [
                'name' => 'qb-multicharacter',
                'title' => 'Multi-Character',
                'category' => 'Player Systems',
                'description' => 'Multiple character selection and management on login',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-multicharacter',
            ],
            'qb-phone' => [
                'name' => 'qb-phone',
                'title' => 'Phone',
                'category' => 'Player Systems',
                'description' => 'In-game phone system with apps, messages, and calls',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-phone',
            ],
            'qb-prison' => [
                'name' => 'qb-prison',
                'title' => 'Prison',
                'category' => 'Player Systems',
                'description' => 'Prison system for jailed players with tasks and release',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-prison',
            ],
            'qb-spawn' => [
                'name' => 'qb-spawn',
                'title' => 'Spawn',
                'category' => 'Player Systems',
                'description' => 'Initial spawn location selector for new players',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-spawn',
            ],
            'qb-fitbit' => [
                'name' => 'qb-fitbit',
                'title' => 'Fitbit',
                'category' => 'Player Systems',
                'description' => 'Health and fitness tracking wearable device',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-fitbit',
            ],

            // UI & Interface
            'qb-hud' => [
                'name' => 'qb-hud',
                'title' => 'HUD',
                'category' => 'UI & Interface',
                'description' => 'Main heads-up display for player information',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-hud',
            ],
            'qb-input' => [
                'name' => 'qb-input',
                'title' => 'Input',
                'category' => 'UI & Interface',
                'description' => 'Generic input dialog system for NUI popups',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-input',
            ],
            'qb-loading' => [
                'name' => 'qb-loading',
                'title' => 'Loading Screen',
                'category' => 'UI & Interface',
                'description' => 'Animated loading screen on server startup',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-loading',
            ],
            'qb-menu' => [
                'name' => 'qb-menu',
                'title' => 'Menu',
                'category' => 'UI & Interface',
                'description' => 'Simple menu UI system for player interactions',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-menu',
            ],
            'qb-radialmenu' => [
                'name' => 'qb-radialmenu',
                'title' => 'Radial Menu',
                'category' => 'UI & Interface',
                'description' => 'Radial/circular menu interface for quick selections',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-radialmenu',
            ],
            'qb-scoreboard' => [
                'name' => 'qb-scoreboard',
                'title' => 'Scoreboard',
                'category' => 'UI & Interface',
                'description' => 'Server player list and information scoreboard',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-scoreboard',
            ],
            'qb-target' => [
                'name' => 'qb-target',
                'title' => 'Target System',
                'category' => 'UI & Interface',
                'description' => 'NPC and object targeting system for interactions',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-target',
            ],

            // Entertainment & Activities
            'qb-diving' => [
                'name' => 'qb-diving',
                'title' => 'Diving',
                'category' => 'Entertainment & Activities',
                'description' => 'Underwater treasure hunting activity',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-diving',
            ],
            'qb-lapraces' => [
                'name' => 'qb-lapraces',
                'title' => 'Lap Races',
                'category' => 'Entertainment & Activities',
                'description' => 'Racing competition system with lap tracking',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-lapraces',
            ],
            'qb-minigames' => [
                'name' => 'qb-minigames',
                'title' => 'Minigames',
                'category' => 'Entertainment & Activities',
                'description' => 'Collection of minigames for lockpicking, hacking, etc.',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-minigames',
            ],
            'qb-radio' => [
                'name' => 'qb-radio',
                'title' => 'Radio',
                'category' => 'Entertainment & Activities',
                'description' => 'In-vehicle or ambient radio station system',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-radio',
            ],
            'qb-streetraces' => [
                'name' => 'qb-streetraces',
                'title' => 'Street Races',
                'category' => 'Entertainment & Activities',
                'description' => 'Street racing event system with records',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-streetraces',
            ],

            // Utilities & Miscellaneous
            'qb-doorlock' => [
                'name' => 'qb-doorlock',
                'title' => 'Door Lock',
                'category' => 'Utilities & Miscellaneous',
                'description' => 'Door locking system with access control',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-doorlock',
            ],
            'qb-scrapyard' => [
                'name' => 'qb-scrapyard',
                'title' => 'Scrap Yard',
                'category' => 'Utilities & Miscellaneous',
                'description' => 'Vehicle scrapping system for parts and money',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-scrapyard',
            ],
            'qb-smallresources' => [
                'name' => 'qb-smallresources',
                'title' => 'Small Resources',
                'category' => 'Utilities & Miscellaneous',
                'description' => 'Bundle of small utility resources for various features',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-smallresources',
            ],
            'qb-weapons' => [
                'name' => 'qb-weapons',
                'title' => 'Weapons',
                'category' => 'Utilities & Miscellaneous',
                'description' => 'Weapon shop and ammo purchasing system',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-weapons',
            ],
            'qb-weathersync' => [
                'name' => 'qb-weathersync',
                'title' => 'Weather Sync',
                'category' => 'Utilities & Miscellaneous',
                'description' => 'Server-wide weather synchronization system',
                'url' => 'https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-weathersync',
            ],
        ];

        if ($category) {
            return array_filter($allResources, fn ($r) => strtolower($r['category']) === strtolower($category));
        }

        return $allResources;
    }

    /**
     * Format the resource list.
     */
    protected function formatResourceList(array $resources): string
    {
        return view('mcp.qbcore.qbcore-resource-list', [
            'resources' => $resources,
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
            'category' => $schema
                ->string()
                ->description('Optional: Filter resources by category (Admin & Management, Jobs, Crime & Robberies, Housing & Interiors, Vehicles & Transportation, Commerce & Markets, Player Systems, UI & Interface, Entertainment & Activities, Utilities & Miscellaneous)')
                ->enum([
                    'Admin & Management',
                    'Jobs',
                    'Crime & Robberies',
                    'Housing & Interiors',
                    'Vehicles & Transportation',
                    'Commerce & Markets',
                    'Player Systems',
                    'UI & Interface',
                    'Entertainment & Activities',
                    'Utilities & Miscellaneous',
                ]),
        ];
    }
}
