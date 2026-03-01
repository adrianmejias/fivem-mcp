window.addEventListener('message', function(event) {
    local data = event.data

    if data.action == 'show' then
        $('#app').fadeIn()
    elseif data.action == 'hide' then
        $('#app').fadeOut()
    end
end)

$('#closeBtn').click(function()
    $.post('https://' .. GetParentResourceName() .. '/close', JSON.stringify({}))
    $('#app').fadeOut()
end)

$(document).keyup(function(e)
    if e.key == 'Escape' then
        $.post('https://' .. GetParentResourceName() .. '/close', JSON.stringify({}))
        $('#app').fadeOut()
    end
end)
