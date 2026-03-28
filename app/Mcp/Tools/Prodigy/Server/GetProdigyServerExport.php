<?php

namespace App\Mcp\Tools\Prodigy\Server;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('prp-bridge server-side exports reference. Covers Cooldowns, Allowlist, Groups, UniQueue, Sell Shops, and Cases exports provided by the Prodigy Studios bridge resource.')]
class GetProdigyServerExport extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $exportName = $request->get('export_name', '');

        $exports = $this->getServerExports();

        if ($exportName) {
            $export = collect($exports)->firstWhere('name', $exportName);
            if ($export) {
                return Response::text($this->formatExport($export));
            }

            return Response::text("prp-bridge server export '$exportName' not found. See https://docs.prodigyrp.net/prp-bridge/exports.html for the full reference.");
        }

        return Response::text($this->formatExportList($exports));
    }

    /**
     * Get all prp-bridge server-side exports.
     */
    protected function getServerExports(): array
    {
        return [
            // Cooldowns
            [
                'name' => 'startGlobalCooldown',
                'category' => 'Cooldowns',
                'side' => 'server',
                'description' => 'Starts a global cooldown for all players for the given duration.',
                'parameters' => [
                    ['name' => 'cooldownKey', 'type' => 'string', 'description' => 'Unique key identifying this cooldown'],
                    ['name' => 'minutes', 'type' => 'number', 'description' => 'Duration of the cooldown in minutes'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "exports['prp-bridge']:startGlobalCooldown('bank_robbery', 30)",
            ],
            [
                'name' => 'startCooldownByIdentifier',
                'category' => 'Cooldowns',
                'side' => 'server',
                'description' => 'Starts a cooldown for a specific player identifier.',
                'parameters' => [
                    ['name' => 'identifier', 'type' => 'string', 'description' => 'The player state ID / identifier'],
                    ['name' => 'cooldownKey', 'type' => 'string', 'description' => 'Unique key identifying this cooldown'],
                    ['name' => 'minutes', 'type' => 'number', 'description' => 'Duration of the cooldown in minutes'],
                    ['name' => 'applyToAllCharacters', 'type' => 'boolean?', 'description' => 'Apply to all characters of the same player (optional)'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "local identifier = GetPlayerIdentifier(source, 0)\nexports['prp-bridge']:startCooldownByIdentifier(identifier, 'drug_run', 15)",
            ],
            [
                'name' => 'startCooldownByPlayerId',
                'category' => 'Cooldowns',
                'side' => 'server',
                'description' => 'Starts a cooldown for a player by their server ID.',
                'parameters' => [
                    ['name' => 'playerId', 'type' => 'number', 'description' => 'The player server ID'],
                    ['name' => 'cooldownKey', 'type' => 'string', 'description' => 'Unique key identifying this cooldown'],
                    ['name' => 'minutes', 'type' => 'number', 'description' => 'Duration of the cooldown in minutes'],
                    ['name' => 'applyToAllCharacters', 'type' => 'boolean?', 'description' => 'Apply to all characters of the same player (optional)'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "exports['prp-bridge']:startCooldownByPlayerId(source, 'drug_run', 15)",
            ],
            [
                'name' => 'isCooldownActive',
                'category' => 'Cooldowns',
                'side' => 'server',
                'description' => 'Returns whether a global cooldown is currently active.',
                'parameters' => [
                    ['name' => 'cooldownKey', 'type' => 'string', 'description' => 'The cooldown key to check'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'true if the global cooldown is active, false otherwise'],
                'lua_example' => "if exports['prp-bridge']:isCooldownActive('bank_robbery') then\n    -- notify player to wait\nend",
            ],
            [
                'name' => 'isCooldownActiveForIdentifier',
                'category' => 'Cooldowns',
                'side' => 'server',
                'description' => 'Returns whether a cooldown is active for a specific player identifier.',
                'parameters' => [
                    ['name' => 'identifier', 'type' => 'string', 'description' => 'The player state ID / identifier'],
                    ['name' => 'cooldownKey', 'type' => 'string', 'description' => 'The cooldown key to check'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'true if the cooldown is active for that identifier, false otherwise'],
                'lua_example' => "local identifier = GetPlayerIdentifier(source, 0)\nif exports['prp-bridge']:isCooldownActiveForIdentifier(identifier, 'drug_run') then\n    -- notify player they must wait\nend",
            ],

            // Allowlist
            [
                'name' => 'GetAllowlist',
                'category' => 'Allowlist',
                'side' => 'server',
                'description' => 'Returns all allowlists for a character by their state ID.',
                'parameters' => [
                    ['name' => 'stateId', 'type' => 'string', 'description' => 'The character state ID'],
                ],
                'returns' => ['type' => 'table<string, boolean>', 'description' => 'Map of allowlist name to whether the character has it'],
                'lua_example' => "local allowlists = exports['prp-bridge']:GetAllowlist(stateId)\nfor name, has in pairs(allowlists) do\n    print(name, has)\nend",
            ],
            [
                'name' => 'HasAllowlist',
                'category' => 'Allowlist',
                'side' => 'server',
                'description' => 'Returns whether a character has a specific allowlist.',
                'parameters' => [
                    ['name' => 'stateId', 'type' => 'string', 'description' => 'The character state ID'],
                    ['name' => 'allowlist', 'type' => 'string', 'description' => 'The allowlist key to check'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'true if the character has the allowlist'],
                'lua_example' => "if exports['prp-bridge']:HasAllowlist(stateId, 'police_allowlist') then\n    print('Has police allowlist')\nend",
            ],
            [
                'name' => 'AddAllowlist',
                'category' => 'Allowlist',
                'side' => 'server',
                'description' => 'Adds an allowlist to a character.',
                'parameters' => [
                    ['name' => 'source', 'type' => 'number', 'description' => 'The player server ID'],
                    ['name' => 'stateId', 'type' => 'string', 'description' => 'The character state ID'],
                    ['name' => 'allowlist', 'type' => 'string', 'description' => 'The allowlist key to add'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'true if successfully added'],
                'lua_example' => "local success = exports['prp-bridge']:AddAllowlist(source, stateId, 'police_allowlist')\nif not success then\n    print('Failed to add allowlist')\nend",
            ],
            [
                'name' => 'RemoveAllowlist',
                'category' => 'Allowlist',
                'side' => 'server',
                'description' => 'Removes an allowlist from a character.',
                'parameters' => [
                    ['name' => 'source', 'type' => 'number', 'description' => 'The player server ID'],
                    ['name' => 'stateId', 'type' => 'string', 'description' => 'The character state ID'],
                    ['name' => 'allowlist', 'type' => 'string', 'description' => 'The allowlist key to remove'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'true if successfully removed'],
                'lua_example' => "local success = exports['prp-bridge']:RemoveAllowlist(source, stateId, 'police_allowlist')\nif not success then\n    print('Failed to remove allowlist')\nend",
            ],

            // Groups
            [
                'name' => 'CreateGroup',
                'category' => 'Groups',
                'side' => 'server',
                'description' => 'Creates a new group with the specified player as the leader.',
                'parameters' => [
                    ['name' => 'leaderSrc', 'type' => 'number', 'description' => 'Server ID of the player who will be the group leader'],
                ],
                'returns' => ['type' => 'table<{ success, error?, group? }>', 'description' => 'Result table with success boolean, optional error string, and optional group object'],
                'lua_example' => "local result = exports['prp-bridge']:CreateGroup(source)\nif result.success then\n    local group = result.group\n    print('Group created:', group.getUUID())\nelse\n    print('Error:', result.error)\nend",
            ],
            [
                'name' => 'GetGroupFromMember',
                'category' => 'Groups',
                'side' => 'server',
                'description' => 'Returns the group a player is currently in, or nil.',
                'parameters' => [
                    ['name' => 'src', 'type' => 'number', 'description' => 'The player server ID'],
                ],
                'returns' => ['type' => 'Group?', 'description' => 'The Group object if the player is in a group, nil otherwise'],
                'lua_example' => "local group = exports['prp-bridge']:GetGroupFromMember(source)\nif group then\n    print('Player is in group:', group.getUUID())\nend",
            ],
            [
                'name' => 'GetGroupFromMemberByIdentifier',
                'category' => 'Groups',
                'side' => 'server',
                'description' => 'Returns the group a player identifier is currently in, or nil.',
                'parameters' => [
                    ['name' => 'identifier', 'type' => 'string', 'description' => 'The player state ID / identifier'],
                ],
                'returns' => ['type' => 'Group?', 'description' => 'The Group object if found, nil otherwise'],
                'lua_example' => "local identifier = GetPlayerIdentifier(source, 0)\nlocal group = exports['prp-bridge']:GetGroupFromMemberByIdentifier(identifier)\nif group then\n    print('Group UUID:', group.getUUID())\nend",
            ],
            [
                'name' => 'GetGroupByUuid',
                'category' => 'Groups',
                'side' => 'server',
                'description' => 'Returns a group by its unique identifier.',
                'parameters' => [
                    ['name' => 'uuid', 'type' => 'string', 'description' => 'The group UUID'],
                ],
                'returns' => ['type' => 'Group?', 'description' => 'The Group object, or nil if not found'],
                'lua_example' => "local group = exports['prp-bridge']:GetGroupByUuid('some-group-uuid')\nif group then\n    print('Found group with', #group.getMembers(), 'members')\nend",
            ],
            [
                'name' => 'GetGroupByPartyUuid',
                'category' => 'Groups',
                'side' => 'server',
                'description' => 'Returns a group by its linked UniQueue party UUID.',
                'parameters' => [
                    ['name' => 'uuid', 'type' => 'string', 'description' => 'The UniQueue party UUID linked to the group'],
                ],
                'returns' => ['type' => 'Group?', 'description' => 'The Group object, or nil if not found'],
                'lua_example' => "local group = exports['prp-bridge']:GetGroupByPartyUuid(partyUuid)\nif group then\n    print('Found group for party:', group.getUUID())\nend",
            ],
            [
                'name' => 'GetGroupIdFromMember',
                'category' => 'Groups',
                'side' => 'server',
                'description' => 'Returns the group UUID for a player if they are in a group.',
                'parameters' => [
                    ['name' => 'src', 'type' => 'number', 'description' => 'The player server ID'],
                ],
                'returns' => ['type' => 'string?', 'description' => 'The group UUID string, or nil if not in a group'],
                'lua_example' => "local groupId = exports['prp-bridge']:GetGroupIdFromMember(source)\nif groupId then\n    print('Group ID:', groupId)\nend",
            ],
            [
                'name' => 'GetGroupIdFromMemberByIdentifier',
                'category' => 'Groups',
                'side' => 'server',
                'description' => 'Returns the group UUID for a player identifier if they are in a group.',
                'parameters' => [
                    ['name' => 'identifier', 'type' => 'string', 'description' => 'The player state ID / identifier'],
                ],
                'returns' => ['type' => 'string?', 'description' => 'The group UUID string, or nil if not in a group'],
                'lua_example' => "local identifier = GetPlayerIdentifier(source, 0)\nlocal groupId = exports['prp-bridge']:GetGroupIdFromMemberByIdentifier(identifier)\nif groupId then\n    print('Group ID:', groupId)\nend",
            ],
            [
                'name' => 'GetGroupPlayerIds',
                'category' => 'Groups',
                'side' => 'server',
                'description' => 'Returns all player server IDs currently in the specified group.',
                'parameters' => [
                    ['name' => 'uuid', 'type' => 'string', 'description' => 'The group UUID'],
                ],
                'returns' => ['type' => 'number[]', 'description' => 'Array of player server IDs in the group'],
                'lua_example' => "local playerIds = exports['prp-bridge']:GetGroupPlayerIds(groupUuid)\nfor _, playerId in ipairs(playerIds) do\n    TriggerClientEvent('myresource:start', playerId)\nend",
            ],

            // UniQueue
            [
                'name' => 'CreateQueue',
                'category' => 'UniQueue',
                'side' => 'server',
                'description' => 'Creates a new mission queue. Most Prodigy resources create their own queues — use this only when building custom missions.',
                'parameters' => [
                    ['name' => 'name', 'type' => 'string', 'description' => 'Unique queue identifier'],
                    ['name' => 'type', 'type' => 'string', 'description' => 'Queue type: "civ" (civilian missions) or "crime" (crime missions)'],
                    ['name' => 'requiredPolicePower', 'type' => 'number', 'description' => 'Police power required to run the mission'],
                    ['name' => 'maxConcurrentTasks', 'type' => 'number', 'description' => 'Max missions from this queue running simultaneously'],
                    ['name' => 'cooldown', 'type' => 'number', 'description' => 'Seconds after a task ends before the next mission can run'],
                ],
                'returns' => ['type' => 'UniQueue', 'description' => 'The created queue object'],
                'lua_example' => "local queue = exports['prp-bridge']:CreateQueue(\n    'my_mission',\n    'crime',\n    4,  -- requires 4 police power\n    1,  -- max 1 concurrent task\n    60  -- 60 second cooldown\n)\n\nqueue.setExecFunction(function(queueName, partyUuid, partyMembers, taskId)\n    startMission(partyMembers, taskId)\nend)",
            ],
            [
                'name' => 'GetQueue',
                'category' => 'UniQueue',
                'side' => 'server',
                'description' => 'Returns a queue by its name, or nil if not found.',
                'parameters' => [
                    ['name' => 'name', 'type' => 'string', 'description' => 'The queue name used in CreateQueue'],
                ],
                'returns' => ['type' => 'UniQueue?', 'description' => 'The queue object, or nil if not found'],
                'lua_example' => "local queue = exports['prp-bridge']:GetQueue('my_mission')\nif queue then\n    print('Queue position count:', queue.getNumOfExecTasks())\nend",
            ],
            [
                'name' => 'GetQueuesByType',
                'category' => 'UniQueue',
                'side' => 'server',
                'description' => 'Returns all queue names of a given type.',
                'parameters' => [
                    ['name' => 'type', 'type' => 'string', 'description' => 'Queue type: "civ" or "crime"'],
                ],
                'returns' => ['type' => 'table<string, boolean>', 'description' => 'Map of queue names of that type'],
                'lua_example' => "local crimeQueues = exports['prp-bridge']:GetQueuesByType('crime')\nfor queueName in pairs(crimeQueues) do\n    print('Crime queue:', queueName)\nend",
            ],
            [
                'name' => 'CreateParty',
                'category' => 'UniQueue',
                'side' => 'server',
                'description' => 'Creates a new UniQueue party of the given type.',
                'parameters' => [
                    ['name' => 'type', 'type' => 'string', 'description' => 'Party type: "civ" or "crime"'],
                ],
                'returns' => ['type' => 'UniQueueParty', 'description' => 'The created party object'],
                'lua_example' => "local party = exports['prp-bridge']:CreateParty('crime')\n\n-- Add members by identifier\nlocal identifier = GetPlayerIdentifier(source, 0)\nlocal result = party.addMember(identifier)\nif not result.success then\n    print('Error:', result.error)\nend\n\n-- Join a queue\nlocal queue = exports['prp-bridge']:GetQueue('my_mission')\nqueue.add(party)",
            ],
            [
                'name' => 'GetParty',
                'category' => 'UniQueue',
                'side' => 'server',
                'description' => 'Returns a party by its UUID, or nil if not found.',
                'parameters' => [
                    ['name' => 'uuid', 'type' => 'string', 'description' => 'The party UUID'],
                ],
                'returns' => ['type' => 'UniQueueParty?', 'description' => 'The party object, or nil if not found'],
                'lua_example' => "local party = exports['prp-bridge']:GetParty(partyUuid)\nif party then\n    local members = party.getMembersAsArray()\n    print('Party has', #members, 'members')\nend",
            ],
            [
                'name' => 'GetPartiesByType',
                'category' => 'UniQueue',
                'side' => 'server',
                'description' => 'Returns all party UUIDs of a given type.',
                'parameters' => [
                    ['name' => 'queueType', 'type' => 'string', 'description' => 'Party type: "civ" or "crime"'],
                ],
                'returns' => ['type' => 'table<string, boolean>', 'description' => 'Map of party UUIDs of that type'],
                'lua_example' => "local crimeParties = exports['prp-bridge']:GetPartiesByType('crime')\nfor partyUuid in pairs(crimeParties) do\n    print('Crime party:', partyUuid)\nend",
            ],
            [
                'name' => 'GetPartiesFromPlayer',
                'category' => 'UniQueue',
                'side' => 'server',
                'description' => 'Returns all party UUIDs the player identifier is currently in.',
                'parameters' => [
                    ['name' => 'identifier', 'type' => 'string', 'description' => 'The player state ID / identifier'],
                ],
                'returns' => ['type' => 'table<string, boolean>', 'description' => 'Map of party UUIDs the player is in'],
                'lua_example' => "local identifier = GetPlayerIdentifier(source, 0)\nlocal parties = exports['prp-bridge']:GetPartiesFromPlayer(identifier)\nfor partyUuid in pairs(parties) do\n    print('In party:', partyUuid)\nend",
            ],

            // Sell Shops
            [
                'name' => 'RegisterSellShop',
                'category' => 'Sell Shops',
                'side' => 'server',
                'description' => 'Registers a sell shop with accepted items and prices. Safe to call repeatedly — re-registering the same ID does nothing.',
                'parameters' => [
                    ['name' => 'id', 'type' => 'string', 'description' => 'Unique shop identifier'],
                    ['name' => 'payload', 'type' => 'table', 'description' => 'Config table with label, items (keyed by item name with label and price), optional reason and coords'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "exports['prp-bridge']:RegisterSellShop('pawn_shop', {\n    label = 'Pawn Shop',\n    items = {\n        ['gold_bar'] = { label = 'Gold Bar', price = 500 },\n        ['silver_bar'] = { label = 'Silver Bar', price = 200 },\n        ['diamond'] = { label = 'Diamond', price = 1000 },\n    }\n})",
            ],
            [
                'name' => 'OpenSellShop',
                'category' => 'Sell Shops',
                'side' => 'server',
                'description' => 'Opens a registered sell shop for a player. The player sees a stash UI and items are consumed for cash on move.',
                'parameters' => [
                    ['name' => 'source', 'type' => 'number', 'description' => 'The player server ID'],
                    ['name' => 'id', 'type' => 'string', 'description' => 'The shop ID registered with RegisterSellShop'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "RegisterNetEvent('myresource:openShop', function()\n    local src = source\n    -- Register before opening (safe to call every time)\n    exports['prp-bridge']:RegisterSellShop('pawn_shop', {\n        label = 'Pawn Shop',\n        items = {\n            ['gold_bar'] = { label = 'Gold Bar', price = 500 },\n        }\n    })\n    exports['prp-bridge']:OpenSellShop(src, 'pawn_shop')\nend)",
            ],

            // Cases
            [
                'name' => 'CreateCase',
                'category' => 'Cases',
                'side' => 'server',
                'description' => 'Registers a new loot case type. All item chances must sum to exactly 100.',
                'parameters' => [
                    ['name' => 'caseId', 'type' => 'string', 'description' => 'Unique case identifier (e.g. "CRIME_CASE")'],
                    ['name' => 'payload', 'type' => 'table', 'description' => 'Config table with optional label and required items array (each with name, chance, optional min/max/metadata/image)'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'true on success; false if item chances do not sum to 100'],
                'lua_example' => "local success = exports['prp-bridge']:CreateCase('ROBBERY_CASE', {\n    label = 'Robbery Loot',\n    items = {\n        { name = 'money',    chance = 50, min = 200, max = 800 },\n        { name = 'lockpick', chance = 30 },\n        { name = 'weapon_pistol', chance = 20 },\n    }\n})\nif not success then\n    print('Item chances must sum to 100!')\nend",
            ],
            [
                'name' => 'GetCase',
                'category' => 'Cases',
                'side' => 'server',
                'description' => 'Gets case data by its unique identifier.',
                'parameters' => [
                    ['name' => 'caseId', 'type' => 'string', 'description' => 'The case ID used in CreateCase'],
                ],
                'returns' => ['type' => 'CaseDataPayload?', 'description' => 'The case data table, or nil if not found'],
                'lua_example' => "local caseData = exports['prp-bridge']:GetCase('ROBBERY_CASE')\nif caseData then\n    print('Case label:', caseData.label)\n    print('Item count:', #caseData.items)\nend",
            ],
            [
                'name' => 'GetAllCases',
                'category' => 'Cases',
                'side' => 'server',
                'description' => 'Returns all registered case types and their item data.',
                'parameters' => [],
                'returns' => ['type' => 'CaseDataPayload[]', 'description' => 'Array of all registered case data tables'],
                'lua_example' => "local allCases = exports['prp-bridge']:GetAllCases()\nfor _, caseData in ipairs(allCases) do\n    print('Case:', caseData.label or 'unlabelled', '- Items:', #caseData.items)\nend",
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
                ->description('The prp-bridge server export name to look up (e.g. "startGlobalCooldown", "CreateGroup", "RegisterSellShop"). Omit to list all server exports.')
                ->default(''),
        ];
    }
}
