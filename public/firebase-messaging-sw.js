importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyBxdXS2wQBFbwsPFvKo_rKKCRwx13IhYAc",
    projectId: "lifebalance-fe7f1",
    messagingSenderId: "776758297141",
    appId: "1:776758297141:web:dd5a25563de57eb0fd277c"
});

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function({data:{title,body,icon}}) {
    return self.registration.showNotification(title,{body,icon});
});
