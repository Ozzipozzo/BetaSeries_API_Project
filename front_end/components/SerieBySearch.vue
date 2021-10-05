<template>
        <div 
            class="serie serie--search" 
            :title="serie.title" 
            :style="{ 'background-image': 'url(' + serie.poster + ')' }" 
            :id="'card-check-archive' + serie.id"
            v-if="serie.poster != undefined"
        >
            <div class="serie__box">
                <p>{{ serie.title }} </p>
            </div>

            <div v-if="archived != true">
                <div class="serie__add" v-if="isConnected != ''">
                    <div v-if="followed == true" @click="RemoveSerie" @mousedown="DisplayInfos(serie)" @mouseup="RemoveTimeout">
                        -
                    </div>
                    <div v-else-if="followed == false" @mousedown="DisplayInfos(serie)" @mouseup="RemoveTimeout">
                        i
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
        </div>
        <div 
            v-else
            class="serie serie--search" 
            :title="serie.title" 
            :style="{ 'background-image': 'url(https://st4.depositphotos.com/14953852/22772/v/600/depositphotos_227724992-stock-illustration-image-available-icon-flat-vector.jpg)' }" 
            :id="'card-check-archive' + serie.id"
        >
            <div class="serie__box">
                <p>{{ serie.title }} </p>
            </div>

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
        </div>
</template>

<script>
export default {
    name: "SerieBySearch",
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
                console.log(element);
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
.serie__add.serie__add--unarchived {
    background: rgba(0, 0, 0, 0.9);
}
.serie__add, .serie__box {
    z-index: 200;
}

</style>