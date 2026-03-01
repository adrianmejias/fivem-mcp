// Server-side script

@if($framework === 'esx')
const ESX = exports['es_extended'].getSharedObject();

@elseif($framework === 'qbcore')
const QBCore = exports['qb-core'].GetCoreObject();

@endif
// Resource started
on('onServerResourceStart', (resourceName) => {
    if (GetCurrentResourceName() !== resourceName) return;

    console.log('Resource started on server');

    // Your initialization code here
});

// Player connecting
on('playerConnecting', (name, setKickReason, deferrals) => {
    const player = global.source;
    deferrals.defer();

    // Check player here

    deferrals.done();
});

// Example server event
onNet('resourceName:serverEvent', (data) => {
    const player = global.source;

    console.log(`Received event from player ${{ '{player}' }}`);

    // Trigger back to client
    emitNet('resourceName:clientEvent', player, { success: true });
});
