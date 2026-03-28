<?php

namespace App\Mcp\Tools\QBCore\Server;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Look up QBCore server-side functions and exports by name. Returns function signature, parameters, return type, and usage examples.')]
class GetQBCoreServerFunction extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $functionName = $request->get('function_name');
        $language = $request->get('language', 'lua');

        $function = $this->findFunction($functionName);

        if (! $function) {
            return Response::text(sprintf("QBCore server function '%s' not found. Check QBCore documentation at https://docs.qbcore.org/", $functionName));
        }

        $output = $this->formatFunctionInfo($function, $language);

        return Response::text($output);
    }

    /**
     * Find a QBCore server function.
     */
    protected function findFunction(string $name): ?array
    {
        $functions = [
            'QBCore.Functions.GetPlayer' => [
                'namespace' => 'Core',
                'description' => 'Get a QBCore player object from server ID (server-side only)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'source', 'type' => 'int', 'description' => 'The player server ID'],
                ],
                'returns' => ['type' => 'table', 'description' => 'QBCore player object or nil'],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    print('Player: ' .. Player.PlayerData.charinfo.firstname)\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    console.log('Player: ' + Player.PlayerData.charinfo.firstname);\n}",
            ],
            'QBCore.Functions.GetPlayerByCitizenId' => [
                'namespace' => 'Core',
                'description' => 'Get a player object by their citizen ID (server-side only)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'citizenId', 'type' => 'string', 'description' => 'The player citizen ID'],
                ],
                'returns' => ['type' => 'table', 'description' => 'QBCore player object or nil'],
                'lua_example' => "local Player = QBCore.Functions.GetPlayerByCitizenId('ABC12345')\nif Player then\n    TriggerClientEvent('QBCore:Notify', Player.PlayerData.source, 'Hello!')\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayerByCitizenId('ABC12345');\nif (Player) {\n    TriggerClientEvent('QBCore:Notify', Player.PlayerData.source, 'Hello!');\n}",
            ],
            'AddItem' => [
                'namespace' => 'Player',
                'description' => 'Add an item to a player\'s inventory (server-side)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'item', 'type' => 'string', 'description' => 'Item name'],
                    ['name' => 'amount', 'type' => 'int', 'description' => 'Amount to add (default 1)'],
                    ['name' => 'slot', 'type' => 'int|nil', 'description' => 'Optional specific slot'],
                    ['name' => 'info', 'type' => 'table|nil', 'description' => 'Optional item metadata/info'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'Success status'],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    local success = Player.Functions.AddItem('water', 1)\n    if success then\n        TriggerClientEvent('QBCore:Notify', source, 'Item added')\n    end\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    const success = Player.Functions.AddItem('water', 1);\n    if (success) {\n        TriggerClientEvent('QBCore:Notify', global.source, 'Item added');\n    }\n}",
            ],
            'RemoveItem' => [
                'namespace' => 'Player',
                'description' => 'Remove an item from a player\'s inventory (server-side)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'item', 'type' => 'string', 'description' => 'Item name'],
                    ['name' => 'amount', 'type' => 'int', 'description' => 'Amount to remove (default 1)'],
                    ['name' => 'slot', 'type' => 'int|nil', 'description' => 'Optional specific slot'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'Success status'],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    local success = Player.Functions.RemoveItem('water', 1)\n    if success then\n        TriggerClientEvent('QBCore:Notify', source, 'Item removed')\n    end\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    const success = Player.Functions.RemoveItem('water', 1);\n    if (success) {\n        TriggerClientEvent('QBCore:Notify', global.source, 'Item removed');\n    }\n}",
            ],
            'GetItemByName' => [
                'namespace' => 'Player',
                'description' => 'Get item data from a player\'s inventory (server-side)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'item', 'type' => 'string', 'description' => 'Item name to search for'],
                ],
                'returns' => ['type' => 'table|nil', 'description' => 'Item data table or nil if not found'],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    local item = Player.Functions.GetItemByName('water')\n    if item then\n        print('Found ' .. item.amount .. 'x ' .. item.name)\n    end\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    const item = Player.Functions.GetItemByName('water');\n    if (item) {\n        console.log(`Found \${item.amount}x \${item.name}`);\n    }\n}",
            ],
            'AddMoney' => [
                'namespace' => 'Player',
                'description' => 'Add money to a player\'s account (server-side)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'moneyType', 'type' => 'string', 'description' => 'Type: "cash", "bank", or custom'],
                    ['name' => 'amount', 'type' => 'int', 'description' => 'Amount to add'],
                    ['name' => 'reason', 'type' => 'string|nil', 'description' => 'Optional reason for logging'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'Success status'],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    Player.Functions.AddMoney('cash', 500, 'Job Payment')\n    TriggerClientEvent('QBCore:Notify', source, 'You earned \$500')\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    Player.Functions.AddMoney('cash', 500, 'Job Payment');\n    TriggerClientEvent('QBCore:Notify', global.source, 'You earned \$500');\n}",
            ],
            'RemoveMoney' => [
                'namespace' => 'Player',
                'description' => 'Remove money from a player\'s account (server-side)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'moneyType', 'type' => 'string', 'description' => 'Type: "cash", "bank", or custom'],
                    ['name' => 'amount', 'type' => 'int', 'description' => 'Amount to remove'],
                    ['name' => 'reason', 'type' => 'string|nil', 'description' => 'Optional reason for logging'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'Success status'],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    Player.Functions.RemoveMoney('cash', 500, 'Shop Purchase')\n    TriggerClientEvent('QBCore:Notify', source, 'Payment successful')\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    Player.Functions.RemoveMoney('cash', 500, 'Shop Purchase');\n    TriggerClientEvent('QBCore:Notify', global.source, 'Payment successful');\n}",
            ],
            'GetMoney' => [
                'namespace' => 'Player',
                'description' => 'Get a player\'s money amount (server-side)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'moneyType', 'type' => 'string', 'description' => 'Type: "cash", "bank", or custom'],
                ],
                'returns' => ['type' => 'int', 'description' => 'Money amount'],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    local cashAmount = Player.Functions.GetMoney('cash')\n    print('Cash: \$' .. cashAmount)\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    const cashAmount = Player.Functions.GetMoney('cash');\n    console.log('Cash: \$' + cashAmount);\n}",
            ],
            'SetJob' => [
                'namespace' => 'Player',
                'description' => 'Set a player\'s job (server-side)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'jobName', 'type' => 'string', 'description' => 'The job name'],
                    ['name' => 'grade', 'type' => 'int', 'description' => 'The job grade (usually 0 for basic)'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'Success status'],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    Player.Functions.SetJob('police', 0)\n    TriggerClientEvent('QBCore:Notify', source, 'You are now a police officer', 'success')\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    Player.Functions.SetJob('police', 0);\n    TriggerClientEvent('QBCore:Notify', global.source, 'You are now a police officer', 'success');\n}",
            ],
            'GetJob' => [
                'namespace' => 'Player',
                'description' => 'Get a player\'s job information (server-side)',
                'side' => 'server',
                'parameters' => [],
                'returns' => ['type' => 'table', 'description' => 'Job data table with name, label, grade, etc.'],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    local job = Player.Functions.GetJob()\n    print('Job: ' .. job.name .. ' (' .. job.grade.name .. ')')\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    const job = Player.Functions.GetJob();\n    console.log(`Job: \${job.name} (\${job.grade.name})`);\n}",
            ],
            'AddVehicle' => [
                'namespace' => 'Player',
                'description' => 'Add a vehicle to a player\'s garage (server-side)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'modelName', 'type' => 'string', 'description' => 'Vehicle model name (e.g., "adder")'],
                    ['name' => 'plate', 'type' => 'string', 'description' => 'License plate (usually unique identifier)'],
                    ['name' => 'properties', 'type' => 'table|nil', 'description' => 'Optional vehicle properties/customization'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'Success status'],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    Player.Functions.AddVehicle('adder', 'QBTEST')\n    TriggerClientEvent('QBCore:Notify', source, 'Vehicle added to garage')\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    Player.Functions.AddVehicle('adder', 'QBTEST');\n    TriggerClientEvent('QBCore:Notify', global.source, 'Vehicle added to garage');\n}",
            ],
            'DeleteVehicle' => [
                'namespace' => 'Player',
                'description' => 'Remove a vehicle from a player\'s garage (server-side)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'plate', 'type' => 'string', 'description' => 'License plate of vehicle to remove'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'Success status'],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    Player.Functions.DeleteVehicle('QBTEST')\n    TriggerClientEvent('QBCore:Notify', source, 'Vehicle removed from garage')\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    Player.Functions.DeleteVehicle('QBTEST');\n    TriggerClientEvent('QBCore:Notify', global.source, 'Vehicle removed from garage');\n}",
            ],
            'SetGang' => [
                'namespace' => 'Player',
                'description' => 'Set a player\'s gang and grade (server-side)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'gangName', 'type' => 'string', 'description' => 'The gang name'],
                    ['name' => 'grade', 'type' => 'number', 'description' => 'The gang grade/rank level'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'Success status'],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    Player.Functions.SetGang('lostmc', 1)\n    TriggerClientEvent('QBCore:Notify', source, 'You joined the gang', 'success')\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    Player.Functions.SetGang('lostmc', 1);\n    TriggerClientEvent('QBCore:Notify', global.source, 'You joined the gang', 'success');\n}",
            ],
            'Notify' => [
                'namespace' => 'Player',
                'description' => 'Send a notification to a player (server-side)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'text', 'type' => 'string', 'description' => 'Notification text'],
                    ['name' => 'type', 'type' => 'string', 'description' => 'Notification type: error, success, primary, warning'],
                    ['name' => 'duration', 'type' => 'number|nil', 'description' => 'Duration in milliseconds (default 5000)'],
                ],
                'returns' => ['type' => 'void', 'description' => ''],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    Player.Functions.Notify('Hello!', 'success', 5000)\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    Player.Functions.Notify('Hello!', 'success', 5000);\n}",
            ],
            'HasItem' => [
                'namespace' => 'Player',
                'description' => 'Check if a player has an item (server-side)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'item', 'type' => 'string|table', 'description' => 'Item name or array of item names'],
                    ['name' => 'amount', 'type' => 'number', 'description' => 'Amount required (default 1)'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'True if player has the item(s)'],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    if Player.Functions.HasItem('water_bottle', 1) then\n        print('Player has water')\n    end\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    if (Player.Functions.HasItem('water_bottle', 1)) {\n        console.log('Player has water');\n    }\n}",
            ],
            'GetName' => [
                'namespace' => 'Player',
                'description' => 'Get a player\'s full character name (server-side)',
                'side' => 'server',
                'parameters' => [],
                'returns' => ['type' => 'string', 'description' => 'Full name (first lastname)'],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    local fullName = Player.Functions.GetName()\n    print('Player name: ' .. fullName)\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    const fullName = Player.Functions.GetName();\n    console.log(`Player name: \${fullName}`);\n}",
            ],
            'SetJobDuty' => [
                'namespace' => 'Player',
                'description' => 'Set a player on/off duty for their job (server-side)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'onDuty', 'type' => 'boolean', 'description' => 'True to set on duty, false to set off duty'],
                ],
                'returns' => ['type' => 'void', 'description' => ''],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    Player.Functions.SetJobDuty(true)\n    Player.Functions.Notify('You are now on duty', 'success')\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    Player.Functions.SetJobDuty(true);\n    Player.Functions.Notify('You are now on duty', 'success');\n}",
            ],
            'SetPlayerData' => [
                'namespace' => 'Player',
                'description' => 'Set a player data value by key (server-side)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'key', 'type' => 'string', 'description' => 'The player data key to set'],
                    ['name' => 'value', 'type' => 'any', 'description' => 'The value to set'],
                ],
                'returns' => ['type' => 'void', 'description' => ''],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    Player.Functions.SetPlayerData('position', vector3(-425.3, 1123.6, 325.0))\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    Player.Functions.SetPlayerData('position', [-425.3, 1123.6, 325.0]);\n}",
            ],
            'SetMetaData' => [
                'namespace' => 'Player',
                'description' => 'Set a player metadata value by key (server-side)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'key', 'type' => 'string', 'description' => 'The metadata key to set'],
                    ['name' => 'value', 'type' => 'any', 'description' => 'The value to set'],
                ],
                'returns' => ['type' => 'void', 'description' => ''],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    Player.Functions.SetMetaData('hunger', 80)\n    Player.Functions.SetMetaData('thirst', 70)\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    Player.Functions.SetMetaData('hunger', 80);\n    Player.Functions.SetMetaData('thirst', 70);\n}",
            ],
            'GetMetaData' => [
                'namespace' => 'Player',
                'description' => 'Get a player metadata value by key (server-side)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'key', 'type' => 'string', 'description' => 'The metadata key to retrieve'],
                ],
                'returns' => ['type' => 'any', 'description' => 'The metadata value'],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    local hunger = Player.Functions.GetMetaData('hunger')\n    print('Hunger Level: ' .. hunger)\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    const hunger = Player.Functions.GetMetaData('hunger');\n    console.log(`Hunger Level: \${hunger}`);\n}",
            ],
            'AddRep' => [
                'namespace' => 'Player',
                'description' => 'Add reputation points to a player (server-side)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'repType', 'type' => 'string', 'description' => 'Type of reputation to add (e.g., selling, heist)'],
                    ['name' => 'amount', 'type' => 'number', 'description' => 'Amount of reputation to add'],
                ],
                'returns' => ['type' => 'void', 'description' => ''],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    Player.Functions.AddRep('selling', 10)\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    Player.Functions.AddRep('selling', 10);\n}",
            ],
            'RemoveRep' => [
                'namespace' => 'Player',
                'description' => 'Remove reputation points from a player (server-side)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'repType', 'type' => 'string', 'description' => 'Type of reputation to remove'],
                    ['name' => 'amount', 'type' => 'number', 'description' => 'Amount of reputation to remove'],
                ],
                'returns' => ['type' => 'void', 'description' => ''],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    Player.Functions.RemoveRep('selling', 5)\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    Player.Functions.RemoveRep('selling', 5);\n}",
            ],
            'GetRep' => [
                'namespace' => 'Player',
                'description' => 'Get a player\'s reputation value (server-side)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'repType', 'type' => 'string', 'description' => 'Type of reputation to retrieve'],
                ],
                'returns' => ['type' => 'number', 'description' => 'Reputation value'],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    local sellingRep = Player.Functions.GetRep('selling')\n    print('Selling Rep: ' .. sellingRep)\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    const sellingRep = Player.Functions.GetRep('selling');\n    console.log(`Selling Rep: \${sellingRep}`);\n}",
            ],
            'SetMoney' => [
                'namespace' => 'Player',
                'description' => 'Set a player\'s money to a specific amount (server-side)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'moneyType', 'type' => 'string', 'description' => 'Type: "cash", "bank", or custom'],
                    ['name' => 'amount', 'type' => 'number', 'description' => 'Amount to set'],
                    ['name' => 'reason', 'type' => 'string|nil', 'description' => 'Optional reason for logging'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'Success status'],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    Player.Functions.SetMoney('cash', 5000, 'Admin Command')\n    Player.Functions.Notify('Your cash has been set', 'info')\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    Player.Functions.SetMoney('cash', 5000, 'Admin Command');\n    Player.Functions.Notify('Your cash has been set', 'info');\n}",
            ],
            'Save' => [
                'namespace' => 'Player',
                'description' => 'Save a player\'s data to the database (server-side)',
                'side' => 'server',
                'parameters' => [],
                'returns' => ['type' => 'void', 'description' => ''],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    Player.Functions.Save()\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    Player.Functions.Save();\n}",
            ],
            'Logout' => [
                'namespace' => 'Player',
                'description' => 'Force a player to logout to the character selection (server-side)',
                'side' => 'server',
                'parameters' => [],
                'returns' => ['type' => 'void', 'description' => ''],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    Player.Functions.Logout()\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    Player.Functions.Logout();\n}",
            ],
            'UpdatePlayerData' => [
                'namespace' => 'Player',
                'description' => 'Update the player data on client and server (server-side)',
                'side' => 'server',
                'parameters' => [],
                'returns' => ['type' => 'void', 'description' => ''],
                'lua_example' => "local Player = QBCore.Functions.GetPlayer(source)\nif Player then\n    Player.Functions.UpdatePlayerData()\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayer(global.source);\nif (Player) {\n    Player.Functions.UpdatePlayerData();\n}",
            ],
            'GetPlayerBophone' => [
                'namespace' => 'Core',
                'description' => 'Get a player by their phone number (server-side only)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'phoneNumber', 'type' => 'string', 'description' => 'The phone number to search for'],
                ],
                'returns' => ['type' => 'table', 'description' => 'QBCore player object or nil'],
                'lua_example' => "local Player = QBCore.Functions.GetPlayerByPhone('555-0001')\nif Player then\n    print('Found player: ' .. Player.Functions.GetName())\nend",
                'js_example' => "const Player = QBCore.Functions.GetPlayerByPhone('555-0001');\nif (Player) {\n    console.log(`Found player: \${Player.Functions.GetName()}`);\n}",
            ],
            'GetPlayers' => [
                'namespace' => 'Core',
                'description' => 'Get table of all online player net IDs (server-side only)',
                'side' => 'server',
                'parameters' => [],
                'returns' => ['type' => 'table', 'description' => 'Array of all connected player net IDs'],
                'lua_example' => "local players = QBCore.Functions.GetPlayers()\nfor i = 1, #players do\n    local Player = QBCore.Functions.GetPlayer(players[i])\n    if Player then\n        print('Player: ' .. Player.Functions.GetName())\n    end\nend",
                'js_example' => "const players = QBCore.Functions.GetPlayers();\nfor (const playerId of players) {\n    const Player = QBCore.Functions.GetPlayer(playerId);\n    if (Player) {\n        console.log(`Player: \${Player.Functions.GetName()}`);\n    }\n}",
            ],
        ];

        $nameLower = strtolower($name);

        foreach ($functions as $functionName => $functionData) {
            if (strtolower($functionName) === $nameLower) {
                return array_merge(['name' => $functionName], $functionData);
            }
        }

        return null;
    }

    /**
     * Format function info.
     */
    protected function formatFunctionInfo(array $function, string $language): string
    {
        return view('mcp.qbcore.qbcore-function', [
            'function' => $function,
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
            'function_name' => $schema
                ->string()
                ->description('The name of the QBCore server function to look up (e.g., "QBCore.Functions.GetPlayer", "AddMoney", "SetJob", "SetGang", "Notify", "HasItem", "SetMetaData", "GetRep")')
                ->required(),
            'language' => $schema
                ->string()
                ->enum(['lua', 'js'])
                ->description('The scripting language for code examples')
                ->default('lua'),
        ];
    }
}
