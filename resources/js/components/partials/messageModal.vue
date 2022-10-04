<template>
    <b-modal hide-footer :title=title @hide="close">
        <div class="mb-3">
            <label for="subject" class="form-label">Subject:</label>
            <input v-model="subject" type="text" class="form-control" id="subject" placeholder="Your subject...">
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Message:</label>
            <textarea v-model="message" placeholder="Your message..." class="form-control" id="message"
                rows="3"></textarea>
        </div>
        <div class="text-end">
            <Button @click="sendMessage" :loading="sending" type="primary">Send</Button>
        </div>
    </b-modal>
</template>

<script>
import { computed, ref } from 'vue'
import useCallApi from "../composables/useCallApi";
import { useToast } from 'vue-toastification'
export default {
    props: ["addressee"],
    setup(props, context) {
        const toast = useToast()
        const title = computed(() => 'Sending message to ' + props.addressee.nickname + ':')
        const subject = ref('')
        const message = ref('')

        const sending = ref(false)
        const sendMessage = async () => {
            if (subject.value.trim().length < 3) return toast.warning('Subject must be at least 2 characters!')
            if (message.value.trim().length < 3) return toast.warning('Message must be at least 3 characters!')
            sending.value = true
            const res = await useCallApi('post', '/create_message', { addressee_id: props.addressee.id, subject: subject.value, message: message.value })

            if (res.status == 201) {
                context.emit('update:modelValue', false)
                toast.success('Message sended successfully!')
            } else {
                toast.error('Failed to send the message!')
            }
            sending.value = false
        }

        //reseting message on closing modal
        const close = () => {
            subject.value = ''
            message.value = ''
        }

        return { title, message, sendMessage, sending, close, subject }
    }
}
</script>

