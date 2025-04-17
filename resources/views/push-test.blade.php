<!DOCTYPE html>
<html>

<head>
    <title>Firebase Push Test</title>
</head>

<body>
    <h1>Firebase Web Push Token Generator</h1>

    <button id="get-token-btn">Get Token</button>

    <div id="token-display"
        style="margin-top: 20px; padding: 10px; background: #f0f0f0; border-radius: 5px; max-width: 800px; word-break: break-word;">
    </div>

    <!-- Firebase SDK v8 -->
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var firebaseConfig = {
                apiKey: "AIzaSyBkGxTIkwa48TQHXDqFSIWbuMvDFbI_5ZU",
                authDomain: "siginifynotification.firebaseapp.com",
                projectId: "siginifynotification",
                storageBucket: "siginifynotification.firebasestorage.app",
                messagingSenderId: "10288476650",
                appId: "1:10288476650:web:1d26d433dfd18e804cc293",
                measurementId: "G-TCJ49LGCRX"
            };

            firebase.initializeApp(firebaseConfig);

            const messaging = firebase.messaging();

            // Register service worker
            navigator.serviceWorker.register('/firebase-messaging-sw.js')
                .then(function(registration) {
                    console.log('Service Worker registered:', registration.scope);

                    messaging.useServiceWorker(registration); //This is important!

                    document.getElementById('get-token-btn').addEventListener('click', async () => {
                        const output = document.getElementById('token-display');
                        try {
                            const permission = await Notification.requestPermission();
                            if (permission === 'granted') {
                                const token = await messaging.getToken({
                                    vapidKey: 'BLIJgM24zNv_S5EExKbUxIAxW_snJ_jYUREhGXUDlFBcRg9qV8Mknwd6gS11PH9LsdKCHKQmRbd19ZozRLK1zfY'
                                });
                                output.innerHTML = `<strong>FCM Token:</strong><br>${token}`;
                            } else {
                                output.innerText = "Permission denied.";
                            }
                        } catch (err) {
                            output.innerText = "Error getting token: " + err.message;
                            console.error(err);
                        }
                    });

                }).catch(function(err) {
                    console.error('Service Worker registration failed:', err);
                });
        });
    </script>

</body>

</html>
