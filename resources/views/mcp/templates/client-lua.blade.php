-- Client-side script

@if($framework === 'esx')
ESX = exports['es_extended']:getSharedObject()

@elseif($framework === 'qbcore')
local QBCore = exports['qb-core']:GetCoreObject()

@endif
-- Resource started
AddEventHandler('onClientResourceStart', function(resourceName)
    if GetCurrentResourceName() ~= resourceName then return end

    print('Resource started on client')

    -- Your initialization code here
end)

-- Example command
RegisterCommand('test', function()
    TriggerEvent('chat:addMessage', {
        args = { 'Script', 'Test command executed!' }
    })
end, false)

-- Example thread
CreateThread(function()
    while true do
        Wait(1000)

        -- Your periodic code here
    end
end)
