-- Server-side script

@if($framework === 'esx')
ESX = exports['es_extended']:getSharedObject()

@elseif($framework === 'qbcore')
local QBCore = exports['qb-core']:GetCoreObject()

@endif
-- Resource started
AddEventHandler('onServerResourceStart', function(resourceName)
    if GetCurrentResourceName() ~= resourceName then return end

    print('Resource started on server')

    -- Your initialization code here
end)

-- Player connecting
AddEventHandler('playerConnecting', function(name, setKickReason, deferrals)
    local player = source
    deferrals.defer()

    -- Check player here

    deferrals.done()
end)

-- Example server event
RegisterNetEvent('resourceName:serverEvent', function(data)
    local player = source

    print('Received event from player ' .. player)

    -- Trigger back to client
    TriggerClientEvent('resourceName:clientEvent', player, { success = true })
end)
