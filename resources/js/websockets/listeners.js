window.addEventListener('load', function () {

    window.Echo.private(`user.reputation.` + window.getCookie('userId')).listen(
        '.QCod\\Gamify\\Events\\ReputationChanged',
        (e) => {
            console.log(e);
        }
    );
});
