<template>
    <section class="serie-infos__container" v-if="serie == null">
        ...Loading
    </section>
    <section class="serie-infos__container" v-else>
        <div class="serie-infos__image_container">
            <img :src="serie.images.show" :alt="serie.title" v-if="serie.images.show != undefined" class="serie-infos__image" />
            <img :src="serie.images.banner" :alt="serie.title" v-else-if="serie.images.banner != undefined" class="serie-infos__image" />
            <img :src="serie.images.poster" :alt="serie.title" v-else-if="serie.images.poster != undefined" class="serie-infos__image" />
            <div class="serie__add" v-if="isConnected != ''">
                <div v-if="followed == true" @click="RemoveSerie">
                    -
                </div>
                <div v-else-if="followed == false" @click="AddSerie">
                    +
                </div>
            </div>
        </div>
        <div class="serie-infos__details">
            <div class="serie-infos__detailsBox">
                <p class="detail_title"> {{ serie.title }} </p>
                <p class="detail_score"> Note : {{ parseFloat(serie.notes.mean).toFixed(1) }} </p>
            </div>
            <div class="detail_description">
                <p> Nombre de saisons : {{ serie.seasons }} </p>
                <p> Nombre d'épisodes : {{ serie.episodes }} </p>
                <p> Durée des épisodes : {{ serie.length }} min </p>
            </div>
            <p class="detail_text"> {{ serie.description }} </p>
            <p class="detail_genre"> Genre : 
                <span v-for="genre in serie.genres" :key="genre">
                    {{ genre }} 
                </span> 
            </p>
            <p class="platform">
                <p style="color: black">
                    Plateformes : 
                </p>
                <span v-for="platform in serie.platforms.svods" :key="platform.id">
                    <img :src="platform.logo" :alt="platform.name" class="platform-logo" />
                </span> 
            </p>
        </div>
        <div class="detail_season_container">
            <div class="detail_select_season">
                <div>
                <select class="select_margin" @change="fetchEpisodes" v-model="saisonDetail" name="saison" id="saison-select">
                    <option v-for="saison in seasons" :key="saison.id" :value="saison.number">Saison {{ saison.number }}</option>
                </select>
                </div>
            </div>
          <div class="season_display_episode" v-if="episodes != undefined">
              <div class="image_season">
                  <img :src="episodes.seasonImage" alt="">
              </div>
              <div class="episode_list">
                  <div v-for="episode in episodes.episodes" :key="episode.id">
                      <Episode
                        :currentEpisode="episode"
                        :id="episode.id"
                        :title="episode.title"
                        :serieImage="serie.images"
                        :followed="followed"
                      />
                  </div>
              </div>

          </div>
        </div>
    </section>
    
</template>

<script>
import Episode from "../components/Episode.vue";

