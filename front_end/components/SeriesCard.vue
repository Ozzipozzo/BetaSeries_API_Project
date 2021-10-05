<template>
<NuxtLink :to="{ name: 'SerieInfos', query: { serieId: serie.id }}" v-if="this.serie.user.archived != true">
  <div class="card_container" :title="serie.title" :id="'card-check-archive' + serie.id">
      <div class="img_serie">
          <img class="img_show" :src="serie.images.show" alt="">
      </div>
      <div class="desc_serie">
            <h3 class="title_serie">
                {{ serie.title }}
            </h3>
            <div class="season_serie">
            <p>S{{ serie.seasons }} | E{{ serie.episodes }}</p>
            </div>
            <div class="desc_serie">
                <p>{{ serie.description }}</p>
            </div>
      </div>
  </div>
</NuxtLink>
<div :title="serie.title" class="card_container" :id="'card-check-archive' + serie.id" v-else>
    <div class="img_serie">
        <img class="img_show" :src="serie.images.show" alt="">
    </div>
    <div class="desc_serie">
        <h3 class="title_serie">
            {{ serie.title }}
        </h3>
        <div class="season_serie">
        <p>S{{ serie.seasons }} | E{{ serie.episodes }}</p>
        </div>
        <div class="desc_serie">
            <p>{{ serie.description }}</p>
        </div>
    </div>
    <div class="serie__add serie__add--unarchived" @click="Unarchived">
        <div>
            U
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: 'SeriesCard',
    props: ["serie"],
    data() {
        return {
            archived: false
        };
    },
    mounted() {
        if(this.serie.user.archived === true) {
            let currentCard = document.getElementById('card-check-archive' + this.serie.id);
            currentCard.classList.add("archived");
        }
    },
    methods: {
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
    }
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Manrope:wght@300;400&display=swap');
@media (max-width: 900px) {
    div.card_container {
        width: 98%;
        margin: auto;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
}
    .card_container {
        font-family: 'Manrope', sans-serif;
        margin: 0 auto;
        width: 70%;
        display: flex;
        background-color: rgb(219, 214, 208);
        margin-bottom: 2em;
        border-radius: 10px;
        box-shadow: rgba(99, 99, 99, 0.2) 2px 2px 8px 2px;
        color: black;
    }
    a.card_container p {
        text-decoration: none;
    }

    .card_container.archived {
        filter: grayscale(100%);
    }

    .img_show {
        width: 326px;
        height: 100%;
        border-radius: 10px 0 0 10px;
    }

    .title_serie {
        border: 1px solid white;
        border-radius: 28px;
        padding: 9px;
        margin-left: 3rem;
        display: inline-block;
    }

    .season_serie {
        margin-left: 4rem;
    }

    .desc_serie {
        width: 70%;
        margin-left: 3rem;
    }

    .desc_serie p{
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        display: -webkit-box;
        overflow: hidden;
    }
</style>