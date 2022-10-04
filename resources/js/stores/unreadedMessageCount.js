import { defineStore } from 'pinia'

export const useUnreadedMessageCount = defineStore('unreadedMessageCount', {
    state: () => ({ unreadedMessageCount: [0] }),
    actions: {
        setCount(count) {
            this.unreadedMessageCount = count
        },
        readMessage() {
            this.unreadedMessageCount = this.unreadedMessageCount - 1
        },
    },
    getters: {
        getCount(state) {
            return state.unreadedMessageCount
        }
    },
})