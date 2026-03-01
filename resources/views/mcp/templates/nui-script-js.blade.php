window.addEventListener('message', (event) => {
    const data = event.data;

    if (data.action === 'show') {
        $('#app').fadeIn();
    } else if (data.action === 'hide') {
        $('#app').fadeOut();
    }
});

$('#closeBtn').click(() => {
    $.post(`https://${{ '{GetParentResourceName()}' }}/close`, JSON.stringify({}));
    $('#app').fadeOut();
});

$(document).keyup((e) => {
    if (e.key === 'Escape') {
        $.post(`https://${{ '{GetParentResourceName()}' }}/close`, JSON.stringify({}));
        $('#app').fadeOut();
    }
});
