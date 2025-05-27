// resources/js/app.js

// 1) Bootstrap and base setup
import './bootstrap'

// 2) AlpineJS core
import Alpine from 'alpinejs'

// 3) AlpineJS plugins
import intersect from '@alpinejs/intersect'

// 4) Register AlpineJS + plugins
Alpine.plugin(intersect)
window.Alpine = Alpine

// 5) Laravel Echo (optional: only if using notifications or real-time features)
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

window.Pusher = Pusher
window.Echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_KEY,
  cluster: import.meta.env.VITE_PUSHER_CLUSTER,
  encrypted: true,
})

// 6) Example Alpine store for notifications (optional)
document.addEventListener('alpine:init', () => {
  console.log('🛠 alpine:init fired')

  Alpine.data('notifications', () => ({
    open: false,
    count: window.LaravelNotifications?.count || 0,
    list: window.LaravelNotifications?.list || [],

    init() {
      if (window.LaravelNotifications?.userId) {
        Echo.private(`App.Models.User.${window.LaravelNotifications.userId}`)
          .notification(notification => {
            this.count++
            this.list.unshift(notification)
          })
      }
    },

    markAllRead() {
      return fetch(window.LaravelNotifications.readUrl, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': window.LaravelNotifications.csrfToken,
          'Content-Type': 'application/json',
        },
      }).then(() => {
        this.count = 0
        this.list = []
      })
    }
  }))
})

// 7) Start Alpine
Alpine.start()
console.log('🛠 Alpine started')
import { Calendar } from '@fullcalendar/core'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'

window.initFullCalendar = function (el, events = []) {
  const calendar = new Calendar(el, {
    plugins: [dayGridPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    selectable: true,
    events: events,
  })
  calendar.render()
}
