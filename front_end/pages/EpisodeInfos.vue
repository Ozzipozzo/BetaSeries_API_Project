<template>
    <div v-if="episode == null">
        Loading...
    </div>
    <div v-else class="full_episode">        
        <div class="episode__container" :style="{ 'background-image': 'url(' + episode.episodeImage + ')' }">
            <h3 class="episode__container__title">{{episode.episode.title}}  ( {{episode.episode.code}} )</h3>
            <p class="episode__container__note">{{ parseFloat(episode.episode.note.mean).toFixed(1)}}</p>
        </div>
        <p>Date de diffusion : {{episode.episode.date}}</p>
        <p>{{episode.episode.description}}</p>
        <div>
            <ul>
                <li v-for="comment in episode.commentList.comments" :key="comment.id" class="comment">
                    <img :src="comment.avatar" :alt="comment.login" :title="comment.login" v-if="comment.avatar != null">
                    <img src="http://www.audiobuffokc.com/wp-content/uploads/2016/02/no_image_available.png" :alt="comment.login" :title="comment.login" v-else>
                    <div class="comment__details">
                        <em>De : {{comment.login}}</em>
                        <p>{{comment.text}}</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    name: 'EpisodeInfos',
    props: ["serieImage"],
    data() {
        return {
            episodeId: null,
            episode: null
        };
    },

    mounted() {
        this.episodeId = this.$route.query.episodeId;

        fetch("http://localhost:8000/api/get/episode", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                id: this.$route.query.episodeId,
                token: localStorage.getItem("userToken"),
            }),
        })
        .then((response) => response.json())
        .then((data) => {
            this.episode = data;
        }).catch(function(err) {
            console.log(err);
            alert("Une erreur est survenu !")
        });
    },

    methods: {
    },
};
</script>

<style>
.full_episode {
    padding: 20px;
    text-align: center;
    font-family: sans-serif;
}
.full_episode > p {
    width: 50%;
    padding-top: 20px;
    margin: auto;
    font-size: 20px;
    line-height: 30px;
}
.episode__container {
    position: relative;
    margin: auto;
    max-width: 500px;
    min-height: 500px;
    background-repeat: no-repeat;
    background-clip: border-box;
    box-sizing: border-box;
    background-position: center center; 
    background-size: contain;
}

.episode__container__title, .episode__container__note {
    position: absolute;
    box-sizing: border-box;
    padding: 10px;
}
.episode__container__title {
    background-color: rgba(0, 0, 0, 0.7);
    color: white;
    width: 100%;
    bottom: 50px;
}
.episode__container__note {
    font-size: 25px;
    box-sizing: border-box;
    background-color: rgba(0, 0, 0, 0.9);
    color: white;
    top: 70px;
    right: 10px;
    border-radius: 50%;
    font-family: "Manrope", sans-serif;
}
.comment {
    display: flex;
    margin: 30px 0px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.3);
    box-shadow: 0px 0px 10px black;
}
.comment img {
    width: 300px;
    height: 300px;
}
.comment .comment__details {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    padding: 20px 10px;
}
.comment .comment__details p {
    width: 100%;
}
</style>