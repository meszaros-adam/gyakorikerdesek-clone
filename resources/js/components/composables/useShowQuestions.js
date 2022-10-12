import { useRouter } from 'vue-router'
import { useQuestionsPageData } from "../../stores/questionsPageData";

export default () => {
    const router = useRouter()
    const questionsPageData = useQuestionsPageData()

    const show = (title, url) => {
        questionsPageData.setData(title, url)
        router.push('/questions')
    }

    return { show }
}