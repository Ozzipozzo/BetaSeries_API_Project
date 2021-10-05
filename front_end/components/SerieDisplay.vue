<template>
    <!-- <NuxtLink :to="{ name: 'SerieInfos', query: { currentSerie: JSON.stringify(serie) }}"> -->
        <div class="serie" :title="serie.title" :style="{ 'background-image': 'url(' + serie.images.show + ')' }" v-if="serie.images.show != undefined" :id="'card-check-archive' + this.serie.id">
                <div v-if="archived != true">
                    <div class="serie__add" v-if="isConnected != ''">
                            <div v-if="followed == true" @click="RemoveSerie" @mousedown="DisplayInfos(serie)" @mouseup="RemoveTimeout">
                                -
                            </div>
                            <div v-else-if="followed == false" @click="AddSerie" @mousedown="DisplayInfos(serie)" @mouseup="RemoveTimeout">
                                +
                            </div>
                    </div>
                    <div class="serie__add" v-else @mousedown="DisplayInfos(serie)" @mouseup="RemoveTimeout">
                        <div>
                            i
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div class="serie__add serie__add--unarchived" @click="Unarchived">
                        <div>
                            U
                        </div>
                    </div>
                </div>

                <div class="serie__box">
                    <p>{{ serie.title }} </p>
                </div>
        </div>
        <div class="serie" :title="serie.title" :style="{ 'background-image': 'url(' + serie.images.banner + ')' }" v-else-if="serie.images.banner != undefined" :id="'card-check-archive' + this.serie.id">
                <div v-if="archived != true">
                    <div class="serie__add" v-if="isConnected != ''">
                        <div v-if="followed == true" @click="RemoveSerie" @mousedown="DisplayInfos(serie)" @mouseup="RemoveTimeout">
                            -
                        </div>
                        <div v-else-if="followed == false" @click="AddSerie" @mousedown="DisplayInfos(serie)" @mouseup="RemoveTimeout">
                            +
                        </div>
                    </div>
                    <div class="serie__add" v-else @mousedown="DisplayInfos(serie)" @mouseup="RemoveTimeout">
                        <div>
                            i
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div class="serie__add serie__add--unarchived" @click="Unarchived">
                        <div>
                            U
                        </div>
                    </div>
                </div>

                <div class="serie__box">
                    <p>{{ serie.title }} </p>
                </div>
        </div>
        <div class="serie" :title="serie.title" :style="{ 'background-image': 'url(' + serie.images.poster + ')' }" v-else-if="serie.images.poster != undefined" :id="'card-check-archive' + this.serie.id">
                <div v-if="archived != true">
                    <div class="serie__add" v-if="isConnected != ''">
                        <div v-if="followed == true" @click="RemoveSerie" @mousedown="DisplayInfos(serie)" @mouseup="RemoveTimeout">
                            -
                        </div>
                        <div v-else-if="followed == false" @click="AddSerie" @mousedown="DisplayInfos(serie)" @mouseup="RemoveTimeout">
                            +
                        </div>
                    </div>
                    <div class="serie__add" v-else @mousedown="DisplayInfos(serie)" @mouseup="RemoveTimeout">
                        <div>
                            i
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div class="serie__add serie__add--unarchived" @click="Unarchived">
                        <div>
                            U
                        </div>
                    </div>
                </div>

                <div class="serie__box">
                    <p>{{ serie.title }} </p>
                </div>
        </div>
    <!-- </NuxtLink> -->
</template>

<script>
export default {
    name: "SerieDisplay",
    props: ["serie"],
    data() {
        return {
            isConnected: '',
            followed: false,
            archived: false
        };
    },
    mounted() {
        this.isConnected = localStorage.getItem("userToken");
        JSON.parse('['+localStorage.getItem("userSeries")+']').forEach(element => {
            if(element.title == this.serie.title) {
                this.followed = true;
                if(element.user.archived === true) {
                    this.archived = true;
                    let currentCard = document.getElementById('card-check-archive' + this.serie.id);
                    currentCard.classList.add("archived");
                }
            }
        });
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

        DisplayInfos(serie) {
            this.pressTimer = window.setTimeout(function() { 
                window.location.href = "/SerieInfos?serieId=" + serie.id
            },1000);

        },
        RemoveTimeout() {
            clearTimeout(this.pressTimer);
        },
        Unarchived() {
            fetch("http://localhost:8000/api/users/unarchived/show", {
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
                        element.user.archived = false;
                    }
                });
                let stringifySeries = JSON.stringify(currentSeries);
                let str = stringifySeries.substring(1, stringifySeries.length - 1);

                localStorage.setItem("userSeries", str);

                alert("Série desarchivée !")
            }).catch(function(err) {
                console.log(err);
                alert("Une erreur est survenu !")
            });
        }
    },
}
</script>

<style>
.serie {
    position: relative;
    max-width: 900px;
    min-height: 500px;
    margin: auto;
    background-repeat: no-repeat;
    background-clip: border-box;
    box-sizing: border-box;
    background-position: center center; 
    background-size: contain;
}
.serie.archived {
    filter: grayscale(100%);
}
.serie__box {
    position: absolute;
    box-sizing: border-box;
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    width: 100%;
    padding: 5px;
    bottom: 68px;
    font-family: serif;
    letter-spacing: 2px;
}
.serie__add {
    position: absolute;
    /* background-color: rgba(0, 0, 0, 0.65); */
    background: linear-gradient(to right, grey, black, grey);
    font-family: serif;
    border: 2px solid white;
    border-radius: 10px;
    color: white;
    right: 10px;
    top: 17%;
    width: 40px;
    height: 40px;
    text-align: center;
    box-sizing: border-box;
    font-size: 30px;
    font-weight: bold;
    cursor: pointer;
}
.serie__add.serie__add--unarchived {
    background: rgba(0, 0, 0, 0.9);
}
.serie__add, .serie__box {
    z-index: 200;
}

</style>