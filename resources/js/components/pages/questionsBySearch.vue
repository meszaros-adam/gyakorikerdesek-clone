<template>
    <displayQuestions :title="title" :get_url="getUrl"> </displayQuestions>
</template>

<script>
import { computed } from '@vue/reactivity';
import { useRoute } from 'vue-router'
import displayQuestions from '../partials/displayQuestions.vue';
export default {
    components: { displayQuestions },
    setup() {
        //get id from route param
        const route = useRoute()

        //it must be computed, so displayQuestions sees the prop change
        const getUrl = computed(() => (`/search_question?keyword=${route.params.keyword}`))
        const title = computed(() => (route.params.keyword))

        return { getUrl, title }
    }
}
</script>