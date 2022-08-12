<template>
    <div class="container my-5 p-5 bg-dark text-light">
        <div v-if="question" class="bg-secondary container mb-3">
            <div class="border-bottom py-3">
                <h1 class="text-center">{{ question.question }}</h1>
                <p class="text-center">{{ question.description }}</p>
            </div>
            <div class="d-flex justify-content-between">
                <div>{{ question.user.nickname }}</div>
                <div>{{ question.created_at }}</div>
            </div>
        </div>
        <div class="bg-secondary container mb-3">
            <h3>Answers</h3>
            <div v-for="(answer, a ) in answers" :key="a">
                <p>{{ answer.answer }}</p>
            </div>
        </div>
        <div class="container">
            <label for="answer" class="form-label">
                <h3>Your Answer</h3>
            </label>
            <textarea v-model="answer.answer" placeholder="Your Answer..." class="form-control" id="answer"
                rows="3"></textarea>
            <Button @click="sendAnswer" :loading="answering" type="primary" class="mt-3">Send Answer</Button>
        </div>


    </div>

</template>

<script>
import { ref } from 'vue'
import { useToast } from 'vue-toastification'
import useCallApi from '../composables/useCallApi'
import { useRoute } from 'vue-router'
export default {
    setup() {
        const toast = useToast()
        const question = ref(null)
        const answers = ref([])

        //get id from route param
        const route = useRoute()

        const getQuestion = async () => {
            const res = await useCallApi('get', `/get_single_question?id=${route.params.id}`)

            if (res.status == 200) {
                question.value = res.data.question
                answers.value = res.data.answers.data
            } else {
                toast.error('Failed to load the question!')
            }
        }

        getQuestion()

        //Answer
        const answer = ref({
            answer: '',
            question_id: route.params.id
        });
        const answering = ref(false)

        const sendAnswer = async () => {
            if (answer.value.answer.trim().length < 2) return toast.warning('Your answer must be at least 2 characters!')

            answering.value = true

            const res = await useCallApi('post', '/create_answer', answer.value)

            if (res.status == 201) {
                answers.value.push(answer.value)
                toast.success('Sucessful answering!')
            } else {
                toast.error(res.data.msg)
            }
            answering.value = false
        }

        return { question, answers, answer, sendAnswer, answering }
    }
}
</script>