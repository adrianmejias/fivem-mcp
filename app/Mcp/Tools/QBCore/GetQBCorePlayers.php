<?php

namespace App\Mcp\Tools\QBCore;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Look up information about the QBCore.Players table structure and how to access player data. Describes player object properties and methods.')]
class GetQBCorePlayers extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $infoType = $request->get('info_type');
        $language = $request->get('language', 'lua');

        $info = $this->findPlayerInfo($infoType);

        if (! $info) {
            return Response::text(sprintf("QBCore player info '%s' not found. Available types: Structure, PlayerData, Methods, Events, Examples", $infoType));
        }

        $output = $this->formatPlayerInfo($info, $language);

        return Response::text($output);
    }

    /**
     * Find QBCore player information.
     */
    protected function findPlayerInfo(string $name): ?array
    {
        $playerInfo = [
            'Structure' => [
                'type' => 'Structure',
                'title' => 'QBCore.Players Table Structure',
                'description' => 'The QBCore.Players table on the server contains all currently connected players indexed by their source (net ID).',
                'properties' => [
                    ['name' => 'QBCore.Players[source]', 'type' => 'table', 'description' => 'Player object for given source ID'],
                    ['name' => 'PlayerData', 'type' => 'table', 'description' => 'All player data (citizenid, job, gang, money, items, etc.)'],
                    ['name' => 'Functions', 'type' => 'table', 'description' => 'Methods available on player object (AddItem, RemoveItem, etc.)'],
                    ['name' => 'Metadata', 'type' => 'table', 'description' => 'Player metadata (hunger, thirst, stress, etc.)'],
                    ['name' => 'Inventory', 'type' => 'table', 'description' => 'Current inventory items and slots'],
                ],
                'lua_example' => "-- Access a player by source (server-side)\nlocal Player = QBCore.Players[source]\nif Player then\n    print('Player found: ' .. Player.PlayerData.charinfo.firstname)\nend\n\n-- Better to use the function instead:\nlocal Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    print('Player: ' .. Player.Functions.GetName())\nend",
                'js_example' => "// Access a player by source (server-side)\nconst Player = QBCore.Players[global.source];\nif (Player) {\n    console.log(`Player found: \${Player.PlayerData.charinfo.firstname}`);\n}\n\n// Better to use the function instead:\nconst Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    console.log(`Player: \${Player.Functions.GetName()}`);\n}",
            ],
            'PlayerData' => [
                'type' => 'PlayerData',
                'title' => 'Player Data Properties',
                'description' => 'Complete structure of the PlayerData table containing all player information.',
                'properties' => [
                    ['name' => 'source', 'type' => 'number', 'description' => 'Player net ID (used to identify player)'],
                    ['name' => 'citizenid', 'type' => 'string', 'description' => 'Unique character identifier'],
                    ['name' => 'cid', 'type' => 'number', 'description' => 'Character ID in database'],
                    ['name' => 'charinfo', 'type' => 'table', 'description' => 'Character information (firstname, lastname, gender, birthdate, etc.)'],
                    ['name' => 'job', 'type' => 'table', 'description' => 'Job information (name, label, grade, payment, onduty)'],
                    ['name' => 'gang', 'type' => 'table', 'description' => 'Gang information (name, label, grade, isboss)'],
                    ['name' => 'money', 'type' => 'table', 'description' => 'Money table (cash, bank accounts)'],
                    ['name' => 'metadata', 'type' => 'table', 'description' => 'Player metadata (hunger, thirst, stress, armor, health)'],
                    ['name' => 'items', 'type' => 'table', 'description' => 'Inventory items'],
                    ['name' => 'position', 'type' => 'vector3', 'description' => 'Player position coordinates'],
                    ['name' => 'licenses', 'type' => 'table', 'description' => 'Licenses owned by player'],
                ],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    local pd = Player.PlayerData\n    print('Name: ' .. pd.charinfo.firstname .. ' ' .. pd.charinfo.lastname)\n    print('Citizen ID: ' .. pd.citizenid)\n    print('Job: ' .. pd.job.name .. ' (Grade: ' .. pd.job.grade.level .. ')')\n    print('Cash: $' .. pd.money.cash .. ' | Bank: $' .. pd.money.bank)\n    print('Hunger: ' .. pd.metadata.hunger)\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    const pd = Player.PlayerData;\n    console.log(`Name: \${pd.charinfo.firstname} \${pd.charinfo.lastname}`);\n    console.log(`Citizen ID: \${pd.citizenid}`);\n    console.log(`Job: \${pd.job.name} (Grade: \${pd.job.grade.level})`);\n    console.log(`Cash: \\\$\${pd.money.cash} | Bank: \\\$\${pd.money.bank}`);\n    console.log(`Hunger: \${pd.metadata.hunger}`);\n}",
            ],
            'Methods' => [
                'type' => 'Methods',
                'title' => 'Player Object Methods',
                'description' => 'Functions available on the Player object for manipulating player data.',
                'methods' => [
                    ['name' => 'AddItem(item, amount)', 'description' => 'Add item to player inventory'],
                    ['name' => 'RemoveItem(item, amount)', 'description' => 'Remove item from player inventory'],
                    ['name' => 'HasItem(item, amount)', 'description' => 'Check if player has item'],
                    ['name' => 'GetItemByName(item)', 'description' => 'Get item data by name'],
                    ['name' => 'AddMoney(type, amount, reason)', 'description' => 'Add money to player account'],
                    ['name' => 'RemoveMoney(type, amount, reason)', 'description' => 'Remove money from player account'],
                    ['name' => 'SetMoney(type, amount, reason)', 'description' => 'Set money to exact amount'],
                    ['name' => 'GetMoney(type)', 'description' => 'Get player money amount'],
                    ['name' => 'SetJob(job, grade)', 'description' => 'Set player job and grade'],
                    ['name' => 'GetJob()', 'description' => 'Get player job data'],
                    ['name' => 'SetJobDuty(onDuty)', 'description' => 'Set player on/off duty'],
                    ['name' => 'SetGang(gang, grade)', 'description' => 'Set player gang and grade'],
                    ['name' => 'SetPlayerData(key, value)', 'description' => 'Set player data by key'],
                    ['name' => 'SetMetaData(key, value)', 'description' => 'Set player metadata by key'],
                    ['name' => 'GetMetaData(key)', 'description' => 'Get player metadata by key'],
                    ['name' => 'AddRep(type, amount)', 'description' => 'Add reputation points'],
                    ['name' => 'RemoveRep(type, amount)', 'description' => 'Remove reputation points'],
                    ['name' => 'GetRep(type)', 'description' => 'Get reputation value'],
                    ['name' => 'Notify(text, type, duration)', 'description' => 'Send notification to player'],
                    ['name' => 'Save()', 'description' => 'Save player data to database'],
                    ['name' => 'UpdatePlayerData()', 'description' => 'Sync player data to client'],
                    ['name' => 'GetName()', 'description' => 'Get full player name'],
                    ['name' => 'Logout()', 'description' => 'Force player to logout'],
                ],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    -- Manage inventory\n    Player.Functions.AddItem('water', 5)\n    Player.Functions.RemoveItem('water', 2)\n    \n    -- Manage money\n    Player.Functions.AddMoney('cash', 1000, 'Salary')\n    local cash = Player.Functions.GetMoney('cash')\n    \n    -- Set job\n    Player.Functions.SetJob('police', 2)\n    Player.Functions.SetJobDuty(true)\n    \n    -- Notify player\n    Player.Functions.Notify('Welcome to the server!', 'success')\n    \n    -- Save changes\n    Player.Functions.Save()\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    // Manage inventory\n    Player.Functions.AddItem('water', 5);\n    Player.Functions.RemoveItem('water', 2);\n    \n    // Manage money\n    Player.Functions.AddMoney('cash', 1000, 'Salary');\n    const cash = Player.Functions.GetMoney('cash');\n    \n    // Set job\n    Player.Functions.SetJob('police', 2);\n    Player.Functions.SetJobDuty(true);\n    \n    // Notify player\n    Player.Functions.Notify('Welcome to the server!', 'success');\n    \n    // Save changes\n    Player.Functions.Save();\n}",
            ],
            'Events' => [
                'type' => 'Events',
                'title' => 'Player-Related Events',
                'description' => 'Events triggered when player data changes or player actions occur.',
                'events' => [
                    ['name' => 'QBCore:Player:SetPlayerData', 'description' => 'Triggered when player data is updated'],
                    ['name' => 'QBCore:Server:OnJobUpdate', 'description' => 'Triggered when player job is changed'],
                    ['name' => 'QBCore:Client:OnJobUpdate', 'description' => 'Client-side job update event'],
                    ['name' => 'QBCore:Player:PlayerLoaded', 'description' => 'Triggered when player finishes loading'],
                    ['name' => 'QBCore:Player:PlayerLoggedOut', 'description' => 'Triggered when player logs out'],
                ],
                'lua_example' => "-- Server-side: Listen for job updates\nRegisterNetEvent('QBCore:Server:OnJobUpdate', function(source)\n    local Player = QBCore.Functions.GetPlayer(source)\n    if Player then\n        print(Player.Functions.GetName() .. ' job updated to ' .. Player.PlayerData.job.name)\n    end\nend)\n\n-- Client-side: Listen for player load\nRegisterNetEvent('QBCore:Player:SetPlayerData')\nAddEventHandler('QBCore:Player:SetPlayerData', function()\n    local playerData = QBCore.Functions.GetPlayerData()\n    print('Player data updated!')\nend)",
                'js_example' => "// Server-side: Listen for job updates\non('QBCore:Server:OnJobUpdate', (source) => {\n    const Player = QBCore.Functions.GetPlayer(source);\n    if (Player) {\n        console.log(`\${Player.Functions.GetName()} job updated to \${Player.PlayerData.job.name}`);\n    }\n});\n\n// Client-side: Listen for player load\non('QBCore:Player:SetPlayerData', () => {\n    const playerData = QBCore.Functions.GetPlayerData();\n    console.log('Player data updated!');\n});",
            ],
            'Examples' => [
                'type' => 'Examples',
                'title' => 'Common Player Usage Examples',
                'description' => 'Real-world examples of working with player objects in QBCore.',
                'lua_example' => "-- Example 1: Check if player has items before proceeding\nlocal Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    if Player.Functions.HasItem('police_badge', 1) then\n        print('Player is a police officer')\n    end\nend\n\n-- Example 2: Transfer money between players\nlocal Player1 = QBCore.Functions.GetPlayer(source)\nlocal Player2 = QBCore.Functions.GetPlayerByCitizenId(citizenid)\nif Player1 and Player2 then\n    local amount = 500\n    if Player1.Functions.RemoveMoney('cash', amount) then\n        Player2.Functions.AddMoney('cash', amount, 'Player Transfer')\n        Player1.Functions.Notify('Gave \$' .. amount .. ' to ' .. Player2.Functions.GetName(), 'success')\n        Player2.Functions.Notify('Received \$' .. amount .. ' from ' .. Player1.Functions.GetName(), 'success')\n    end\nend\n\n-- Example 3: Job paycheck system\nfor i = 1, #QBCore.Functions.GetPlayers() do\n    local Player = QBCore.Functions.GetPlayer(QBCore.Functions.GetPlayers()[i])\n    if Player and Player.PlayerData.job.onduty then\n        local payment = Player.PlayerData.job.payment\n        Player.Functions.AddMoney('bank', payment, 'Salary')\n        Player.Functions.Notify('Received \$' .. payment .. ' salary', 'success')\n    end\nend",
                'js_example' => "// Example 1: Check if player has items before proceeding\nconst Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    if (Player.Functions.HasItem('police_badge', 1)) {\n        console.log('Player is a police officer');\n    }\n}\n\n// Example 2: Transfer money between players\nconst Player1 = QBCore.Functions.GetPlayer(global.source);\nconst Player2 = QBCore.Functions.GetPlayerByCitizenId(citizenid);\nif (Player1 && Player2) {\n    const amount = 500;\n    if (Player1.Functions.RemoveMoney('cash', amount)) {\n        Player2.Functions.AddMoney('cash', amount, 'Player Transfer');\n        Player1.Functions.Notify(`Gave \\\$\${amount} to \${Player2.Functions.GetName()}`, 'success');\n        Player2.Functions.Notify(`Received \\\$\${amount} from \${Player1.Functions.GetName()}`, 'success');\n    }\n}\n\n// Example 3: Job paycheck system\nconst players = QBCore.Functions.GetPlayers();\nfor (const playerId of players) {\n    const Player = QBCore.Functions.GetPlayer(playerId);\n    if (Player && Player.PlayerData.job.onduty) {\n        const payment = Player.PlayerData.job.payment;\n        Player.Functions.AddMoney('bank', payment, 'Salary');\n        Player.Functions.Notify(`Received \\\$\${payment} salary`, 'success');\n    }\n}",
            ],
        ];

        $nameLower = strtolower($name);

        foreach ($playerInfo as $infoName => $infoData) {
            if (strtolower($infoName) === $nameLower) {
                return $infoData;
            }
        }

        return null;
    }

    /**
     * Format player info.
     */
    protected function formatPlayerInfo(array $info, string $language): string
    {
        return view('mcp.qbcore.qbcore-players', [
            'info' => $info,
            'language' => $language,
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
            'info_type' => $schema
                ->string()
                ->description('The type of QBCore player information to look up (Structure, PlayerData, Methods, Events, Examples)')
                ->enum(['Structure', 'PlayerData', 'Methods', 'Events', 'Examples'])
                ->required(),
            'language' => $schema
                ->string()
                ->enum(['lua', 'js'])
                ->description('The scripting language for code examples')
                ->default('lua'),
        ];
    }
}
