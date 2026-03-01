<?php

namespace App\Mcp\Prompts;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Prompt;

#[Description('Debug common FiveM issues and errors')]
class DebugIssues extends Prompt
{
    public function handle(Request $request): Response
    {
        $issue = $request->get('issue', '');
        $errorMessage = $request->get('error_message', '');

        $parts = ["Help me debug a FiveM issue:\n\n"];

        if ($issue) {
            $parts[] = sprintf("Issue Description: %s\n\n", $issue);
        }

        if ($errorMessage) {
            $parts[] = sprintf("Error Message: %s\n\n", $errorMessage);
        }

        $parts[] = sprintf(
            "%s\n%s\n%s\n%s\n%s\n%s\n\n%s",
            "Please analyze this issue and:",
            "1. Identify the likely cause",
            "2. Suggest fixes with code examples",
            "3. Explain common mistakes that lead to this issue",
            "4. Provide preventive measures",
            "5. Reference FiveM best practices if applicable",
            "Use the SearchFiveMDocs and GetNativeFunction tools to find relevant information."
        );

        return Response::prompt(implode('', $parts));
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'issue' => $schema->string()
                ->description('Description of the issue or problem')
                ->default(''),
            'error_message' => $schema->string()
                ->description('Error message from console or logs')
                ->default(''),
        ];
    }
}
