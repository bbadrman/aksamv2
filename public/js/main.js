if ('Notification' in window && 'Worker' in window) {
    console.log('TZ0;', Notification.permission)
    if (Notification.permission !== 'granted') {
        Notification.requestPermission().then(permission => {
            if (permission !== 'granted') {
                alert('Notifications are disabled. Enable them to receive updates.');
            }
        });
    }

    // Create a new web worker
    const worker = new Worker('/js/worker.js');

    // Pass the API URL to the worker
    worker.postMessage({ apiUrl: '/prospect/newprospectApi' });

    // Listen for messages from the worker
    worker.onmessage = function (e) {
        console.log('MESSAGE', e.data);
        if (Number(e.data) > 0) {
            showNotification(e.data);
            updateCountInDOM(e.data);
        }
    };
} else {
    alert('Your browser does not support Web Workers or Notifications.');
}
let userRole = 'ROLE_ADMIN';
let notificationSound;



// Function to initialize the sound
function initNotificationSound() {
    if (!notificationSound) {
        if (userRole === 'ROLE_ADMIN') {
            notificationSound = new Audio('/sounds/noutifactionAksamAdmin.mp3');
        } else {
            notificationSound = new Audio('/sounds/noutifactionAksamAdmin.mp3');
        }
        notificationSound.load(); // Preload the sound
    }
}
// Function to show a notification
function showNotification(prospectionCount) {
    console.log('SHOW', Notification.permission);
    if (Notification.permission === 'granted') {
        console.log('SEND NOTIF');
        new Notification('New Prospection', {
            body: `Vous avez ${prospectionCount} nouvelles prospections!`,
            //icon: '/icon.png' // Path to an icon image
        });
        if (notificationSound) {
            notificationSound.play().catch(error => {
                console.error('Error playing notification sound:', error);
            });
        }
    }
}

// Function to update the count in the DOM
function updateCountInDOM(prospectionCount) {
    const element = document.getElementById('notif');
    const container = document.getElementById('notif-container');
    if (element && container) {
        const oldValue = element.innerHTML.trim();
        const newValue = prospectionCount.toString().padStart(2, '0');
        if (oldValue === newValue) {
            return;
        }
        console.log('OOOOLD', oldValue);
        element.innerHTML = newValue;
        container.classList.remove('green-panel');
        container.classList.add('red-panel');

        setTimeout(function () {
            container.classList.remove('red-panel');
            container.classList.add('green-panel');
        }, 800);
    }
}

// Event listener for user interaction to initialize the sound
document.addEventListener('click', initNotificationSound, { once: true });

if ('Notification' in window && 'Worker' in window) {
    console.log('TZ0;', Notification.permission);
    if (Notification.permission !== 'granted') {
        Notification.requestPermission().then(permission => {
            if (permission !== 'granted') {
                alert('Notifications are disabled. Enable them to receive updates.');
            }
        });
    }

    // Create a new web worker
    const worker = new Worker('/js/worker.js');

    // Pass the API URL to the worker
    worker.postMessage({ apiUrl: '/prospect/newprospectApi' });

    // Listen for messages from the worker
    worker.onmessage = function (e) {
        console.log('MESSAGE', e.data);
        if (Number(e.data) > 0) {
            showNotification(e.data);
            updateCountInDOM(e.data);
        }
    };
} else {
    alert('Your browser does not support Web Workers or Notifications.');
}
