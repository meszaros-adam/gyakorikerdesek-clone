import { defineStore } from 'pinia'

export const useUserStore = defineStore("user", {
    state: () => ({ user: null }),
    actions: {
        setUser(user) {
            this.user = user
            if (user) {
                this.user.watched_tags = this.user.watched_tags.map(watched_tag => watched_tag.tag_id)
            }
        },
        addTagToWatchlist(tag) {
            this.user.watched_tags.push(tag)
        },
        removeTagFromWatchlist(tag) {
            this.user.watched_tags = this.user.watched_tags.filter(item => item !== tag)
        },
    },
    getters: {
        getUser(state) {
            return state.user
        }
    }

})