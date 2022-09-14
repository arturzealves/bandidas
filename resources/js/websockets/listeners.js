window.addEventListener('load', function () {

    window.Echo.private(`user.reputation.` + window.getCookie('userId')).listen(
        '.QCod\\Gamify\\Events\\ReputationChanged',
        (e) => {
            var notyf = new Notyf();

            if (e.point == 1) {
                notyf.success(`You got ${e.point} point!`);
            } else {
                notyf.success(`You got ${e.point} points!`);
            }
        }
    );
});
