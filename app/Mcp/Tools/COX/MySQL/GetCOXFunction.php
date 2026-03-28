<?php

namespace App\Mcp\Tools\COX\MySQL;

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
                'description' => 'Execute a MySQL query with optional parameters. When selecting data, returns all matching rows; otherwise returns data like insertId, affectedRows, etc.',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'sql', 'type' => 'string', 'description' => 'SQL query with ? placeholders for prepared statements'],
                    ['name' => 'params', 'type' => 'table|nil', 'description' => 'Array of parameters to bind to placeholders'],
                    ['name' => 'callback', 'type' => 'function|nil', 'description' => 'Optional callback function that receives the query result'],
                ],
                'returns' => ['type' => 'table|nil', 'description' => 'Query result (rows for SELECT, or metadata for INSERT/UPDATE/DELETE)'],
                'lua_example' => "MySQL.query('SELECT `firstname`, `lastname` FROM `users` WHERE `identifier` = ?', {\n    identifier\n}, function(response)\n    if response then\n        for i = 1, #response do\n            local row = response[i]\n            print(row.firstname, row.lastname)\n        end\n    end\nend)",
                'js_example' => "MySQL.query('SELECT `firstname`, `lastname` FROM `users` WHERE `identifier` = ?', [\n    identifier\n], (response) => {\n    if (response) {\n        response.forEach(row => {\n            console.log(row.firstname, row.lastname);\n        });\n    }\n});",
            ],
            'MySQL.insert' => [
                'namespace' => 'Query',
                'description' => 'Insert a row into the database and get the insert ID',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'sql', 'type' => 'string', 'description' => 'INSERT SQL query with ? placeholders'],
                    ['name' => 'params', 'type' => 'table|nil', 'description' => 'Array of parameters to bind to placeholders'],
                    ['name' => 'callback', 'type' => 'function|nil', 'description' => 'Optional callback function that receives the insert ID'],
                ],
                'returns' => ['type' => 'number', 'description' => 'Insert ID (in callback mode) or awaitable insert ID (in promise mode)'],
                'lua_example' => "MySQL.insert('INSERT INTO `users` (identifier, firstname, lastname) VALUES (?, ?, ?)', {\n    identifier, firstName, lastName\n}, function(id)\n    print('Inserted with ID: ' .. id)\nend)",
                'js_example' => "MySQL.insert('INSERT INTO `users` (identifier, firstname, lastname) VALUES (?, ?, ?)', [\n    identifier, firstName, lastName\n], (id) => {\n    console.log('Inserted with ID: ' + id);\n});",
            ],
            'MySQL.update' => [
                'namespace' => 'Query',
                'description' => 'Update rows in the database and get the number of affected rows',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'sql', 'type' => 'string', 'description' => 'UPDATE SQL query with ? placeholders'],
                    ['name' => 'params', 'type' => 'table|nil', 'description' => 'Array of parameters to bind to placeholders'],
                    ['name' => 'callback', 'type' => 'function|nil', 'description' => 'Optional callback function that receives affected rows count'],
                ],
                'returns' => ['type' => 'number', 'description' => 'Number of affected rows (in callback mode) or awaitable affected rows (in promise mode)'],
                'lua_example' => "MySQL.update('UPDATE users SET firstname = ? WHERE identifier = ?', {\n    newName, identifier\n}, function(affectedRows)\n    print('Updated ' .. affectedRows .. ' rows')\nend)",
                'js_example' => "MySQL.update('UPDATE users SET firstname = ? WHERE identifier = ?', [\n    newName, identifier\n], (affectedRows) => {\n    console.log(`Updated \${affectedRows} rows`);\n});",
            ],
            'MySQL.scalar' => [
                'namespace' => 'Query',
                'description' => 'Returns the first column for a single row',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'sql', 'type' => 'string', 'description' => 'SQL query with ? placeholders'],
                    ['name' => 'params', 'type' => 'table|nil', 'description' => 'Query parameters'],
                    ['name' => 'callback', 'type' => 'function', 'description' => 'Callback that receives the scalar value'],
                ],
                'returns' => ['type' => 'any', 'description' => 'Single value from the first column of the first row'],
                'lua_example' => "MySQL.scalar('SELECT `firstname` FROM `users` WHERE `identifier` = ? LIMIT 1', {\n    identifier\n}, function(firstName)\n    print(firstName)\nend)",
                'js_example' => "MySQL.scalar('SELECT `firstname` FROM `users` WHERE `identifier` = ? LIMIT 1', [\n    identifier\n], (firstName) => {\n    console.log(firstName);\n});",
            ],
            'MySQL.single' => [
                'namespace' => 'Query',
                'description' => 'Returns all selected columns for a single row',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'sql', 'type' => 'string', 'description' => 'SQL query with ? placeholders'],
                    ['name' => 'params', 'type' => 'table|nil', 'description' => 'Query parameters'],
                    ['name' => 'callback', 'type' => 'function', 'description' => 'Callback that receives the row data'],
                ],
                'returns' => ['type' => 'table|nil', 'description' => 'Single row object or nil if no results'],
                'lua_example' => "MySQL.single('SELECT `firstname`, `lastname` FROM `users` WHERE `identifier` = ? LIMIT 1', {\n    identifier\n}, function(row)\n    if not row then return end\n    print(row.firstname, row.lastname)\nend)",
                'js_example' => "MySQL.single('SELECT `firstname`, `lastname` FROM `users` WHERE `identifier` = ? LIMIT 1', [\n    identifier\n], (row) => {\n    if (!row) return;\n    console.log(row.firstname, row.lastname);\n});",
            ],

            // Transaction Methods
            'MySQL.transaction' => [
                'namespace' => 'Transaction',
                'description' => 'Execute multiple queries as a transaction. If one fails, none are committed. Returns boolean indicating success.',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'queries', 'type' => 'table', 'description' => 'Array of query objects with query and values/parameters'],
                    ['name' => 'values', 'type' => 'table|function|nil', 'description' => 'Shared parameters for all queries (optional) or callback function'],
                    ['name' => 'callback', 'type' => 'function|nil', 'description' => 'Optional callback that receives boolean success result'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'True if transaction succeeded, false otherwise'],
                'lua_example' => "-- Specific format (unique params per query)\nlocal queries = {\n    { query = 'INSERT INTO `test` (id) VALUES (?)', values = { 1 } },\n    { query = 'INSERT INTO `test` (id, name) VALUES (?, ?)', values = { 2, 'bob' } },\n}\nMySQL.transaction(queries, function(success)\n    print(success)\nend)",
                'js_example' => "// Specific format (unique params per query)\nconst queries = [\n    { query: 'INSERT INTO `test` (id) VALUES (?)', values: [1] },\n    { query: 'INSERT INTO `test` (id, name) VALUES (?, ?)', values: [2, 'bob'] },\n];\nMySQL.transaction(queries, (success) => {\n    console.log(success);\n});",
            ],

            // Prepared Statements
            'MySQL.prepare' => [
                'namespace' => 'Prepared',
                'description' => 'Execute frequently called queries faster. Accepts multiple sets of parameters. Only supports ? value placeholders. Return format matches query/single/scalar based on result shape.',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'sql', 'type' => 'string', 'description' => 'SQL with ? value placeholders (no ?? column or named placeholders)'],
                    ['name' => 'params', 'type' => 'table', 'description' => 'Parameters to bind'],
                    ['name' => 'callback', 'type' => 'function', 'description' => 'Callback that receives results'],
                ],
                'returns' => ['type' => 'any', 'description' => 'Returns column, row, or array of rows depending on SELECT result shape'],
                'lua_example' => "MySQL.prepare('SELECT `firstname`, `lastname` FROM `users` WHERE `identifier` = ?', {\n    identifier\n}, function(response)\n    print(json.encode(response, { indent = true, sort_keys = true }))\nend)",
                'js_example' => "MySQL.prepare('SELECT `firstname`, `lastname` FROM `users` WHERE `identifier` = ?', [\n    identifier\n], (response) => {\n    console.log(JSON.stringify(response, null, 2));\n});",
            ],
            'MySQL.rawExecute' => [
                'namespace' => 'Prepared',
                'description' => 'Execute frequently called queries faster. Unlike prepare, SELECT always returns array of rows. Only supports ? value placeholders. Date and TINYINT/BIT types are not converted.',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'sql', 'type' => 'string', 'description' => 'SQL with ? value placeholders only'],
                    ['name' => 'params', 'type' => 'table', 'description' => 'Parameters to bind'],
                    ['name' => 'callback', 'type' => 'function', 'description' => 'Callback that receives results'],
                ],
                'returns' => ['type' => 'table', 'description' => 'Always returns array of rows for SELECT'],
                'lua_example' => "MySQL.rawExecute('SELECT `firstname`, `lastname` FROM `users` WHERE `identifier` = ?', {\n    identifier\n}, function(response)\n    print(json.encode(response, { indent = true, sort_keys = true }))\nend)",
                'js_example' => "MySQL.rawExecute('SELECT `firstname`, `lastname` FROM `users` WHERE `identifier` = ?', [\n    identifier\n], (response) => {\n    console.log(JSON.stringify(response, null, 2));\n});",
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
        return view('mcp.cox.cox-function', [
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
