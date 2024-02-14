self.addEventListener('push', function(event) {

    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }


  console.log('Received a push message', event);

  var title = 'Vie';
  var body = 'Hello world';
  var icon = 'https://vie.eduwise.com.au/assets/pwa-icons/logo-256x256.png';
  var tag = 'simple-push-demo-notification-tag';
  var data = {
    doge: {
        wow: 'such amaze notification data'
    }
  };

  event.waitUntil(
    self.registration.showNotification(title, {
      body: body,
      icon: icon,
      tag: tag,
      data: data
    })
  );
});



self.addEventListener('notificationclick', function(e) {
  var notification = e.notification;
  var primaryKey = notification.data.primaryKey;
  var action = e.action;

  if (action === 'close') {
    notification.close();
  } else {
    clients.openWindow('http://vie.eduwise.com.au');
    notification.close();
  }
});
