import { defineStore } from 'pinia'

export const useQuestionsPageData = defineStore('questionsPageData', {
    state: () => ({
        url: '',
        title: '',
    }),
    actions: {
        setData(url, title) {
            this.url = url
            this.title = title
        }
    },
    getters: {
        getUrl(state) {
            return state.url
        },
        getTitle(state) {
            return state.title
        }
    },
})