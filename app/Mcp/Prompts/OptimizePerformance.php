<?php

namespace App\Mcp\Prompts;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Prompt;

#[Description('Optimize FiveM script performance')]
class OptimizePerformance extends Prompt
{
    public function handle(Request $request): Response
    {
        $code = $request->get('code', '');
        $scriptType = $request->get('script_type', 'client');

        $parts = ["Review and optimize this FiveM script for better performance:\n\n"];

        if ($code) {
            $parts[] = sprintf("```\n%s\n```\n\n", $code);
        }

        $parts[] = sprintf(
            "Script Type: %s\n\n%s\n%s\n%s\n%s\n%s\n%s\n\n%s\n%s\n%s\n%s\n%s\n%s",
            $scriptType,
            'Please analyze the code and:',
            '1. Identify performance issues (unnecessary loops, missing waits, etc.)',
            '2. Suggest optimizations with code examples',
            '3. Explain why each optimization improves performance',
            '4. Provide the optimized version of the code',
            '5. Reference FiveM best practices for performance',
            'Focus on:',
            '- Proper Citizen.Wait() usage',
            '- Distance checks before expensive operations',
            '- Caching frequently accessed values',
            '- Reducing server-client communication',
            '- Efficient event handling'
        );

        return Response::prompt(implode('', $parts));
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'code' => $schema->string()
                ->description('The code to optimize')
                ->default(''),
            'script_type' => $schema->enum(['client', 'server', 'shared'])
                ->description('Type of script')
                ->default('client'),
        ];
    }
}
