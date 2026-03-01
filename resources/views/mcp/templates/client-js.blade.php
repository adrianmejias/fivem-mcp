// Client-side script

@if($framework === 'esx')
let ESX = null;

on('esx:getSharedObject', (obj) => { ESX = obj; });

@elseif($framework === 'qbcore')
const QBCore = exports['qb-core'].GetCoreObject();

@endif
// Resource started
on('onClientResourceStart', (resourceName) => {
    if (GetCurrentResourceName() !== resourceName) return;

    console.log('Resource started on client');

    // Your initialization code here
});

// Example command
RegisterCommand('test', () => {
    emit('chat:addMessage', {
        args: ['Script', 'Test command executed!']
    });
}, false);

// Example thread
setInterval(() => {
    // Your periodic code here
}, 1000);
