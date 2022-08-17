<template>
    <b-modal hide-footer :title=title>
        <div class="mb-3">
            <label for="message" class="form-label">{{title}}</label>
            <textarea placeholder="Your message..." class="form-control" id="message" rows="3"></textarea>
        </div>
    </b-modal>
</template>

<script>
import { computed, ref } from 'vue'
export default {
    props: ["addressee"],
    setup(props) {
        const title = computed(() => 'Sending message to ' + props.addressee.nickname + ':')

        const message = ref('')


        return { title, message }
    }
}
</script>