export default {
    name: "SerieInfos",
    props: ["currentSerie"],
    components: {
        Episode
    },
    data() {
        return {
            route_params: this.$route.query,
            serie: null,
            isConnected: '',
            followed: false,
            pressTimer: null,
            seasons: null,
            saisonDetail: null,
            episodes: null
        };
    },
    created() {
        if(this.$route.query.currentSerie) {
            this.serie = JSON.parse(this.$route.query.currentSerie)
        } else if(this.$route.query.serieId) {
            this.GetSerieInfos();
        }
    },
    mounted() {
        this.isConnected = localStorage.getItem("userToken");
        if(this.$route.query.currentSerie) {
            JSON.parse('['+localStorage.getItem("userSeries")+']').forEach(element => {
                if(element.title == this.serie.title) {
                    this.followed = true;
                }
            });
        }
    },
    methods: {
        RemoveSerie() {
            fetch("http://localhost:8000/api/users/remove/show", {
                method: "POST",
                headers: {
                "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    id: this.serie.id,
                    token: localStorage.getItem("userToken"),
                }),
            })
            .then((response) => response.json())
            .then((data) => {
                let currentSeries = JSON.parse('['+localStorage.getItem("userSeries")+']')

                currentSeries.forEach(element => {
                    if(element.title == this.serie.title) {
                        element.user.archived = true;
                    }
                });
                let stringifySeries = JSON.stringify(currentSeries);
                let str = stringifySeries.substring(1, stringifySeries.length - 1);

                localStorage.setItem("userSeries", str);
                alert("Série archivée !")
            }).catch(function(err) {
                console.log(err);
                alert("Une erreur est survenu !")
            });
        },
        AddSerie() {
            fetch("http://localhost:8000/api/users/add/show", {
                method: "POST",
                headers: {
                "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    id: this.serie.id,
                    token: localStorage.getItem("userToken"),
                }),
            })
            .then((response) => response.json())
            .then((data) => {
                let currentSeries = JSON.parse('['+localStorage.getItem("userSeries")+']')
                let newObjectPosition = currentSeries.length;
                currentSeries[newObjectPosition] = this.serie;
                var result = JSON.stringify(currentSeries).slice(1,-1);
                localStorage.setItem("userSeries", result);
                alert("Série ajoutée !")
            }).catch(function(err) {
                console.log(err);
                alert("Une erreur est survenu !")
            });
        },
        fetchEpisodes() {
          console.log();
          fetch("http://localhost:8000/api/get/episodes", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body:JSON.stringify({
              id: this.serie.id,
              season: this.saisonDetail,
              token: localStorage.getItem('userToken')
            }),
          })
            .then(res => res.json())
            .then(res => {
                console.log(res)
                this.episodes = res;
            }).catch(function(err) {
                console.log(err);
                alert("Une erreur est survenu !")
            });
        },
        DisplayInfos(serie) {
            this.pressTimer = window.setTimeout(function() { 
                window.location.href = "/EpisodeInfos?currentEpisode=" + episode
            },1000);
        },
        RemoveTimeout() {
            clearTimeout(this.pressTimer);
        },
        GetSerieInfos() {
            fetch("http://127.0.0.1:8000/api/show/one", {
                method: "POST",
                headers: {
                "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    id: this.$route.query.serieId
                }),
            })
            .then((response) => response.json())
            .then((data) => {
            if (data) {
                console.log(data);
                this.serie = data.show;
                this.seasons = this.serie.seasons_details;
                JSON.parse('['+localStorage.getItem("userSeries")+']').forEach(element => {
                    if(element.title == this.serie.title) {
                        this.followed = true;
                    }
                });
                console.log(data);
            } else {
                alert("Une erreur est survenu !");
            }
            }).catch(function(error) {
                console.log(error);
            });
        }
    }
}
</script>

<style>
.serie-infos__image_container {
    position: relative;
}

@import url("https://fonts.googleapis.com/css2?family=Manrope:wght@300;400&display=swap");

.serie-infos__container {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
}
.serie-infos__image {
  margin: auto;
  max-width: 1200px;
  max-height: 900px;
  background-color: rgba(0, 0, 0, 0.6);
}
.serie-infos__details {
  width: 100%;
  text-align: center;
  background-color: #f9f9f9;
  border-bottom: 1px solid grey;
}
.platform {
  display: flex;
  align-content: center;
}
.platform-logo {
  width: 40px;
  height: 40px;
  border-radius: 5px;
  margin-right: 8px;
}

.serie-infos__detailsBox {
    position: absolute;
    top: 40%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: rgba(0, 0, 0, 0.5);
    box-shadow: 0px 0px 10px black;
}

.detail_title {
  color: white;
  font-family: "Manrope", sans-serif;
  font-size: 56px;
  margin: 10px;
}

.detail_score {
  color: white;
  font-family: "Manrope", sans-serif;
  font-size: 26px;
  margin: 10px;
}

.detail_description {
  display: flex;
  justify-content: center;
  color: black;
}

.detail_description > p {
  margin-right: 1em;
  font-size: 14px;
}

.detail_text {
  font-size: 16px;
  margin-top: 0;
  color: rgb(56, 56, 56);
  margin: 0px 10%;
  line-height: 29px;
  font-family:sans-serif;
  padding: 20px 5px 30px 5px;
}

.detail_genre {
  font-size: 14px;
  margin-top: 0;
  color: black;
  font-family:monospace;
}

.detail_season_container {
  color: white;
  width: 70%;
}

.detail_select_season {
  display: flex;
  justify-content: space-between;
}
.detail_select_season div, .detail_select_season div select {
    width: 100%;
}
.detail_select_season div select {
    margin-bottom: 20px;
}

.select_margin {
  margin-top: 16px;
}

.image_season {
    width: 250px;
    height: 375px;
    margin: auto;
}
.image_season img {
  width: 100%;
  height: 100%;
}

</style>