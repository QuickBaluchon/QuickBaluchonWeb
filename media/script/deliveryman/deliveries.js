function stopDeliveries (id) {
    ajax(`/api/roadmap/${id}`, '', 'DELETE', ended) ;
}

function ended (text) {
    window.location.reload();
}