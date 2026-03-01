<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Look up COX MySQL functions and methods by name. Returns function signature, parameters, return type, and usage examples.')]
class GetCOXFunction extends Tool
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
            return Response::text(sprintf("COX function '%s' not found. Check COX documentation at https://coxdocs.dev/", $functionName));
        }

        $output = $this->formatFunctionInfo($function, $language);

        return Response::text($output);
    }

    /**
     * Find a COX function.
     */
    protected function findFunction(string $name): ?array
    {
        $functions = [
            // Query Methods
            'MySQL.query' => [
                'namespace' => 'Query',
                'description' => 'Execute a MySQL query with optional parameters (prepared statement)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'sql', 'type' => 'string', 'description' => 'SQL query with ? placeholders for prepared statements'],
                    ['name' => 'params', 'type' => 'table|nil', 'description' => 'Array of parameters to bind to placeholders'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'True if query succeeded, false otherwise'],
                'lua_example' => "MySQL.query('SELECT * FROM users WHERE id = ?', { 1 }, function(result)\n    if result then\n        for i = 1, #result do\n            print(result[i].name)\n        end\n    end\nend)",
                'js_example' => "MySQL.query('SELECT * FROM users WHERE id = ?', [1], (result) => {\n    if (result) {\n        result.forEach(row => console.log(row.name));\n    }\n});",
            ],
            'MySQL.insert' => [
                'namespace' => 'Query',
                'description' => 'Insert a row into the database and get the insert ID',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'tableName', 'type' => 'string', 'description' => 'Name of the table'],
                    ['name' => 'data', 'type' => 'table', 'description' => 'Table with column names as keys and values'],
                    ['name' => 'callback', 'type' => 'function|nil', 'description' => 'Optional callback function'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "MySQL.insert('users', {\n    username = 'player',\n    money = 500\n}, function(id)\n    print('Inserted with ID: ' .. id)\nend)",
                'js_example' => "MySQL.insert('users', {\n    username: 'player',\n    money: 500\n}, (id) => {\n    console.log('Inserted with ID: ' + id);\n});",
            ],
            'MySQL.update' => [
                'namespace' => 'Query',
                'description' => 'Update rows in the database where a condition matches',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'tableName', 'type' => 'string', 'description' => 'Name of the table'],
                    ['name' => 'data', 'type' => 'table', 'description' => 'Column names and new values'],
                    ['name' => 'conditions', 'type' => 'string|table', 'description' => 'WHERE clause or conditions table'],
                    ['name' => 'callback', 'type' => 'function|nil', 'description' => 'Optional callback function'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "MySQL.update('users', {\n    money = 1000\n}, { id = 5 }, function(affectedRows)\n    print('Updated ' .. affectedRows .. ' rows')\nend)",
                'js_example' => "MySQL.update('users', {\n    money: 1000\n}, { id: 5 }, (affectedRows) => {\n    console.log(`Updated \${affectedRows} rows`);\n});",
            ],
            'MySQL.scalar' => [
                'namespace' => 'Query',
                'description' => 'Get a single scalar value from the database (single result)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'sql', 'type' => 'string', 'description' => 'SQL query'],
                    ['name' => 'params', 'type' => 'table|nil', 'description' => 'Query parameters'],
                    ['name' => 'callback', 'type' => 'function', 'description' => 'Callback with the scalar value'],
                ],
                'returns' => ['type' => 'void', 'description' => 'Callback receives the value'],
                'lua_example' => "MySQL.scalar('SELECT COUNT(*) as count FROM users', {}, function(count)\n    print('Total users: ' .. (count or 0))\nend)",
                'js_example' => "MySQL.scalar('SELECT COUNT(*) as count FROM users', [], (count) => {\n    console.log('Total users: ' + (count || 0));\n});",
            ],
            'MySQL.single' => [
                'namespace' => 'Query',
                'description' => 'Get a single row from the database',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'sql', 'type' => 'string', 'description' => 'SQL query'],
                    ['name' => 'params', 'type' => 'table|nil', 'description' => 'Query parameters'],
                    ['name' => 'callback', 'type' => 'function', 'description' => 'Callback with the row data'],
                ],
                'returns' => ['type' => 'void', 'description' => 'Callback receives a single row or nil'],
                'lua_example' => "MySQL.single('SELECT * FROM users WHERE id = ?', { 1 }, function(user)\n    if user then\n        print('User: ' .. user.username)\n    end\nend)",
                'js_example' => "MySQL.single('SELECT * FROM users WHERE id = ?', [1], (user) => {\n    if (user) {\n        console.log('User: ' + user.username);\n    }\n});",
            ],

            // Transaction Methods
            'MySQL.transaction.begin' => [
                'namespace' => 'Transaction',
                'description' => 'Begin a new database transaction',
                'side' => 'server',
                'parameters' => [],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "MySQL.transaction.begin()\nMySQL.update('users', { money = money - 500 }, { id = 1 })\nMySQL.update('users', { money = money + 500 }, { id = 2 })\nMySQL.transaction.commit()",
                'js_example' => "MySQL.transaction.begin();\nMySQL.update('users', { money: 'money - 500' }, { id: 1 });\nMySQL.update('users', { money: 'money + 500' }, { id: 2 });\nMySQL.transaction.commit();",
            ],
            'MySQL.transaction.commit' => [
                'namespace' => 'Transaction',
                'description' => 'Commit the current transaction',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'callback', 'type' => 'function|nil', 'description' => 'Optional callback after commit'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "MySQL.transaction.commit(function(success)\n    if success then\n        print('Transaction committed successfully')\n    else\n        print('Transaction commit failed')\n    end\nend)",
                'js_example' => "MySQL.transaction.commit((success) => {\n    if (success) {\n        console.log('Transaction committed successfully');\n    } else {\n        console.log('Transaction commit failed');\n    }\n});",
            ],
            'MySQL.transaction.rollback' => [
                'namespace' => 'Transaction',
                'description' => 'Rollback the current transaction',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'callback', 'type' => 'function|nil', 'description' => 'Optional callback after rollback'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "if error then\n    MySQL.transaction.rollback(function(success)\n        print('Transaction rolled back')\n    end)\nend",
                'js_example' => "if (error) {\n    MySQL.transaction.rollback((success) => {\n        console.log('Transaction rolled back');\n    });\n}",
            ],

            // Connection Methods
            'MySQL.ready' => [
                'namespace' => 'Connection',
                'description' => 'Check if the database connection is ready',
                'side' => 'server',
                'parameters' => [],
                'returns' => ['type' => 'boolean', 'description' => 'True if connection is ready'],
                'lua_example' => "if MySQL.ready then\n    MySQL.query('SELECT 1', function(result)\n        print('Database is connected!')\n    end)\nend",
                'js_example' => "if (MySQL.ready) {\n    MySQL.query('SELECT 1', (result) => {\n        console.log('Database is connected!');\n    });\n}",
            ],
            'MySQL.getStatus' => [
                'namespace' => 'Connection',
                'description' => 'Get the current MySQL connection status',
                'side' => 'server',
                'parameters' => [],
                'returns' => ['type' => 'string', 'description' => 'Status string (connected, connecting, disconnected, etc.)'],
                'lua_example' => "local status = MySQL.getStatus()\nprint('Connection status: ' .. status)",
                'js_example' => "const status = MySQL.getStatus();\nconsole.log('Connection status: ' + status);",
            ],

            // Prepared Statements
            'MySQL.prepare' => [
                'namespace' => 'Prepared',
                'description' => 'Prepare a query for execution with bound parameters (prevents SQL injection)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'sql', 'type' => 'string', 'description' => 'SQL with ? placeholders'],
                    ['name' => 'params', 'type' => 'table', 'description' => 'Parameters to bind'],
                    ['name' => 'callback', 'type' => 'function', 'description' => 'Callback with results'],
                ],
                'returns' => ['type' => 'void', 'description' => 'Callback receives prepared statement result'],
                'lua_example' => "MySQL.prepare('INSERT INTO logs (user_id, action) VALUES (?, ?)', { 5, 'joined' }, function(success)\n    if success then\n        print('Log inserted')\n    end\nend)",
                'js_example' => "MySQL.prepare('INSERT INTO logs (user_id, action) VALUES (?, ?)', [5, 'joined'], (success) => {\n    if (success) {\n        console.log('Log inserted');\n    }\n});",
            ],

            // Async Methods
            'MySQL.async.query' => [
                'namespace' => 'Async',
                'description' => 'Execute an async query (returns promise-like structure)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'sql', 'type' => 'string', 'description' => 'SQL query'],
                    ['name' => 'params', 'type' => 'table|nil', 'description' => 'Query parameters'],
                ],
                'returns' => ['type' => 'table', 'description' => 'Awaitable result table'],
                'lua_example' => "local result = MySQL.async.query('SELECT * FROM users WHERE banned = ?', { 0 })\nprint('Query completed')",
                'js_example' => "const result = MySQL.async.query('SELECT * FROM users WHERE banned = ?', [0]);\nconsole.log('Query completed');",
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
        return view('mcp.cox-function', [
            'function' => $function,
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
            'function_name' => $schema
                ->string()
                ->description('The name of the COX function to look up (e.g., "MySQL.query", "MySQL.insert", "MySQL.transaction.begin")')
                ->required(),
            'language' => $schema
                ->string()
                ->enum(['lua', 'js'])
                ->description('The scripting language for code examples')
                ->default('lua'),
        ];
    }
}
