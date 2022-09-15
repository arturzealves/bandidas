window.addEventListener('load', function () {

    const notyf = new Notyf({
        duration: 5000,
        position: {
            x: 'center',
            y: 'top'
        },
        dismissible: true
    });

    window.Echo.private(`user.reputation.` + window.getCookie('userId'))
        .listen('.QCod\\Gamify\\Events\\ReputationChanged', (e) => {
            console.log('ReputationChanged', e);


            if (e.point == 1) {
                notyf.success(`You got ${e.point} point!`);
            } else {
                notyf.success(`You got ${e.point} points!`);
            }
        })
        .listen('.App\\Gamify\\Events\\BadgeAwarded', (e) => {
            console.log('BadgeAwarded', e);

            notyf.success(`New badge: ${e.badge.description}`);
        });
});
