<template>
  <div>
      <SeriesCard v-for="item in content" :key="item.id" :serie="item" />
  </div>
</template>

<script>
import SeriesCard from '../components/SeriesCard.vue'

export default {
    data() {
        return {
            content: null,
            id: null,
            token: null
        }
    },
    mounted() {
        this.id = localStorage.getItem('userId'),
        this.token = localStorage.getItem('userToken')
        this.userShow();
    },
    components: {
        SeriesCard
    },
    methods: {
        userShow() {
            fetch('http://localhost:8000/api/users/show',
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id: this.id,
                    token: this.token
                })
            })
            .then((res) => res.json())
            .then((data) => {
                console.log(data);
                this.content = data;
            })
        }
    }
}
</script>

<style>

</style>