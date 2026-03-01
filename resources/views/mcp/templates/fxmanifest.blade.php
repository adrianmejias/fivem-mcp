fx_version 'cerulean'
game 'gta5'

author '{{ $author }}'
description '{{ $description }}'
version '{{ $version }}'

@if($framework === 'esx')
shared_script '@es_extended/imports.lua'

@elseif($framework === 'qbcore')
shared_script '@qb-core/shared/locale.lua'

@endif
shared_scripts {
@if($framework === 'esx')
    '@es_extended/locale.lua',
    'locales/*.lua',
@endif
    'config.{{ $scriptExt }}'
}

@if($includeClient)
client_scripts {
    'client/main.{{ $scriptExt }}'
}

@endif
@if($includeServer)
server_scripts {
@if($framework === 'esx')
    '@mysql-async/lib/MySQL.lua',
@elseif($framework === 'qbcore')
    '@oxmysql/lib/MySQL.lua',
@endif
    'server/main.{{ $scriptExt }}'
}

@endif
-- Uncomment if using NUI
-- ui_page 'html/index.html'
--
-- files {
--     'html/index.html',
--     'html/style.css',
--     'html/script.js'
-- }

-- dependencies {
@if($framework === 'esx')
--     'es_extended'
@elseif($framework === 'qbcore')
--     'qb-core'
@endif
-- }

lua54 'yes'
