$(document).ready(async() => {

    $(".IconeMenuBar").click(() => {
        $("#barlateral").toggleClass('close');
    });
    $('#CHAT').click(() => {
        $("#barlateral").addClass('close');
    });

});