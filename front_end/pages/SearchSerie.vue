<template>
  <div class="home">
      <div v-if="genresList == null">
          ...Loading
      </div>
      <div v-else>
          <h2>Choisissez un genre de série ou écrivez le nom de la série à rechercher</h2>
          <select name="serieType" id="serieType" @change="GetSeries" v-model="serieCategory">
              <option :value="index" v-for="(genre, index) in genresList.genres" :key="index">{{genre}}</option>
          </select>
          <br />
          <br />
          <input type="text" name="serieName" id="serieName" @change="FetchByName" v-model="serieName" placeholder="Entrez le nom de la série" />

            <div v-if="content!= null">
                <section
                    class="serie--search__container"
                    v-if="byName == false"
                >
                    <SerieBySearch
                        v-for="serie in content"
                        :key="serie.id"
                        :serie="serie"
                    />
                </section>
                <section
                    class="serie--search__container"
                    v-else
                >
                    <SerieBySearch
                        v-for="serie in content"
                        :key="serie.id"
                        :serie="serie"
                    />
                </section>

                  <div v-if="content!= null">
                    <div v-if="byName == false">
                      <button id="myBtn" title="Aller en haut de la page" @click="TopFunction" >Haut de page</button>
                      <ul class="pagination">
                          <li @click="GetSeriesPagination($event)" class="pagination__dot active" value="0">1</li>
                          <li @click="GetSeriesPagination($event)" class="pagination__dot" value="20">2</li>
                          <li @click="GetSeriesPagination($event)" class="pagination__dot" value="40">3</li>
                      </ul>
                    </div>
                  </div>
            </div>
      </div>
  </div>
</template>


<script>
import SerieBySearch from "../components/SerieBySearch.vue";

export default {
  data() {
    return {
      isLoading: true,
      content: null,
      currentPage: null,
      genresList: null,
      serieCategory: null,
      serieName: null,
      byName: false,
    };
  },
    created() {
        this.GetGenres();
    },
    components: {
        SerieBySearch,
    },
  methods: {
      GetGenres() {
          fetch("http://127.0.0.1:8000/api/get/genres")
        .then((response) => response.json())
        .then((data) => {
          if (data) {
              this.genresList = data;
          } else {
              alert("Une erreur est survenu !");
          }
        }).catch(function(error) {
            console.log(error);
              alert("Une erreur est survenu !");
        });
      },
    GetSeries() {
      fetch("http://127.0.0.1:8000/api/show/byGenre", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          start: 0,
          genre: this.serieCategory
        }),
      })
        .then((response) => response.json())
        .then((data) => {
            this.byName = false;
            this.content = data;
        }).catch(function(error) {
            console.log(error);
        });
    },
    FetchByName() {
      fetch("http://127.0.0.1:8000/api/show/byName", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          name: this.serieName
        }),
      })
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            this.byName = true;
            this.content = data.shows;
        }).catch(function(error) {
            console.log(error);
        });
    },
    GetSeriesPagination(event) {
      fetch("http://127.0.0.1:8000/api/show/byGenre", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          start: event.target.value,
          genre: this.serieCategory
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data) {
              this.content = data;
              let dotList = document.getElementsByClassName('pagination__dot');
              dotList.forEach(element => {
                element.classList.remove('active');
              });
              event.target.classList.add('active');
          } else {
              alert("Une erreur est survenu !");
          }
        }).catch(function(error) {
            console.log(error);
        });
    },
    TopFunction() {
      document.body.scrollTop = 0;
      document.documentElement.scrollTop = 0;
    },
    DisplayInfos(serie) {
        this.pressTimer = window.setTimeout(function() { 
            window.location.href = "/SerieInfos?currentSerie=" + JSON.stringify(serie)
        },1000);

    },
    RemoveTimeout() {
        clearTimeout(this.pressTimer);
    },
  },
};
</script>

<style>
.serie--search__container {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
}
.serie.serie--search {
    width: 296px;
    height: 436px;
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
}

</style>