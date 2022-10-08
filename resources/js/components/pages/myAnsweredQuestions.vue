<template>
    <displayQuestions title="My Answered Questions" :get_url="getUrl"> </displayQuestions>
</template>

<script>
import { computed } from '@vue/reactivity';
import displayQuestions from '../partials/displayQuestions.vue';
export default {
    components: { displayQuestions },
    setup() {
        //it must be computed, so displayQuestions sees the prop change
        const getUrl = computed(() => (`/get_my_answered_questions?nothing=${'nothing'}`))

        return { getUrl }
    }

}
</script>