<?php

use App\Mcp\Servers\FiveMServer;
use Laravel\Mcp\Facades\Mcp;

Mcp::local('fivem', FiveMServer::class);

Mcp::web('fivem', FiveMServer::class)->name('fivem');
