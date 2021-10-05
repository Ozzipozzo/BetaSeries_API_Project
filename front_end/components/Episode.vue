<template>
    <div>
        <div v-if="followed == true">
            <p class="episode-link"> 
                <span v-if="currentEpisode.user.seen == true" class="viewed" @click="EpisodeEventDisplay(currentEpisode.id)" @mousedown="DisplayInfos(serieImage)" @mouseup="RemoveTimeout">
                        {{ title }} 
                </span>
                <span v-else @click="EpisodeEventDisplay(currentEpisode.id)" @mousedown="DisplayInfos(serieImage)" @mouseup="RemoveTimeout" :id="'episode' + currentEpisode.id"> 
                    {{ title }} 
                </span>
                <div class="episode-link__dropdown" :id="'episodeDropdown' + id">
                    <ul v-if="currentEpisode.user.seen == true">
                        <li @click="RemoveEpisode" class="hover-mark">Marquer en non-vue</li>
                        <li class="episode-link__dropdown__comment">
                            <label :for="'comment' + id">Commenter l'épisode</label>
                            <textarea name="" :id="'comment' + id" cols="30" rows="10" style="overflow-x: hidden; width: 100%"></textarea>
                            <button @click="CommentEpisode">Envoyer le commentaire</button>
                        </li>
                        <li class="episode-link__dropdown__comment">
                            <label v-if="currentEpisode.note.user != 0">Noter l'épisode ( note actuelle : {{currentEpisode.note.user}} )</label>
                            <label v-else>Noter l'épisode</label>
                            <div>
                                <label :for="'1_radio_' + id">1</label>
                                <input type="radio" value="1" :name="currentEpisode.id" :id="'1_radio_' + id" />
                                <label :for="'2_radio_' + id">2</label>
                                <input type="radio" value="2" :name="currentEpisode.id" :id="'2_radio_' + id" />
                                <label :for="'3_radio_' + id">3</label>
                                <input type="radio" value="3" :name="currentEpisode.id" :id="'3_radio_' + id" checked />
                                <label :for="'4_radio_' + id">4</label>
                                <input type="radio" value="4" :name="currentEpisode.id" :id="'4_radio_' + id" />
                                <label :for="'5_radio_' + id">5</label>
                                <input type="radio" value="5" :name="currentEpisode.id" :id="'5_radio_' + id" />
                            </div>
                            <button @click="RateEpisode">Noter l'épisode</button>
                        </li>
                    </ul>
                    <ul v-else>
                        <li @click="ViewEpisode" class="hover-mark">Marquer en vue</li>
                        <li @click="ViewEpisodes" class="hover-mark">Marquer en vue avec les précédents</li>
                    </ul>
                </div>
            </p>
        </div>
        <div v-else>
            <p class="episode-link">
                <span @mousedown="DisplayInfos(serieImage)" @mouseup="RemoveTimeout" :id="'episode' + currentEpisode.id"> 
                    {{ title }} 
                </span>
            </p>
        </div>
        <hr />
    </div>
</template>

<script>
export default {
    name: 'Episode',
    props: ["currentEpisode" ,"title", "id", "serieImage", "followed"],
    data() {
        return {
            
        };
    },

    methods: {
        EpisodeEventDisplay(id) {
            let currentDropdown = document.getElementById('episodeDropdown' + id);
            currentDropdown.classList.toggle('active');
        },
        DisplayInfos() {
            this.pressTimer = window.setTimeout(() => { 
                window.location.href = "/EpisodeInfos?episodeId=" + this.id
            },1000);
        },
        RemoveTimeout() {
            clearTimeout(this.pressTimer);
        },
        ViewEpisode() {
            fetch("http://localhost:8000/api/users/add/view", {
                method: "POST",
                headers: {
                "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    id: this.currentEpisode.id,
                    token: localStorage.getItem("userToken"),
                }),
            })
            .then((response) => response.json())
            .then((data) => {
                alert("Épisode vue !");
            }).catch(function(err) {
                console.log(err);
                alert("Une erreur est survenu !");
            });
        },
        ViewEpisodes() {
            fetch("http://localhost:8000/api/users/add/views", {
                method: "POST",
                headers: {
                "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    id: this.currentEpisode.id,
                    token: localStorage.getItem("userToken"),
                }),
            })
            .then((response) => response.json())
            .then((data) => {
                alert("Épisode vue !");
            }).catch(function(err) {
                console.log(err);
                alert("Une erreur est survenu !");
            });
        },
        RemoveEpisode() {
            fetch("http://localhost:8000/api/users/remove/view", {
                method: "POST",
                headers: {
                "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    id: this.currentEpisode.id,
                    token: localStorage.getItem("userToken"),
                }),
            })
            .then((response) => response.json())
            .then((data) => {
                alert("L'épisode n'est plus vue !");
            }).catch(function(err) {
                console.log(err);
                alert("Une erreur est survenu !");
            });
        },
        CommentEpisode() {
            fetch("http://localhost:8000/api/users/add/comment", {
                method: "POST",
                headers: {
                "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    id: this.currentEpisode.id,
                    token: localStorage.getItem("userToken"),
                    text: document.getElementById('comment' + this.id).value
                }),
            })
            .then((response) => response.json())
            .then((data) => {
                alert("Commentaire envoyé !");
            }).catch(function(err) {
                console.log(err);
                alert("Une erreur est survenu !");
            });
        },
        RateEpisode() {
            let querySelector = 'input[name="' + this.currentEpisode.id + '"]:checked';
            fetch("http://localhost:8000/api/users/add/note", {
                method: "POST",
                headers: {
                "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    id: this.currentEpisode.id,
                    token: localStorage.getItem("userToken"),
                    note: document.querySelector(querySelector).value
                }),
            })
            .then((response) => response.json())
            .then((data) => {
                alert("Épisode noté !");
            }).catch(function(err) {
                console.log(err);
                alert("Une erreur est survenu !");
            });
        }
    },
};
</script>

<style>
.episode-link span{
    cursor: pointer;
}
.episode-link .viewed {
    color: limegreen;
}
.episode-link span.viewed:hover {
    color: limegreen;
}
.episode-link span:hover {
    text-decoration: underline;
    color: dodgerblue;
}
.episode-link span:active, .episode-link span.viewed:active {
    cursor: progress;
} 

.episode-link__dropdown {
    display: none;
}
.episode-link__dropdown.active {
    display: block;
}

.episode-link__dropdown ul li {
    padding: 5px;
}

.episode-link__dropdown ul li label, .episode-link__dropdown ul li div {
    margin: 10px 0px;
    padding: 10px 0px;
}

.episode-link__dropdown ul li button {
    margin: 10px 0px;
}

.hover-mark:hover {
    color: dodgerblue;
    cursor: pointer;
}

.episode-link__dropdown__comment{
    cursor: initial;
}
.episode-link__dropdown__comment textarea {
    display: block;
}
</style>