import { defineStore } from 'pinia'

export const useLastFiveQuestions = defineStore('lastFive', {
    state: () => ({ lastFive: [] }),
    actions: {
        setLastFive(lastFive){
            this.lastFive = lastFive
        },
        newQuestion(question) {
            this.lastFive.unshift(question)
            this.lastFive.pop()
        }
    },
    getters: {
        getQuestions(state) {
            return state.lastFive
        }
    },
})